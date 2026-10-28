<?php

namespace App\Http\Controllers;

use App\Providers\magazijn\index\Index;
use Illuminate\View\View;

class MagazijnController extends Controller
{
    public function __construct(private Index $provider) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('magazijn.index', $this->provider->paginaData());
    }
}
