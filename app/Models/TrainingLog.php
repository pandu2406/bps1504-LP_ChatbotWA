<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the user that performed the action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
