public function index() {
    $tips = [
        [
            'category' => 'Zero Waste',
            'user' => '@eco_felix',
            'title' => 'Mastering the Art of Backyard Composting',
            'description' => 'Learn how to turn your kitchen scraps into nutrient-rich soil gold.',
            'likes' => 412,
            'comments' => 24,
            'image' => 'https://via.placeholder.com/400x300',
            'avatar' => 'https://via.placeholder.com/50'
        ],
        // ... Agrega los demás tips aquí
    ];

    return view('welcome', compact('tips'));
}