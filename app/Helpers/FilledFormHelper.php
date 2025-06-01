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
            [72, 77, 5.5],
            [78, 83, 6],
            [84, 89, 6.5],
            [90, 95, 7],
            [96, 100, 7.5],
            [101, 106, 8],
            [107, 112, 8.5],
            [113, 118, 9],
            [119, 124, 9.5],
            [125, 125, 10],
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
                'failed' => true,
            ];
        }

        // Bij een onvoldoende component kan je de competentie nog herstellen
        if ($zeroCount === 1) {
            return [
                'class'  => 'bg-yellow-500 hover:bg-yellow-600',
                'status' => 'Herstel',
                'failed' => false,
            ];
        }

        // Geen onvoldoendes en totaal minder dan 16 punten is een voldoende!
        if ($total <= 16) {
            return [
                'class'  => 'bg-lime-500 hover:bg-lime-600',
                'status' => 'Voldoende',
                'failed' => false,
            ];
        }

        // Anders ben je goed
        return [
            'class'  => 'bg-green-500 hover:bg-green-600',
            'status' => 'Goed',
            'failed' => false,
        ];
    }

//    public static function setStatus(int $zeroCount, int $total): array
//    {
//        // Bepaal of een competentie "failed" is: of er zijn meer dan 2 nullen, of het totaal punten is minder dan 11.
//        $failed = $zeroCount >= 2 || $total <= 10;
//
//        if ($failed) {
//            return [
//                'class' => 'bg-red-500 hover:bg-red-600',
//                'status' => 'Onvoldoende',
//                'failed' => true,
//            ];
//        } elseif ($total <= 16) {
//            return [
//                'class' => 'bg-yellow-500 hover:bg-yellow-600',
//                'status' => 'Voldoende',
//                'failed' => false,
//            ];
//        } else {
//            return [
//                'class' => 'bg-green-500 hover:bg-green-600',
//                'status' => 'Goed',
//                'failed' => false,
//            ];
//        }
//    }

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

                // Hij pakt dit van eerder terug
                'failed'     => $status['failed'],
            ];
        })->toArray();
    }

    /**
     * Berekent de eindstatus en het cijfer van het formulier,
     * rekening houdend met het aantal failed competenties
     */
    public static function calcFinalGrade(array $competencies): array
    {
        // Kijk hoeveel competencies failed zijn
        $failedCount = collect($competencies)->filter(fn($comp) => $comp['failed'])->count();

        // Als 2 of meer competenties failed zijn, altijd onvoldoende en 5.0
        if ($failedCount >= 2) {
            return [
                'grade'  => 5.0,
                'status' => 'Onvoldoende',
            ];
        }

        // Anders totaalpunten optellen en cijfer berekenen
        $totalPoints = collect($competencies)->sum('total');
        $calculatedGrade = self::calcGrade($totalPoints) ?? 5.0;

        // als cijfer hoger is dan 5.5, noem het ‘Gehaald!’, anders ‘Onvoldoende’
        $statusText = $calculatedGrade >= 5.5
            ? 'Gehaald!'
            : 'Onvoldoende';

        return [
            'grade'  => $calculatedGrade,
            'status' => $statusText,
        ];
    }

//    public static function calcFinalGrade(array $competencies): float
//    {
//        $failedCount = collect($competencies)->filter(fn($comp) => $comp['failed'])->count();
//
//        // Als 2 of meer competenties failed zijn, altijd onvoldoende en 5.0
//        if ($failedCount >= 2) {
//            return 5.0;
//        }
//
//        // Anders totaalpunten optellen
//        $totalPoints = collect($competencies)->sum('total');
//
//        // Bereken het cijfer op basis van de punten, fallback naar 5.0
//        return self::calcGrade($totalPoints) ?? 5.0;
//    }
}
