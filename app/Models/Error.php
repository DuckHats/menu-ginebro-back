<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    use HasFactory;

    protected $table = 'errors';

    protected $fillable = [
        'error_code',
        'error_message',
        'stack_trace',
        'user_id',
        'session_id',
        'occurred_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
