<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function __invoke(): View
    {
        return view('Admin.Reportes.ListasdeReportes');
    }
}
