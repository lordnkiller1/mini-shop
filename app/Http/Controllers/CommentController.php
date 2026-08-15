<?php

namespace App\Http\Controllers;

use App\Enums\CommentStatus;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CommentController extends Controller implements HasMiddleware
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Product $product)
    {
        $comments = $product->comments()
            ->with('user')
            ->filter($request->only([
                'search',
                'status',
            ]))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.comments.index', compact('product', 'comments'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request, Product $product)
    {
        $data = $request->validated();

        $product->comments()->create([
            'user_id' => Auth::id(),
            'body' => $data['body'],
            'status' => CommentStatus::Pending,
        ]);

        return to_route('products.comments.index', $product)->with('success', 'کامنت با موفقیت ثبت شد');
    }

    public function update(UpdateCommentRequest $request, Product $product, Comment $comment)
    {
        $data = $request->validated();

        $comment->update([
            'status' => $data['status'],
        ]);

        return to_route('products.comments.index', $product)
            ->with('success', 'وضعیت کامنت تغییر کرد');
    }

    public function destroy(Product $product, Comment $comment)
    {
        $comment->delete();

        return to_route('products.comments.index', $product)
            ->with('success', 'کامنت با موفقیت حذف شد');
    }

    public static function middleware(): array
    {
        return [
            new Middleware(
                'permission:update_comment_status',
                only: ['update']
            ),

            new Middleware(
                'permission:delete_comment',
                only: ['destroy']
            ),
        ];
    }
}
