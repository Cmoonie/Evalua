<?php

namespace App\Models;

use Database\Factories\CompetencyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competency extends Model
{
    /** @use HasFactory<CompetencyFactory> */
    use HasFactory;

    // Deze mogen ingevuld worden
    protected $fillable = [
        'name',
        'domain_description',
        'rating_scale',
        'complexity',
    ];

    // Dit is de connectie met de tussentabel (1-op-veel relatie)
    public function formCompetencies() {
        return $this->hasMany(FormCompetency::class);
    }

    // Een competentie heeft meerdere componenten (1-op-veel relatie)
    public function components() {
        return $this->hasMany(Component::class);
    }
}
