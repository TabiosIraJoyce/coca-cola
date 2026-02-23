<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Division;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division_id',  // ✅ added
        'subrole',      // ✅ added
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ✅ Available roles
     */
    public static function roles()
    {
        return ['admin', 'user'];
    }

    /**
     * ✅ Subroles
     */
    public static function subroles()
    {
        return ['Viewer', 'Editor'];
    }

    /**
     * ✅ Check if the user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * ✅ Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * ✅ Check if user is editor
     */
    public function isEditor()
    {
        return $this->subrole === 'Editor';
    }

    /**
     * ✅ Relation to Division
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
