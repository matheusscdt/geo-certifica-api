<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class ApiController extends Controller
{
    public function index()
    {
        return $this->service()->findAll();
    }

    protected abstract function service(): ApiService;

    public function store(Request $request)
    {
        return $this->service()->store($request);
    }

    public function show($id)
    {
        return $this->service()->findById($id);
    }

    public function update(Request $request, $id)
    {
        return $this->service()->update($request, $id);
    }

    public function destroy($id): Response
    {
        return $this->service()->delete($id);
    }
}
