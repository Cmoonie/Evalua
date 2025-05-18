<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilledComponent extends Model
{
    // Om massaal in te vullen
    protected $fillable = [
        'component_id',
        'grade_level_id',
        'filled_form_id',
        'comment',
    ];

    // Een ingvld component hoort bij 1 ingvld formulier, maar 1 ingvld formulier kan meerdere  ingvlde componenten hebben (veel-op-1 relatie)
    public function filledForm() {
        return $this->belongsTo(FilledForm::class);
    }

    // Een ingvld component hoort bij 1 component, maar een component kan meerdere keren ingevuld worden (veel-op-1 relatie)
    public function component() {
        return $this->belongsTo(Component::class);
    }

    // Een ingevld component hoort bij 1 beoordelingsniveau, maar een beoordelingsniveau kan bij meerdere ingevulde componenten gebruikt worden (veel-op-1 relatie)
    public function gradeLevel() {
        return $this->belongsTo(GradeLevel::class);
    }
}
