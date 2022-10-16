<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Utils\ApplicationUtils;

class CustomerController extends Controller
{
    protected $service;
    public function __construct() {
        parent::__construct();
        $this->data['customers'] = ApplicationUtils::getCustomers();
    }
    
    public function customerList() {
        return view('customer.list', $this->data);
    }
    
    public function serverList(Request $request): string {
        $result = [];
        $totalData = Customer::count();
        $totalFiltered = $totalData;
        
        $limit = $request->input('length');
        $start = $request->input('start');
        
        $qry = Customer::with(['payment', 'billing', 'latest_payment']);
        if(!empty($request->input('search.value'))){ 
            $search = strtolower($request->input('search.value')); 
            
            $qry->whereRaw("LOWER(name) LIKE '%{$search}%'");
            $totalFiltered = $qry->count();
            
        }
        $customers = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        
        if(!empty($customers)){
            $result = $this->getPaginationData($customers);
        }
        $json_data = $this->getReturnData($request, $totalData, $totalFiltered, $result);
        return json_encode($json_data); 
    }
    
    private function getPaginationData(object $customers): array {
        $result = [];
        foreach ($customers as $customer){
            $billing_amount = $paid_amount = 0;
            if(isset($customer->billing)) { 
                $billing_amount = $customer->billing->sum('net_amount');
            }
            if(isset($customer->payment)) { 
                $paid_amount = $customer->payment->sum('payment_amount');
            }
            $nestedData['name'] = ucwords($customer->name);
            $nestedData['address'] = $customer->city_village.", ".$customer->pincode;
            $nestedData['outstanding_amount'] = $billing_amount - $paid_amount;
            $result[] = $nestedData;
        }
        return $result;
    }
    private function getReturnData(object $request, int $totalData, int $totalFiltered, array $result): array{
        return [
                "draw"            => intval($request->input('draw')),  
                "recordsTotal"    => intval($totalData),  
                "recordsFiltered" => intval($totalFiltered), 
                "data"            => $result   
            ];
    }
}
