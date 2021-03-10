<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\AuthenticationException;

class ApiController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function allowedAdminAction(){
        if(Gate::denies('admin-action')){
            throw new AuthenticationException('Esta acción no es permitida');
        }
    }
}
