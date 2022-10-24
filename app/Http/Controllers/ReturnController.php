<?php

namespace App\Http\Controllers;

use App\Services\ReturnService;
use App\Http\Requests\ReturnRequest;

class ReturnController extends Controller
{
    private ReturnService $service;
    
    public function __construct()
    {
        parent::__construct();
        $this->service = new ReturnService();   
    }

    public function returnInvoice(ReturnRequest $req) {
        $inputs = $req->validated();
        
        $this->service->returnInvoice($inputs);
    }

}
