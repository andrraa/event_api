<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $table = 'tbl_tickets';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    public $fillable = [
        'event_id',
        'name',
        'price',
        'quota',
        'information',
        'is_active'
    ];

    public function masterEvent(): BelongsTo
    {
        return $this->belongsTo(MasterEvent::class, 'event_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'ticket_id', 'id');
    }
}
