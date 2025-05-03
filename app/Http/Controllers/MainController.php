<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {
        echo "Show exercises";
    }

    public function generateExercises(Request $request)
    {
        echo "Generate exercises";
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
