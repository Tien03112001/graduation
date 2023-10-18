<?php

/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 6/19/2019
 * Time: 11:44 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

interface RestNestedApiController
{
    public function index($parentId, Request $request);

    public function store($parentId, Request $request);

    public function show($parentId, $id);

    public function update($parentId, $id, Request $request);

    public function destroy($parentId, $id);
}