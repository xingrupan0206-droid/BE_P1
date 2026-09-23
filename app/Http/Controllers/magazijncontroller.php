<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class magazijncontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $producten = DB::table('Product as p')
            ->join('Magazijn as m', 'm.ProductId', '=', 'p.Id')
            ->orderBy('p.Barcode')
            ->get(['p.Id', 'p.Barcode', 'p.Naam', 'm.VerpakkingsEenheid', 'm.AantalAanwezig']);

        return view('magazijn.index', ['producten' => $producten]);
    }
}
