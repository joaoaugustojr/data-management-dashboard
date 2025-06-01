<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Payment extends Model
{
    use HasFactory, HasUlids;
    use FilterQueryString;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'default',
        'status',
        'amount',
    ];

    /**
     * Filterable attributes.
     *
     * @var array<int, string>
     */
    protected $filters = [
        'user_id',
        'status',
        'email',
        'sort',
    ];

    /**
     * The attributes that should be casts to native types.
     *
     * @var array<int, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PaymentStatus::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];
    }

    /**
     * Get the sort of the payment.
     */
    public function sort($query, $value): mixed
    {
        [$field, $direction] = explode(',', $value);

        if ($field === 'user_email') {
            return $query->select('payments.*')
                ->join('users', 'payments.user_id', 'users.id')
                ->orderBy('users.email', $direction);
        }

        if ($field === 'user_name') {
            return $query->select('payments.*')
                ->join('users', 'payments.user_id', 'users.id')
                ->orderBy('users.name', $direction);
        }

        return $query->orderBy($field, $direction);
    }

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
