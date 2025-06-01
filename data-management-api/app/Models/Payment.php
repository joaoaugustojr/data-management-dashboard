<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

#[ObservedBy([PaymentObserver::class])]
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
        'user_name',
        'user_email',
        'status',
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
     * Get filter by user name.
     */
    public function user_name($query, $value): mixed
    {
        $value = strtolower(trim($value));

        if (empty($value)) return $query;

        return $query->whereHas('user', function ($q) use ($value) {
            $q->whereRaw('LOWER(name) LIKE ?', ['%' . $value . '%']);
        });
    }

    /**
     * Get filter by user email.
     */
    public function user_email($query, $value): mixed
    {
        $value = strtolower(trim($value));

        if (empty($value)) return $query;

        return $query->whereHas('user', function ($q) use ($value) {
            $q->whereRaw('LOWER(email) LIKE ?', ['%' . $value . '%']);
        });
    }

    /**
     * Get the sort of the payment.
     */
    public function sort($query, $value): mixed
    {
        [$field, $direction] = explode(',', $value);

        if ($field === 'user_email') {
            return $query->whereHas('user', function ($q) use ($direction) {
                $q->orderBy('email', $direction);
            });
        }

        if ($field === 'user_name') {
            return $query->whereHas('user', function ($q) use ($direction) {
                $q->orderBy('name', $direction);
            });
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
