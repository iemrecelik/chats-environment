<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $fillable = [
        'path',
    ];

    /**
     * Get all of the owning owner models.
     */
    public function owner()
    {
        return $this->morphTo();
    }
}
