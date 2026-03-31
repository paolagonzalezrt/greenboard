<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TipController extends Controller
{
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

        $tips = $query->paginate(20)
            ->appends(['search' => $search, 'sort' => $sortBy, 'category' => $category])
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => '@' . $tip->user->name,
                    'title' => $tip->title,
                    'description' => $tip->description,
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'avatar' => $tip->user->photo ?? 'https://via.placeholder.com/50',
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $user ? $tip->likes()->where('user_id', $user->id)->exists() : false,
                    'is_bookmarked' => $user ? $tip->bookmarks()->where('user_id', $user->id)->exists() : false,
                ];
            });

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

        $tips = $query->paginate(20)
            ->appends(['search' => $search, 'sort' => $sortBy, 'category' => $category])
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => $tip->user->name,
                    'title' => $tip->title,
                    'description' => $tip->description,
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'avatar' => $tip->user->photo ?? 'https://via.placeholder.com/50',
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                    'is_bookmarked' => $tip->bookmarks()->where('user_id', $user->id)->exists(),
                ];
            });

        return view('dashboard', compact('tips', 'search', 'sortBy', 'category'));
    }

    public function following()
    {
        $user = Auth::user();

        // Obtener los IDs de los usuarios que sigue el usuario actual
        $followingIds = $user->following()->pluck('users.id');

        // Si no sigue a nadie, retornar array vacío
        if ($followingIds->isEmpty()) {
            return view('following', ['tips' => []]);
        }

        // Obtener los tips de los usuarios que sigue
        $tips = Tip::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->whereIn('user_id', $followingIds)
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => '@' . $tip->user->name,
                    'title' => $tip->title,
                    'description' => $tip->description,
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'avatar' => $tip->user->photo ?? 'https://via.placeholder.com/50',
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                    'is_bookmarked' => $tip->bookmarks()->where('user_id', $user->id)->exists(),
                ];
            });

        return view('following', compact('tips'));
    }

    public function saved()
    {
        $user = Auth::user();

        // Obtener los tips guardados por el usuario
        $tips = $user->bookmarkedTips()
            ->with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->orderBy('bookmarks.created_at', 'desc')
            ->paginate(20)
            ->through(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
                    'user_id' => $tip->user->id,
                    'category' => $tip->category,
                    'user' => '@' . $tip->user->name,
                    'title' => $tip->title,
                    'description' => $tip->description,
                    'likes' => $tip->likes_count,
                    'comments' => $tip->comments_count,
                    'image' => $tip->image,
                    'avatar' => $tip->user->photo ?? 'https://via.placeholder.com/50',
                    'published_at' => $tip->created_at->diffForHumans(),
                    'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                    'is_bookmarked' => true, // Always true in saved page
                ];
            });

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
            'category' => 'required|string|in:Home,Energy,Consumption,Transport,Food,Zero Waste',
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

        // Redirigir con mensaje de éxito
        return redirect()->route('dashboard')->with('success', 'Post created successfully!');
    }

    /**
     * Mostrar un tip individual con sus comentarios
     */
    public function show(Tip $tip)
    {
        // Cargar el tip con sus relaciones
        $tip->load(['user', 'likes', 'comments']);

        // Obtener los comentarios principales (sin parent_id) con sus respuestas
        $comments = $tip->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tips.show', compact('tip', 'comments'));
    }

    /**
     * Mostrar el perfil del usuario con sus tips
     */
    public function profile(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'my-tips'); // Default to 'my-tips'

        // Conteo de posts del usuario
        $postsCount = $user->tips()->count();

        if ($tab === 'saved') {
            // Obtener los tips guardados por el usuario
            $tips = $user->bookmarkedTips()
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('bookmarks.created_at', 'desc')
                ->paginate(20)
                ->through(function ($tip) use ($user) {
                    return [
                        'id' => $tip->id,
                        'user_id' => $tip->user->id,
                        'category' => $tip->category,
                        'user' => $tip->user->name,
                        'title' => $tip->title,
                        'description' => $tip->description,
                        'likes' => $tip->likes_count,
                        'comments' => $tip->comments_count,
                        'image' => $tip->image,
                        'avatar' => $tip->user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($tip->user->name) . '&size=100&background=13ec5b&color=102216&bold=true',
                        'published_at' => $tip->created_at->diffForHumans(),
                        'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                        'is_bookmarked' => true,
                    ];
                });
        } else {
            // Obtener los tips publicados por el usuario
            $tips = $user->tips()
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('created_at', 'desc')
                ->paginate(20)
                ->through(function ($tip) use ($user) {
                    return [
                        'id' => $tip->id,
                        'user_id' => $tip->user->id,
                        'category' => $tip->category,
                        'user' => $tip->user->name,
                        'title' => $tip->title,
                        'description' => $tip->description,
                        'likes' => $tip->likes_count,
                        'comments' => $tip->comments_count,
                        'image' => $tip->image,
                        'avatar' => $tip->user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($tip->user->name) . '&size=100&background=13ec5b&color=102216&bold=true',
                        'published_at' => $tip->created_at->diffForHumans(),
                        'is_liked' => $tip->likes()->where('user_id', $user->id)->exists(),
                        'is_bookmarked' => $tip->bookmarks()->where('user_id', $user->id)->exists(),
                    ];
                });
        }

        return view('profile', compact('tips', 'postsCount', 'tab'));
    }

    /**
     * Mostrar el perfil de un usuario específico
     */
    public function showUserProfile(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $tab = $request->query('tab', 'posts'); // Default to 'posts'

        // Conteo de posts del usuario
        $postsCount = $user->tips()->count();

        // Check if current user is following this user
        $isFollowing = $currentUser ? $currentUser->isFollowing($user->id) : false;

        if ($tab === 'saved') {
            // Obtener los tips guardados por el usuario
            $tips = $user->bookmarkedTips()
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('bookmarks.created_at', 'desc')
                ->paginate(20)
                ->through(function ($tip) use ($currentUser) {
                    return [
                        'id' => $tip->id,
                        'user_id' => $tip->user->id,
                        'category' => $tip->category,
                        'user' => $tip->user->name,
                        'title' => $tip->title,
                        'description' => $tip->description,
                        'likes' => $tip->likes_count,
                        'comments' => $tip->comments_count,
                        'image' => $tip->image,
                        'avatar' => $tip->user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($tip->user->name) . '&size=100&background=13ec5b&color=102216&bold=true',
                        'published_at' => $tip->created_at->diffForHumans(),
                        'is_liked' => $currentUser ? $tip->likes()->where('user_id', $currentUser->id)->exists() : false,
                        'is_bookmarked' => true,
                    ];
                });
        } else {
            // Obtener los tips publicados por el usuario
            $tips = $user->tips()
                ->with(['user', 'likes', 'comments'])
                ->withCount(['likes', 'comments'])
                ->orderBy('created_at', 'desc')
                ->paginate(20)
                ->through(function ($tip) use ($currentUser) {
                    return [
                        'id' => $tip->id,
                        'user_id' => $tip->user->id,
                        'category' => $tip->category,
                        'user' => $tip->user->name,
                        'title' => $tip->title,
                        'description' => $tip->description,
                        'likes' => $tip->likes_count,
                        'comments' => $tip->comments_count,
                        'image' => $tip->image,
                        'avatar' => $tip->user->photo ?? 'https://ui-avatars.com/api/?name=' . urlencode($tip->user->name) . '&size=100&background=13ec5b&color=102216&bold=true',
                        'published_at' => $tip->created_at->diffForHumans(),
                        'is_liked' => $currentUser ? $tip->likes()->where('user_id', $currentUser->id)->exists() : false,
                        'is_bookmarked' => $currentUser ? $tip->bookmarks()->where('user_id', $currentUser->id)->exists() : false,
                    ];
                });
        }

        return view('users.show', compact('user', 'tips', 'postsCount', 'tab', 'isFollowing'));
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

