<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilledForm extends Model
{
    // Om massaal in te vullen
    protected $fillable = [
        'form_id',
        'user_id',
        'student_name',
        'student_number',
        'assignment',
        'business_name',
        'business_location',
        'start_date',
        'end_date',
    ];

    // Kut carbon
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

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
