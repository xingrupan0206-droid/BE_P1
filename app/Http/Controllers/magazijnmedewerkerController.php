<?php

namespace App\Http\Controllers;

class MagazijnmedewerkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('magazijnmedewerker.index', [
            'title' => 'Magazijnmedewerker Page'
        ]);
    }

}
