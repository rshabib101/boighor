<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    protected $fillable = [
        'user_id', 'points', 'amount', 'payment_method', 'account_number',
        'status', 'admin_note', 'processed_by', 'processed_at'
    ];

    protected $casts = ['processed_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function processor() { return $this->belongsTo(User::class, 'processed_by'); }
}
