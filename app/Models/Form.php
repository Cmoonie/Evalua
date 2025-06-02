<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    /** @use HasFactory<\Database\Factories\FormFactory> */
    use HasFactory;

    // Deze mogen ingevuld worden
    protected $fillable = [
        'user_id',
        'title',
        'subject',
        'oe_code',
        'description',
    ];

    // Veel formulieren kunnen aan 1 gebruiker toebehoren  (veel-op-1 relatie)
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Connectie met de tussentabel! (1-op-veel relatie)
    public function formCompetencies() {
        return $this->hasMany(FormCompetency::class);
    }

    // Een formulier kan meerdere keren ingevuld worden (1-op-veel relatie)
    public function filledForms() {
        return $this->hasMany(FilledForm::class);
    }
}
