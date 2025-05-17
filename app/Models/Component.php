<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    /** @use HasFactory<\Database\Factories\ComponentFactory> */
    use HasFactory;

    // Meerdere componenten kunnen bij 1 competentie horen (veel-op-1 relatie)
    public function competency() {
        return $this->belongsTo(Competency::class);
    }

    // Een component heeft meerdere beoordelingsniveau's (1-op-veel relatie)
    public function levels() {
        return $this->hasMany(ComponentLevel::class);
    }
}
