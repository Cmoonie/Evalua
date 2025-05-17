<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// TUSSENTABEL om de tabellen aan elkaar te linken!!
class FormCompetency extends Model
{

    // FormCompetency hoort bij meerdere formulieren (1-op-veel relatie)
    public function form() {
        return $this->belongsTo(Form::class);
    }

    // FormCompetency hoort bij meerdere competenties (1-op-veel relatie)
    public function competency() {
        return $this->belongsTo(Competency::class);
    }
}
