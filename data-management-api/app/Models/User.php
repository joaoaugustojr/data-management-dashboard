<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUlids;
    use FilterQueryString;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'image',
    ];

    /**
     * Filterable attributes.
     *
     * @var array<int, string>
     */
    protected $filters = [
        'name',
        'email',
        'like',
        'in'
    ];

    /**
     * The attributes that should be casts to native types.
     *
     * @var array<int, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    /**
     * Filter by name.
     */
    public function name($query, $value): mixed
    {
        $value = strtolower(trim($value));
        return $query->whereRaw('LOWER(name) LIKE ?', ['%' . $value . '%']);
    }

    /**
     * Get the payments of the user.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, "user_id");
    }
}
