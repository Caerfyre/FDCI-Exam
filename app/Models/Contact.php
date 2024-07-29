<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'contactID';
    
    protected $fillable = [
        'contactID',
        'userID',
        'name',
        'company',
        'phone',
        'email',
    ];

    /**
     * Get the user contact
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
