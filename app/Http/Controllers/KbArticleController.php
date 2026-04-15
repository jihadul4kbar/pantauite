<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKbArticleRequest;
use App\Http\Requests\UpdateKbArticleRequest;
use App\Models\KbArticle;
use App\Models\KbCategory;
use App\Services\KbService;
use Illuminate\Http\Request;

class KbArticleController extends Controller
{
    public function __construct(
        private KbService $kbService
    ) {}

    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', KbArticle::class);
        
        $filters = [];

        if ($request->filled('category_id')) {
            $filters['category_id'] = $request->category_id;
        }

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('tag')) {
            $filters['tag'] = $request->tag;
        }

        $articles = $this->kbService->getPaginatedArticles($filters);
        $categories = KbCategory::active()->get();

        return view('kb.index', compact('articles', 'categories', 'filters'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        $this->authorize('create', KbArticle::class);

        $categories = KbCategory::active()->with('parent')->get();

        return view('kb.create', compact('categories'));
    }

    /**
     * Store a newly created article.
     */
    public function store(StoreKbArticleRequest $request)
    {
        $article = $this->kbService->createArticle(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('kb.show', $article)
            ->with('success', 'Article ' . $article->article_number . ' created successfully.');
    }

    /**
     * Display the specified article.
     */
    public function show(KbArticle $kb)
    {
        $this->authorize('view', $kb);

        // Increment views
        $this->kbService->incrementViews($kb);

        // Load relationships
        $kb->load(['category', 'author', 'reviewer']);

        // Get related articles
        $relatedArticles = KbArticle::withoutTrashed()
            ->where('status', 'published')
            ->where('category_id', $kb->category_id)
            ->where('id', '!=', $kb->id)
            ->where('is_internal', $kb->is_internal)
            ->mostViewed(5)
            ->get();

        return view('kb.show', compact('kb', 'relatedArticles'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(KbArticle $kb)
    {
        $this->authorize('update', $kb);

        $categories = KbCategory::active()->get();

        return view('kb.edit', compact('kb', 'categories'));
    }

    /**
     * Update the specified article.
     */
    public function update(UpdateKbArticleRequest $request, KbArticle $kb)
    {
        $kb = $this->kbService->updateArticle(
            $kb,
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('kb.show', $kb)
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified article.
     */
    public function destroy(KbArticle $kb)
    {
        try {
            // Check authorization
            if (!auth()->user()->hasRole('it_manager') && !auth()->user()->hasRole('super_admin')) {
                return redirect()
                    ->back()
                    ->with('error', 'Unauthorized: Only IT Manager or Super Admin can delete articles.');
            }

            // Debug: Check what article ID we received
            $articleId = $kb->id ?? null;
            
            if (!$articleId) {
                return redirect()
                    ->back()
                    ->with('error', 'Article ID is null. Route model binding failed.');
            }

            // Check if article exists and get its current state
            $articleCheck = \Illuminate\Support\Facades\DB::table('kb_articles')
                ->where('id', $articleId)
                ->first();
            
            if (!$articleCheck) {
                // Let's see all articles to debug
                $allArticles = \Illuminate\Support\Facades\DB::table('kb_articles')
                    ->select('id', 'title', 'deleted_at')
                    ->get();
                    
                $articleIds = $allArticles->pluck('id')->toArray();
                
                return redirect()
                    ->back()
                    ->with('error', "Article ID {$articleId} not found. Available IDs: [" . implode(', ', $articleIds) . ']');
            }

            // Check if already deleted
            if (!is_null($articleCheck->deleted_at)) {
                return redirect()
                    ->route('kb.index')
                    ->with('success', 'Article has already been deleted.');
            }

            // Directly update deleted_at via query builder
            $affected = \Illuminate\Support\Facades\DB::table('kb_articles')
                ->where('id', $articleId)
                ->update(['deleted_at' => now()]);

            if ($affected > 0) {
                return redirect()
                    ->route('kb.index')
                    ->with('success', 'Article deleted successfully.');
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to delete article. Rows affected: ' . $affected);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Exception: ' . $e->getMessage());
        }
    }

    /**
     * Vote on article
     */
    public function vote(Request $request, KbArticle $kb)
    {
        $validated = $request->validate([
            'vote_type' => ['required', 'in:helpful,not_helpful'],
            'feedback' => ['nullable', 'string', 'max:500'],
        ]);

        $this->kbService->vote(
            $kb,
            $request->user(),
            $validated['vote_type'],
            $validated['feedback'] ?? null
        );

        return redirect()
            ->route('kb.show', $kb)
            ->with('success', 'Thank you for your feedback!');
    }

    /**
     * Upload image for KB article content.
     */
    public function uploadImage(Request $request)
    {
        $this->authorize('create', \App\Models\KbArticle::class);

        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Store in storage/app/public/articles
            $path = $file->storeAs('articles', $filename, 'public');
            
            // Generate URL
            $url = asset('storage/' . $path);
            
            // CKEditor expects 'url' in the response
            return response()->json([
                'url' => $url,
                'uploaded' => true,
                'fileName' => $filename,
            ]);
        }

        return response()->json([
            'error' => ['message' => 'No file uploaded'],
            'uploaded' => false,
        ], 400);
    }
}
