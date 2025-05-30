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
        $failed = $zeroCount >= 2 || $total <= 14;

        if ($failed) {
            return [
                'class' => 'bg-red-500 hover:bg-red-600',
                'status' => 'Onvoldoende',
            ];
        } elseif ($total <= 20) {
            return [
                'class' => 'bg-yellow-500 hover:bg-yellow-600',
                'status' => 'Voldoende',
            ];
        } else {
            return [
                'class' => 'bg-green-500 hover:bg-green-600',
                'status' => 'Goed',
            ];
        }
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
                // vind ingevulde data
                $filled = $filledForm->filledComponents
                    ->firstWhere('component_id', $component->id);

                // punten en counters
                $points = optional($filled->gradeLevel)->points ?? 0;
                $total += $points;
                if ($points === 0) {
                    $zeroCount++;
                }

                // map alle mogelijke levels van dit component
                $levels = $component
                    ->levels
                    ->map(fn($lvl) => [
                        'id'          => $lvl->gradeLevel->id,
                        'name'        => strtolower($lvl->gradeLevel->name),
                        'description' => $lvl->description,
                        'points'      => $lvl->points,
                    ])
                    ->toArray();

                return [
                    'id'           => $component->id,
                    'name'         => $component->name,
                    'description'  => $component->description,
                    'points'       => $points,
                    'grade_level_id' => $filled->grade_level_id,
                    'comment'      => $filled->comment ?? 'Geen',
                    'levels'       => $levels,
                ];
            });

            $status = self::setStatus($zeroCount, $total);

            return [
                'id'           => $fc->id,
                'name'         => $fc->competency->name,
                'components'   => $components,
                'total'        => $total,
                'zeroCount'    => $zeroCount,
                'stateClass'   => $status['class'],
                'statusText'   => $status['status'],
            ];
        })->toArray();
    }
}
