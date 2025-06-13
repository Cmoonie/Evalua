<?php

namespace App\Models;

use Database\Factories\ComponentLevelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// TUSSENTABEL!!!
class ComponentLevel extends Model
{
    /** @use HasFactory<ComponentLevelFactory> */
    use HasFactory;

    // Om allemaal in te vullen
    protected $fillable = [
        'component_id',
        'grade_level_id',
        'description',
    ];

    // Hoort bij meerdere componenten (1-op-veel relatie)
    public function component() {
        return $this->belongsTo(Component::class);
    }

    // Hoort bij meerdere beoordelingsniveau's (1-op-veel relatie)
    public function gradeLevel() {
        return $this->belongsTo(GradeLevel::class);
    }
}
