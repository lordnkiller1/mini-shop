<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    
    protected $fillable = [
        'title',
        'status',
        'price',
        'category_id'
    ];

    protected static function booted()
    {
        static::creating(function ($product) {

            $product->slug = Str::slug($product->title);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    #[Scope]
    protected function filter(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
