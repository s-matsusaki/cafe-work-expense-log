<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MonthlyReportController extends Controller
{
    public function index(): View
    {
        return view('reports.monthly');
    }
}