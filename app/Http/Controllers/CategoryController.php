<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(
                'permission:view_category',
                only: ['index', 'trash']
            ),

            new Middleware(
                'permission:create_category',
                only: ['create', 'store']
            ),

            new Middleware(
                'permission:edit_category',
                only: ['edit', 'update']
            ),

            new Middleware(
                'permission:delete_category',
                only: ['destroy']
            ),

            new Middleware(
                'permission:restore_category',
                only: ['restore']
            ),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::with('image')->filter($request->only(['search', 'status']))->latest()->paginate(10)->withQueryString();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $category->image()->create([
                'path' => $path
            ]);
        }

        return to_route('categories.index')->with('success', 'دسته‌بندی با موفقیت ثبت شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $category->load('image');
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $category->update($data);

        if ($request->hasFile('image')) {

            $path = $request->file('image')
                ->store('categories', 'public');

            if ($category->image) {

                Storage::disk('public')
                    ->delete($category->image->path);

                $category->image->update([
                    'path' => $path
                ]);
            } else {

                $category->image()->create([
                    'path' => $path
                ]);
            }
        }

        return to_route('categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ویرایش شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {

            return back()->with(
                'error',
                'این دسته بندی دارای محصول است و قابل حذف نیست'
            );
        }

        $category->delete();

        return back()->with(
            'success',
            'دسته بندی حذف شد'
        );
    }
    public function trash()
    {
        $categories = Category::onlyTrashed()->latest('deleted_at')->paginate(10);
        return view('categories.trash', compact('categories'));
    }
    public function restore(Category $category)
    {
        $category->restore();

        return to_route('categories.trash')->with(
            'success',
            'دسته بندی بازگردانی شد'
        );
    }
}
