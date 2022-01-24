<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ArticleCategories\StoreRequest;
use App\Http\Requests\Admin\ArticleCategories\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ArticleCategoriesController extends BaseController
{
    public function index(): View
    {
        $articleCategories = ArticleCategory::orderBy('name', 'asc')->paginate();

        return view('admin.article-categories.index', [
            'articleCategories' => $articleCategories,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.article-categories.create', [
            'articleCategory' => new ArticleCategory(),
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->only('name');

        ArticleCategory::create($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Article category created');
        }

        return redirect()->route('admin.article-categories.index')->with('success', 'Article category created');
    }

    public function edit(ArticleCategory $article_category, Request $request): View
    {
        return view('admin.article-categories.edit', [
            'articleCategory' => $article_category,
            'redirectUrl' => $this->redirectUrl($request),
        ]);
    }

    public function update(ArticleCategory $article_category, UpdateRequest $request): RedirectResponse
    {
        $data = $request->only('name');

        $article_category->update($data);

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Article category updated');
        }

        return redirect()->route('admin.article-categories.index')->with('success', 'Article category updated');
    }

    public function destroy(ArticleCategory $article_category, Request $request): RedirectResponse
    {
        $article_category->delete();

        if ($redirect_url = $this->redirectUrl($request)) {
            return redirect($redirect_url)->with('success', 'Article category deleted');
        }

        return redirect()->route('admin.article-categories.index')->with('success', 'Article category deleted');
    }
}
