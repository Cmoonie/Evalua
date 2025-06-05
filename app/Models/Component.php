<?php

namespace App\Models;

use Database\Factories\ComponentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    /** @use HasFactory<ComponentFactory> */
    use HasFactory;

    // Deze mogen ingevuld worden
    protected $fillable = [
        'competency_id',
        'name',
        'description',
    ];

    // Meerdere componenten kunnen bij 1 competentie horen (veel-op-1 relatie)
    public function competency() {
        return $this->belongsTo(Competency::class);
    }

    // Een component heeft meerdere beoordelingsniveau's (1-op-veel relatie)
    public function levels() {
        return $this->hasMany(ComponentLevel::class);
    }
}
