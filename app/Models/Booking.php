<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Models\Scopes\OwnerScope;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Booking extends Model
{
    //
    use LogsActivity;
    protected $table = 'bookings';
    protected $fillable = [
        'user_id',
        'apartment_id',
        'guest_name',
        'guest_email',
        'discount_id',
        'start_date',
        'end_date',
        'amount',
        'total_amount',
        'discount_amount',
        'net_amount',
        'status',
        'reference'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status',
                'total_amount',
                'start_date',
                'end_date',
            ])
            ->logOnlyDirty()
            ->useLogName('booking');
    }


    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        // 'status' => BookingStatus::class,
    ];




    /* ───────────────
     | RELATIONSHIPS
     ─────────────── */

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function guest()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function payments()
    {
        return $this->hasMany(BookingPayment::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function ownerEarning()
    {
        return $this->hasOne(OwnerEarning::class);
    }

    /* HELPERS  */

    public function paidAmount(): float
    {
        return (float) $this->payments()
            ->where('status', 'paid')
            ->sum('amount');
    }

    public function balanceAmount(): float
    {
        return max($this->amount - $this->paidAmount(), 0);
    }

    public function isFullyPaid(): bool
    {
        // return $this->paidAmount() >= $this->net_amount;
        return $this->balanceAmount() <= 0;
    }

    protected static function booted()
    {
        static::addGlobalScope(new OwnerScope());
    }

    // Booking Methods
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    public function refund()
    {
        $this->update(['status' => 'refunded']);
    }

    public function canBeRefunded(): bool
    {
        return $this->status === BookingStatus::APPROVED
            && $this->payments()->where('status', 'success')->exists();
    }
}
