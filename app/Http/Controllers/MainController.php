<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {
       return view('home');
    }

    public function generateExercises(Request $request):View
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

           $exercises[] = $this->generateExercise($i, $operations, $min, $max);

        }

        session(['exercises' => $exercises]);

        return view('operations', ['exercises' => $exercises]);
    }

    public function printExercises()
    {
        if (!session()->has('exercises')) {
            return redirect()->route('home');
        }

        $exercises = session('exercises');

        echo '<pre>';
        echo '<h1>Exercícios de Matemática ('. env('APP_NAME') .')</h1>';
        echo '<hr>';

        //dd($exercises);

        foreach ($exercises as $exercise) {
            echo '<div><h2><small>'.$exercise['number_exercise'].'.  </small> '.$exercise['exercise'].'</h2></div>';
        }

        echo '<pre>';
        echo '<hr>';
        echo '<h2>Solução dos exercícios</h2>';

        foreach ($exercises as $exercise) {
            echo '<div><h4><small>'.$exercise['number_exercise'].'.  </small> '.$exercise['sollution'].'</h4></div>';
        }

    }

    public function exportExercises()
    {
        if (!session()->has('exercises')) {
            return redirect()->route('home');
        }

        $exercises = session('exercises');
        $filename = 'execises_'.env('APP_NAME').'_'.date('YmdHis').'.txt';
        $content = '';
        foreach ($exercises as $exercise) {
            $content .= $exercise['number_exercise'] . '.  ' . $exercise['exercise'] . "\n";
        }

        $content .= "\n";
        $content .= "Soluções\n" . str_repeat('-', 20) . "\n";
        foreach ($exercises as $exercise) {
            $content .= $exercise['number_exercise'] . '.  ' . $exercise['sollution'] . "\n";
        }

        return response($content)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function generateExercise($i, $operations, $min, $max): array
    {
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

        return [
            'operation' => $operation,
            'number_exercise' => str_pad($i, 2, '0', STR_PAD_LEFT),
            'exercise' => $exercise,
            'sollution' => "$exercise $sollution"
        ];
    }
}
