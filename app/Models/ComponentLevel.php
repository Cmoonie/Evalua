<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// TUSSENTABEL!!!
class ComponentLevel extends Model
{
    // Hoort bij meerdere componenten (1-op-veel relatie)
    public function component() {
        return $this->belongsTo(Component::class);
    }

    // Hoort bij meerdere beoordelingsniveau's (1-op-veel relatie)
    public function gradeLevel() {
        return $this->belongsTo(GradeLevel::class);
    }
}
