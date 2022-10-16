<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\ApplicationUtils;
use App\Services\ReportService;
use Validator;

class ReportController extends Controller
{
    protected $service;
    public function __construct() {
        parent::__construct();
        $this->data['customers'] = ApplicationUtils::getCustomers();
        $this->service = new ReportService();
    }
    public function reportView(){
        return view('customer.report', $this->data);
    }
    public function customerReport(Request $req) {
        $inputs = $req->input('filters');
        $validator = $this->validator($inputs);
        if ($validator->passes()) {
            $this->data['details'] = $this->service->getdata($inputs);
            
//            dd($this->data['details']);
            return view('customer.report', $this->data);
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    protected function validator(array $data){
        $validator = [
            'from_date' => 'required|date', 'to_date' => 'required|date|after:from_date', 'customer_id' => 'required|numeric'
        ];
        return Validator::make($data, $validator);
    }
}
