<?php

namespace App\Helpers;

use App\Models\FilledForm;
use Illuminate\Support\Collection;

class FilledFormHelper
{
    public static function mapCompetencies(FilledForm $filledForm): array
    {
        $numComponents = 0;
        $numCompetencies = $filledForm->form->formCompetencies->count();

        $mapped = $filledForm->form->formCompetencies->map(function ($fc) use ($filledForm, &$numComponents, $numCompetencies) {
            $total = 0;
            $zeroCount = 0;
            $competencyComponentsCount = $fc->competency->components->count();
            $maxPoints = $competencyComponentsCount * 5;
            $minPoints = max($competencyComponentsCount - 1, 1) * 3;

            // Ik voeg later comments toe ik ben moe
            $components = $fc->competency->components->map(function ($component) use ($filledForm, &$total, &$zeroCount, &$numComponents) {
                $numComponents++; // globaal totaal componenten tellen

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

            $status = self::setStatus($zeroCount, $total, $competencyComponentsCount, 1);

            return [
                'id' => $fc->id,
                'name' => $fc->competency->name,
                'complexity' => $fc->competency->complexity,
                'rating_scale' => $fc->competency->rating_scale,
                'domain_description' => $fc->competency->domain_description,
                'components' => $components,
                'total' => $total,
                'zeroCount' => $zeroCount,
                'stateClass' => $status['class'],
                'statusText' => $status['status'],
                'maxPoints' => $maxPoints,
                'minPoints' => $minPoints,
            ];
        })->toArray();

        return [
            'competencies' => $mapped,
            'numComponents' => $numComponents,
            'numCompetencies' => $numCompetencies,
        ];
    }

    public static function calcGrade(int $points, int $numComponents, int $numCompetencies): array
    {
        $mid = max($numComponents - $numCompetencies, 1) * 3;
        $max = $numComponents * 5;
        $grade = $points <= $mid
            ? 1 + ($points / $mid) * 4.5
            : 5.5 + (($points - $mid) / ($max - $mid)) * 4.5;

        return [
            'grade' => round($grade, 1),
            'mid' => $mid,
            'max' => $max,
        ];
    }


    public static function calcFinalGrade(array $competencies, int $numComponents, int $numCompetencies): array
    {
        $onvoldoendeCount = collect($competencies)
            ->where('statusText', 'Onvoldoende')
            ->count();

        // 1 onvoldoende competentie is al genoeg om te falen
        if ($onvoldoendeCount >= 1) {
            return ['grade' => 5.0, 'status' => 'Onvoldoende'];
        }

        $herstelCount = collect($competencies)
            ->where('statusText', 'Herstel')
            ->count();

        // Als er meerdere competenties zijn met herstel, dan ook geen 'gehaald' yo
        if ($herstelCount >= 1) {
            return ['grade' => 5.0, 'status' => 'Herstellen'];
        }

        // Alles is voldoende
        $totalPoints = collect($competencies)->sum('total');

        $calculatedGrade = self::calcGrade($totalPoints, $numComponents, $numCompetencies);

        $statusText = $calculatedGrade['grade'] >= 5.5 ? 'Gehaald!' : 'Onvoldoende';

        return [
            'grade' => $calculatedGrade['grade'],
            'status' => $statusText,
        ];
    }

    public static function setStatus(int $zeroCount, int $total, int $numComponents, int $numCompetencies): array
    {
        $allowedZeros = max($numCompetencies, 1);
        $neededComponents = max($numComponents - $numCompetencies, 1);
        $mid = $neededComponents * 3; // grens voor 5.5

        if ($zeroCount > $allowedZeros + 1) {
            return ['class' => 'bg-red-500', 'status' => 'Onvoldoende'];
        }

        if ($zeroCount === $allowedZeros + 1) {
            return ['class' => 'bg-yellow-500', 'status' => 'Herstel'];
        }

        if ($zeroCount <= $allowedZeros) {
            if ($total >= $mid + 6) {
                // Bonuspunten voor ‘Goed’
                return ['class' => 'bg-green-500', 'status' => 'Goed'];
            }

            if ($total >= $mid) {
                return ['class' => 'bg-lime-500', 'status' => 'Voldoende'];
            }
        }

        // Alles daartussen is ‘Herstel’
        return ['class' => 'bg-yellow-500', 'status' => 'Herstel'];
    }



    public static function calcGrandTotal(FilledForm $filledForm): int
    {
        return $filledForm->filledComponents
            ->pluck('gradeLevel.points')
            ->filter()
            ->sum();
    }
}
