<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'disputer_id',
        'disputed_user_id',
        'type',
        'subject',
        'description',
        'evidence_files',
        'status',
        'resolution',
        'resolved_by',
        'resolved_at'
    ];

    protected $casts = [
        'evidence_files' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function disputer()
    {
        return $this->belongsTo(User::class, 'disputer_id');
    }

    public function disputedUser()
    {
        return $this->belongsTo(User::class, 'disputed_user_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function messages()
    {
        return $this->hasMany(DisputeMessage::class);
    }
}
