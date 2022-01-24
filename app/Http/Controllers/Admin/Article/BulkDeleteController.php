<?php

namespace App\Http\Controllers\Admin\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Articles\BulkDeleteRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class BulkDeleteController extends Controller
{
    public function __invoke(BulkDeleteRequest $request): RedirectResponse
    {
        try {
            $articleIds = $request->validated()['article_ids'];

            $articlesToDelete = Article::select('id')->whereIn('id', $articleIds)->get();

            $failedToDelete = $articlesToDelete->filter(function (Article $article) {
                try {
                    return !$article->delete();
                } catch (Throwable $e) {
                    Log::error($e);
                    return true;
                }
            });

            if ($failedToDelete->isEmpty()) {
                return redirect()->back()->with('success', 'Successfully deleted articles');
            } else {
                return redirect()->back()->with('warning', 'There was an issue when deleting some articles');
            }
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'Failed to delete articles');
        }
    }
}
