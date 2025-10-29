<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_image',
        'is_active',
        'role_id',
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

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function is_active(){
    }

    public function isAdmin(){
        return $this->role->slug === "administrator";
    }

    public function isUser(){
        return $this->role->slug === "user";
    }

    public function isEditor(){
        return $this->role->slug === "editor";
    }

    public function isCustomer(){
        return $this->role->slug === "customer";
    }

    public function abilities(){
        $this->role->id ?? null;
        return[
            'admin'=>$this->isAdmin(),
            'user'=>$this->isUser(),
            'editor'=>$this->isEditor(),
            'customer'=>$this->isCustomer(),
        ];
    }
}
