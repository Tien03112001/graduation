<?php

/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/19/2019
 * Time: 11:44 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

interface ApiController
{
    public function index(Request $request);

    public function store(Request $request);

    public function show($id);

    public function update(Request $request, $id);

    public function destroy($id);
}