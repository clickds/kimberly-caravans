<?php

namespace App\Http\Controllers;

use App\Repositories\CaravanRepository;

class TestController extends Controller
{
    public function index(CaravanRepository $r) {

        $d = $r->findAll(['kimberley_date_updated'=>'DESC']);
        echo '<pre>';die(print_r($d));
    }
}