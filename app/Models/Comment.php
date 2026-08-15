<?php

namespace App\Models;


use App\Enums\CommentStatus as EnumsCommentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;


class Comment extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'body',
        'status',
    ];
    #[Scope]
    protected function filter(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {

            $query->where(
                'body',
                'like',
                '%' . $filters['search'] . '%'
            );
        }


        if (isset($filters['status']) && $filters['status'] !== '') {

            $query->where(
                'status',
                $filters['status']
            );
        }
    }
    protected function casts(): array
    {
        return [
            'status' => EnumsCommentStatus::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
