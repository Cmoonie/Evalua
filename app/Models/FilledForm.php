<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilledForm extends Model
{
    // Een ingevuld formulier hoort bij maar 1 formulier, maar 1 formulier kan meerdere ingevulde formulieren hebben (veel-op-1 relatie)
    public function form() {
        return $this->belongsTo(Form::class);
    }

    // Een ingevuld formulier wordt ingevuld door 1 gebruiker, maar 1 gebruiker kan meerdere formulieren invullen (veel-op-1 relatie)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Een ingevuld formulier heeft meerdere ingevulde componenten (1-op-veel relatie)
    public function filledComponents() {
        return $this->hasMany(FilledComponent::class);
    }
}
