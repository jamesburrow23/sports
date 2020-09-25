<?php

namespace App\Http\Controllers;

use App\Queries\Teams as Query;

class TeamsController extends Controller
{
    public function index(Query $query)
    {
        return view('teams.index', $query->index());
    }
}
