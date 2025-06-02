<?php

namespace App\Helpers;

use App\Models\FilledForm;

class FilledFormHelper
{
    /**
     * Bereken de grand total van alle punten in het formulier
     */
    public static function calcGrandTotal(FilledForm $filledForm): int
    {
        return $filledForm->filledComponents
            ->pluck('gradeLevel.points')
            ->filter()
            ->sum();
    }

    /**
     * Zet de grand total om naar een eindcijfer
     */
    public static function calcGrade(int $points): ?float
    {
        $scoreMap = [
            [72, 79, 5.5],
            [80, 89, 6.0],
            [90, 97, 6.5],
            [98, 105, 7.0],
            [106, 114, 7.5],
            [115, 123, 8.0],
            [124, 131, 8.5],
            [132, 140, 9.0],
            [141, 148, 9.5],
            [149, 150, 10.0],
        ];

        foreach ($scoreMap as [$min, $max, $grade]) {
            if ($points >= $min && $points <= $max) {
                return $grade;
            }
        }

        return null;
    }

    /**
     * Bepaal de beoordeling en kleur op basis van total en zeroCount
     */
    public static function setStatus(int $zeroCount, int $total): array
    {
        // Twee of meer onvoldoendes of minder dan 11 punten is een onvoldoende
        if ($zeroCount >= 2 || $total < 11) {
            return [
                'class'  => 'bg-red-500 hover:bg-red-600',
                'status' => 'Onvoldoende',

            ];
        }

        // Bij 1 onvoldoende component kan je de competentie nog herstellen
        if ($zeroCount === 1) {
            return [
                'class'  => 'bg-yellow-500 hover:bg-yellow-600',
                'status' => 'Herstel',

            ];
        }

        // Geen onvoldoendes en totaal minder dan 16 punten (maar dus wel meer dan 11) is een voldoende!
        if ($total <= 16) {
            return [
                'class'  => 'bg-lime-500 hover:bg-lime-600',
                'status' => 'Voldoende',

            ];
        }

        // Anders ben je goed
        return [
            'class'  => 'bg-green-500 hover:bg-green-600',
            'status' => 'Goed',

        ];
    }

    /**
     * Map alle competenties van het formulier en voeg totalen en status toe
     */
    public static function mapCompetencies(FilledForm $filledForm): array
    {
        return $filledForm->form->formCompetencies->map(function ($fc) use ($filledForm) {
            $total = 0;
            $zeroCount = 0;

            $components = $fc->competency->components->map(function ($component) use ($filledForm, &$total, &$zeroCount) {
                $filled = $filledForm->filledComponents
                    ->firstWhere('component_id', $component->id);

                $points = optional($filled->gradeLevel)->points ?? 0;
                $total += $points;
                if ($points === 0) {
                    $zeroCount++;
                }

                $levels = $component
                    ->levels
                    ->map(fn($lvl) => [
                        'id' => $lvl->gradeLevel->id,
                        'name' => strtolower($lvl->gradeLevel->name),
                        'description' => $lvl->description,
                        'points' => $lvl->points,
                    ])
                    ->toArray();

                return [
                    'id' => $component->id,
                    'name' => $component->name,
                    'description' => $component->description,
                    'points' => $points,
                    'grade_level_id' => $filled->grade_level_id,
                    'comment' => $filled->comment ?? 'Geen',
                    'levels' => $levels,
                ];
            });

            $status = self::setStatus($zeroCount, $total);

            return [
                'id' => $fc->id,
                'name' => $fc->competency->name,
                'complexity' => $fc->competency->complexity,
                'rating_scale' => $fc->competency->rating_scale,
                'domain_description' => $fc->competency->domain_description,


                'components' => $components,
                'total' => $total,
                'zeroCount' => $zeroCount,

                // Kleur voor tailwind en status-tekst
                'stateClass' => $status['class'],
                'statusText' => $status['status'],
            ];
        })->toArray();
    }

    /**
     * Berekent de eindstatus en het cijfer van het formulier,
     * rekening houdend met het aantal onvoldoendes en herstellingen
     */
    public static function calcFinalGrade(array $competencies): array
    {
        // Tel competenties met status “Onvoldoende”
        $onvoldoendeCount = collect($competencies)
            ->filter(fn($comp) => $comp['statusText'] === 'Onvoldoende')
            ->count();

        if ($onvoldoendeCount > 0) {
            // Bij 1 of meer “Onvoldoende” is het eindresultaat direct “Onvoldoende” en dus maximaal een 5.0
            return [
                'grade'  => 5.0,
                'status' => 'Onvoldoende',
            ];
        }

        // Tel competenties met status “Herstel”
        $herstelCount = collect($competencies)
            ->filter(fn($comp) => $comp['statusText'] === 'Herstel')
            ->count();

        if ($herstelCount > 0) {
            // Bij 1 of meer "Herstel" mag je herstellen en het formulier krijgt de status “Herstel” met cijfer 5.0
            return [
                'grade'  => 5.0,
                'status' => 'Herstellen',
            ];
        }

        // Pas als er geen “Onvoldoende” of “Herstel” in de competenties zit, berekenen we het cijfer op basis van de totalPoints
        $totalPoints = collect($competencies)->sum('total');
        $calculatedGrade = self::calcGrade($totalPoints) ?? 5.0;

        // Als het berekende cijfer hoger dan 5,5 is, dan “Gehaald!”, anders “Onvoldoende”
        $statusText = $calculatedGrade >= 5.5
            ? 'Gehaald!'
            : 'Onvoldoende';

        return [
            'grade'  => $calculatedGrade,
            'status' => $statusText,
        ];
    }

}
