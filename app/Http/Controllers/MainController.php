<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {
       return view('home');
    }

    public function generateExercises(Request $request)
    {
        $request->validate([
            'check_sum' => 'required_without_all:check_subtration,check_multiplication,check_division',
            'check_subtration' => 'required_without_all:check_multiplication,check_division,check_sum',
            'check_multiplication' => 'required_without_all:check_division,check_sum,check_subtration',
            'check_division' => 'required_without_all:check_sum,check_multiplication,check_subtration',
            'number_one' => 'required|integer|min:0|max:999|lt:number_two',
            'number_two' => 'required|integer|min:0|max:999',
            'number_exercises' => 'required|integer|min:5|max:50'
        ]);

        //get selected operations
        $operations = [];

        if ($request->check_sum) {$operations[] = 'sum';}
        if ($request->check_subtration) {$operations[] = 'subtration';}
        if ($request->check_multiplication) {$operations[] = 'multiplication';}
        if ($request->check_division) {$operations[] = 'division';}

        //get numbers min and max
        $min = $request->number_one;
        $max = $request->number_two;

        //get number exercises
        $numberExercises = $request->number_exercises;

        //generate exercises
        $exercises = [];
        for ($i=1; $i <= $numberExercises ; $i++) {

            $operation = $operations[array_rand($operations)];
            $number1 = rand($min, $max);
            $number2 = rand($min, $max);

            $exercise = '';
            $sollution = '';

            switch ($operation) {
                case 'sum':
                    $exercise = "$number1 + $number2 =";
                    $sollution = $number1 + $number2;
                    break;

                case 'subtration':
                    $exercise = "$number1 - $number2 =";
                    $sollution = $number1 - $number2;
                    break;

                case 'multiplication':
                    $exercise = "$number1 * $number2 =";
                    $sollution = $number1 * $number2;
                    break;

                case 'division':

                    if ($number2 == 0) {
                        $number2 = 1;
                    }
                    $exercise = "$number1 / $number2 =";
                    $sollution = $number1 / $number2;
                    break;
            }

            if (is_float($sollution)) {
                $sollution = round($sollution, 2);
            }

            $exercises[] = [
                'operation' => $operation,
                'number_exercise' => $i,
                'exercise' => $exercise,
                'sollution' => "$exercise $sollution"
            ];

        }

        dd($exercises);
    }

    public function printExercises()
    {
        echo "Print exercises";
    }

    public function exportExercises()
    {
        echo "export exercises";
    }
}
