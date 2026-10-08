<?php

namespace App\Http\Controllers;

use App\Models\Magazijn;
use Illuminate\View\View;

class MagazijnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('magazijn.index', ['producten' => Magazijn::overzicht()]);
    }
}
