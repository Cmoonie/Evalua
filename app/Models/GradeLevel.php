<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    /** @use HasFactory<\Database\Factories\GradeLevelFactory> */
    use HasFactory;

    // Dit is de relatie met de tussentabel (1-op-veel relatie)
    public function componentLevels() {
        return $this->hasMany(ComponentLevel::class);
    }

    // Een beoordelingsniveau heeft meerdere ingevulde componenten (1-op-veel relatie)
    public function filledComponents() {
        return $this->hasMany(FilledComponent::class);
    }
}
