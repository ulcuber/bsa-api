<?php

namespace App\Http\Controllers\Api;

use App\Models\Alg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AlgResource;

class AlgController extends Controller
{
    public function count()
    {
        return Alg::count();
    }
}
