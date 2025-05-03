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
            'number_one' => 'required|integer|min:0|max:999',
            'number_two' => 'required|integer|min:0|max:999',
            'number_exercises' => 'required|integer|min:5|max:50'
        ]);

        dd($request);
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
