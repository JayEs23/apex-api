<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

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
        'roles'
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
        'password' => 'hashed',
        'roles' => 'array', //define role attribute as an array
    ];

    // Check if user has admin role
    public function isAdmin()
    {
        return in_array('admin', $this->roles);
    }

    /**
     * Assign a role to the user.
     *
     * @param string $role
     * @return void
     */
    public function assignRole(string $role)
    {
        // Ensure that $this->roles is initialized as an array even if it's initially null
        $roles = $this->roles ?? [];

        if (!in_array($role, $roles)) {
            $roles[] = $role;
            $this->roles = $roles;
            $this->save();
        }
    }

    /**
     * Remove a role from the user.
     *
     * @param string $role
     * @return void
     */
    public function removeRole(string $role)
    {
        $this->roles = array_diff($this->roles, [$role]);
        $this->save();
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }
}
