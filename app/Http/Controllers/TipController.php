<?php

namespace App\Http\Controllers;

use App\Helpers\PaginationHelper;
use App\Models\Tip;
use App\Models\User;
use App\Services\Translation\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TipController extends Controller
{
    public function __construct(
        private TranslationService $translator
    ) {}

    /**
     * Translate any user-generated content to the current locale.
     * Uses DeepL auto-detection so posts and comments written in any
     * language are correctly translated regardless of locale.
     */
    private function translateContent(string $text): string
    {
        return $this->translator->translateAutoDetect($text, app()->getLocale());
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $sortBy = $request->input('sort', 'desc');
        $category = $request->input('category');

        $query = Tip::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments']);

        // Aplicar filtro de categoría si existe
        if ($category) {
            $query->where('category', $category);
        }

        // Aplicar búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        // Aplicar ordenamiento por fecha
        $query->orderBy('created_at', $sortBy === 'asc' ? 'asc' : 'desc');

        $perPage = PaginationHelper::getPerPage($request);
        $tips = $query->paginate($perPage)
            ->appends(['search' => $search, 'sort' => $sortBy, 'category' => $category, 'per_page' => $perPage])
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => $tip->user->name,
                    'user_obj' => $tip->user,
                    'title' => $this->translateContent($tip->title),
                    'description' => $this->translateContent($tip->description),
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $user ? $tip->likes()->where('user_id', $user->id)->exists() : false,
                    'is_bookmarked' => $user ? $tip->bookmarks()->where('user_id', $user->id)->exists() : false,
                ];
            })
            ->onEachSide(2);

        return view('welcome', compact('tips', 'search', 'sortBy', 'category'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $sortBy = $request->input('sort', 'desc');
        $category = $request->input('category');

        $query = Tip::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments']);

        // Aplicar filtro de categoría si existe
        if ($category) {
            $query->where('category', $category);
        }

        // Aplicar búsqueda si existe
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
            });
        }

        // Aplicar ordenamiento por fecha
        $query->orderBy('created_at', $sortBy === 'asc' ? 'asc' : 'desc');

        $perPage = PaginationHelper::getPerPage($request);
        $tips = $query->paginate($perPage)
            ->appends(['search' => $search, 'sort' => $sortBy, 'category' => $category, 'per_page' => $perPage])
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => $tip->user->name,
                    'user_obj' => $tip->user,
                    'title' => $this->translateContent($tip->title),
                    'description' => $this->translateContent($tip->description),
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                    'is_bookmarked' => $tip->bookmarks()->where('user_id', $user->id)->exists(),
                ];
            })
            ->onEachSide(2);

        return view('dashboard', compact('tips', 'search', 'sortBy', 'category'));
    }

    public function following(Request $request)
    {
        $user = Auth::user();

        // Obtener los IDs de los usuarios que sigue el usuario actual
        $followingIds = $user->following()->pluck('users.id');

        // Si no sigue a nadie, retornar array vacío
        if ($followingIds->isEmpty()) {
            return view('following', ['tips' => []]);
        }

        // Obtener los tips de los usuarios que sigue
        $perPage = PaginationHelper::getPerPage($request);
        $tips = Tip::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->whereIn('user_id', $followingIds)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage])
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => $tip->user->name,
                    'user_obj' => $tip->user,
                    'title' => $this->translateContent($tip->title),
                    'description' => $this->translateContent($tip->description),
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                    'is_bookmarked' => $tip->bookmarks()->where('user_id', $user->id)->exists(),
                ];
            })
            ->onEachSide(2);

        return view('following', compact('tips'));
    }

    public function saved(Request $request)
    {
        $user = Auth::user();

        // Obtener los tips guardados por el usuario
        $perPage = PaginationHelper::getPerPage($request);
        $tips = $user->bookmarkedTips()
            ->with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->orderBy('bookmarks.created_at', 'desc')
            ->paginate($perPage)
            ->appends(['per_page' => $perPage])
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => $tip->user->name,
                    'user_obj' => $tip->user,
                    'title' => $this->translateContent($tip->title),
                    'description' => $this->translateContent($tip->description),
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                    'is_bookmarked' => true, // Always true in saved page
                ];
            })
            ->onEachSide(2);

        return view('saved', compact('tips'));
    }

    /**
     * Mostrar el formulario para crear un nuevo tip
     */
    public function create()
    {
        return view('tips.create');
    }

    /**
     * Guardar un nuevo tip en la base de datos
     */
    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'category' => 'required|string|in:hogar,alimentacion,consumo,transporte,residuos,energia,naturaleza,educacion',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // 5MB max
        ]);

        // Manejar la subida de imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tips', 'public');
            $imagePath = Storage::url($imagePath);
        }

        // Crear el tip
        $tip = Tip::create([
            'user_id' => Auth::id(),
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
        ]);

        // Redirigir con mensaje de éxito (usará el notificador flotante en la esquina superior derecha)
        return redirect()->route('dashboard')->with('success', __('messages.success.created'));
    }

    /**
     * Mostrar un tip individual con sus comentarios
     */
    public function show(Tip $tip)
    {
        // Cargar el tip con sus relaciones
        $tip->load(['user', 'likes', 'comments']);

        // Obtener los comentarios principales (sin parent_id) con sus respuestas
        $commentsPerPage = PaginationHelper::getPerPage(request(), 10);
        $comments = $tip->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->paginate($commentsPerPage)
            ->appends(['per_page' => $commentsPerPage])
            ->onEachSide(2);

        $tip->title = $this->translateContent($tip->title);
        $tip->description = $this->translateContent($tip->description);

        // Traducir comentarios principales y sus respuestas
        // Usa auto-detect para manejar comentarios escritos en cualquier idioma
        $comments->through(function ($comment) {
            $comment->content = $this->translateContent($comment->content);

            // Traducir respuestas anidadas
            if ($comment->relationLoaded('replies')) {
                $comment->replies->each(function ($reply) {
                    $reply->content = $this->translateContent($reply->content);
                });
            }

            return $comment;
        });

        return view('tips.show', compact('tip', 'comments'));
    }

    /**
     * Mostrar el perfil del usuario con sus tips
     */
    public function profile(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'my-tips'); // Default to 'my-tips'
        $perPage = PaginationHelper::getPerPage($request);

        // Conteo de posts del usuario
        $postsCount = $user->tips()->count();

        if ($tab === 'saved') {
            // Obtener los tips guardados por el usuario
            $tips = $user->bookmarkedTips()
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('bookmarks.created_at', 'desc')
                ->paginate($perPage)
                ->appends(['tab' => $tab, 'per_page' => $perPage])
                ->through(function ($tip) use ($user) {
                    return [
                        'id' => $tip->id,
                        'user_id' => $tip->user->id,
                        'category' => $tip->category,
                        'user' => $tip->user->name,
                        'user_obj' => $tip->user,
                        'title' => $this->translateContent($tip->title),
                        'description' => $this->translateContent($tip->description),
                        'likes' => $tip->likes_count,
                        'comments' => $tip->comments_count,
                        'image' => $tip->image,
                        'published_at' => $tip->created_at->diffForHumans(),
                        'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                        'is_bookmarked' => true,
                    ];
                })
                ->onEachSide(2);
        } else {
            // Obtener los tips publicados por el usuario
            $tips = $user->tips()
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->appends(['tab' => $tab, 'per_page' => $perPage])
                ->through(function ($tip) use ($user) {
                    return [
                        'id' => $tip->id,
                        'user_id' => $tip->user->id,
                        'category' => $tip->category,
                        'user' => $tip->user->name,
                        'user_obj' => $tip->user,
                        'title' => $this->translateContent($tip->title),
                        'description' => $this->translateContent($tip->description),
                        'likes' => $tip->likes_count,
                        'comments' => $tip->comments_count,
                        'image' => $tip->image,
                        'published_at' => $tip->created_at->diffForHumans(),
                        'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                        'is_bookmarked' => $tip->bookmarks()->where('user_id', $user->id)->exists(),
                    ];
                })
                ->onEachSide(2);
        }

        return view('profile', compact('user', 'tips', 'postsCount', 'tab'));
    }

    /**
     * Mostrar el perfil de un usuario específico
     */
    public function showUserProfile(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $tab = $request->query('tab', 'posts'); // Default to 'posts'
        $perPage = PaginationHelper::getPerPage($request);

        // Conteo de posts del usuario
        $postsCount = $user->tips()->count();

        // Normalizar el tab name para que sea consistente
        if ($tab === 'my-tips') {
            $tab = 'posts';
        }

        if ($tab === 'saved') {
            // Solo mostrar guardados si es el usuario actual
            if ($currentUser && $currentUser->id === $user->id) {
                $tips = $user->bookmarkedTips()
                    ->with(['user', 'likes', 'comments'])
                    ->withCount(['likes', 'comments'])
                    ->orderBy('bookmarks.created_at', 'desc')
                    ->paginate($perPage)
                    ->appends(['tab' => $tab, 'per_page' => $perPage])
                    ->through(function ($tip) use ($currentUser) {
                        return [
                            'id' => $tip->id,
                            'user_id' => $tip->user->id,
                            'category' => $tip->category,
                            'user' => $tip->user->name,
                            'user_obj' => $tip->user,
                            'title' => $this->translateContent($tip->title),
                            'description' => $this->translateContent($tip->description),
                            'likes' => $tip->likes_count,
                            'comments' => $tip->comments_count,
                            'image' => $tip->image,
                            'published_at' => $tip->created_at->diffForHumans(),
                            'is_liked' => $currentUser ? $tip->likes()->where('user_id', $currentUser->id)->exists() : false,
                            'is_bookmarked' => true,
                        ];
                    })
                    ->onEachSide(2);
            } else {
                $tips = collect([]);
            }
        } else {
            // Obtener los tips publicados por el usuario
            $tips = $user->tips()
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->appends(['tab' => $tab, 'per_page' => $perPage])
                ->through(function ($tip) use ($currentUser) {
                    return [
                        'id' => $tip->id,
                        'user_id' => $tip->user->id,
                        'category' => $tip->category,
                        'user' => $tip->user->name,
                        'user_obj' => $tip->user,
                        'title' => $this->translateContent($tip->title),
                        'description' => $this->translateContent($tip->description),
                        'likes' => $tip->likes_count,
                        'comments' => $tip->comments_count,
                        'image' => $tip->image,
                        'published_at' => $tip->created_at->diffForHumans(),
                        'is_liked' => $currentUser ? $tip->likes()->where('user_id', $currentUser->id)->exists() : false,
                        'is_bookmarked' => $currentUser ? $tip->bookmarks()->where('user_id', $currentUser->id)->exists() : false,
                    ];
                })
                ->onEachSide(2);
        }

        return view('profile', compact('user', 'tips', 'postsCount', 'tab'));
    }

    /**
     * Eliminar un tip
     */
    public function destroy(Tip $tip)
    {
        // Verificar que el usuario autenticado sea el dueño del tip
        if (Auth::id() !== $tip->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este post.'
            ], 403);
        }

        try {
            // Si el tip tiene una imagen, eliminarla del storage
            if ($tip->image) {
                // Extraer la ruta relativa de la imagen
                $imagePath = str_replace('/storage/', '', $tip->image);
                Storage::disk('public')->delete($imagePath);
            }

            // Eliminar el tip (los likes, bookmarks y comments se eliminan automáticamente por cascada)
            $tip->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post eliminado exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el post. Por favor intenta de nuevo.'
            ], 500);
        }
    }
}

