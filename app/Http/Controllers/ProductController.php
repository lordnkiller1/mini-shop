<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('category', 'image')
        ->filter($request->only(['search','category_id','status']))->latest()->paginate(10)->withQueryString();
        $categories = Category::all();
        return view('products.index', compact('products','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create',  compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::create($data);
        if ($request->hasFile('image')) {

            $path = $request->file('image')
                ->store('products', 'public');


            $product->image()->create([
                'path' => $path
            ]);
        }
        return to_route('products.index')->with('success', 'دسته‌بندی با موفقیت ثبت شد');
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
    public function edit(Product $product)
    {
        $product->load('image');

        $categories = Category::all();

        return view('products.edit', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(UpdateProductRequest $request, Product $product)
{
    $data = $request->validated();

    $product->update($data);


    if ($request->hasFile('image')) {

        $path = $request->file('image')
            ->store('products', 'public');


        if ($product->image) {

            Storage::disk('public')
                ->delete($product->image->path);

            $product->image->update([
                'path' => $path,
            ]);

        } else {

            $product->image()->create([
                'path' => $path,
            ]);
        }
    }


    return to_route('products.index')
        ->with('success', 'محصول با موفقیت ویرایش شد');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if($product->image){
            Storage::disk('public')->delete($product->image->path);
            $product->image()->delete();
        }

        $product->delete();
        return to_route('products.index')->with('success', 'دسته‌بندی حذف شد');
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view_product', only: ['index']),

            new Middleware('permission:create_product', only: [
                'create',
                'store'
            ]),

            new Middleware('permission:edit_product', only: [
                'edit',
                'update'
            ]),

            new Middleware('permission:delete_product', only: [
                'destroy'
            ]),
        ];
    }
}
