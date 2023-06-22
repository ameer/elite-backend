<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function zones()
    {
        return Zone::all();
    }
}
