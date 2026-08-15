<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'categories' => Category::count(),
            'comments' => Comment::count(),
        ];


        $latestProducts = Product::latest()
            ->take(5)
            ->get();


        $latestUsers = User::latest()
            ->take(5)
            ->get();


        $latestComments = Comment::with('user', 'product')
            ->latest()
            ->take(5)
            ->get();


        return view('dashboard', compact(
            'stats',
            'latestProducts',
            'latestUsers',
            'latestComments'
        ));
    }
}