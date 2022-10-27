<?php

namespace App\Http\Controllers;

use App\Services\ReturnService;
use App\Http\Requests\ReturnRequest;

class ReturnController extends Controller
{
    private ReturnService $service;
    
    public function __construct(ReturnService $sr)
    {
        parent::__construct();
        $this->service = $sr;
    }
    
    public function viewReturn(int $billing_id) {
        $this->data['today'] = $this->today;
        $this->data['billing_details'] = $this->service->viewReturn($billing_id);
        return view('return.generate', $this->data);
    }

    public function returnInvoice(ReturnRequest $req) {
        $inputs = $req->validated();
        
        $this->service->returnInvoice($inputs);
    }

}