<?php

namespace App\Http\Controllers;

use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TipController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $tips = Tip::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
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

        return view('welcome', compact('tips'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        $tips = Tip::with(['user', 'likes', 'comments'])
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
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

        return view('dashboard', compact('tips'));
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
            ->get()
            ->map(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
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
            ->get()
            ->map(function ($tip) use ($user) {
                return [
                    'id' => $tip->id,
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
}

