<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $table = 'tbl_transactions';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    public $fillable = [
        'date',
        'event_id',
        'ticket_id',
        'quantity',
        'total_price',
        'name',
        'email',
        'phone',
        'is_active'
    ];

    public function masterEvent(): BelongsTo
    {
        return $this->belongsTo(MasterEvent::class, 'event_id', 'id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
