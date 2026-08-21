<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    #[Scope]
    protected function filter(Builder $query, array $filters): void
    {
        if (!empty($filters['search'])) {

            $query->where(function (Builder $query) use ($filters) {

                $query->where(
                    'name',
                    'like',
                    '%' . $filters['search'] . '%'
                )
                    ->orWhere(
                        'email',
                        'like',
                        '%' . $filters['search'] . '%'
                    );
            });
        }


        if (!empty($filters['role_id'])) {

            $query->whereHas('roles', function (Builder $query) use ($filters) {

                $query->where('roles.id', $filters['role_id']);
            });
        }
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function hasPermission($permission)
    {
        return $this->roles
            ->flatMap->permissions
            ->contains('name', $permission);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function cart(){
        return $this->hasOne(Cart::class);
    }
    public function orders(){
        return $this->hasMany(Order::class);
    }
}


