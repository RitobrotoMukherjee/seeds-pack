<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Utils\ApplicationUtils;
use App\Models\Payment;
use Validator;

class PaymentController extends Controller
{
    protected $service;
    public function __construct() {
        parent::__construct();
        
        $this->data['customers'] = ApplicationUtils::getCustomers();
    }
    
    public function paymentAdd() {
        return view('payment.add', $this->data);
    }
    
    public function upsertPayment(Request $request){
        $payment = $request->input('payment');
        $validator = $this->validator($payment);
        if ($validator->passes()) {
            $result = $this->insertPayment($payment);
            if(isset($result->id)){
                return redirect()->route('payment.list');
            }
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    public function paymentList() {
        return view('payment.list', $this->data);
    }
    public function serverList(Request $request): string {
        $result = [];
        $totalData = Payment::count();
        $totalFiltered = $totalData;
        
        $limit = $request->input('length');
        $start = $request->input('start');
        
        $qry = Payment::with(['customer']);
        if(!empty($request->input('search.value'))){ 
            $search = strtolower($request->input('search.value')); 
            
            $qry->whereHas('customer', function(Builder $query) use ($search){
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });
            $totalFiltered = $qry->count();
            
        }
        $payments = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        
        if(!empty($payments)){
            $result = $this->getPaginationData($payments);
        }
        $json_data = $this->getReturnData($request, $totalData, $totalFiltered, $result);
        return json_encode($json_data); 
    }
    
    protected function validator(array $payment)
    {
        $validator = [
            'customer_id' => 'required|integer',
            'payment_amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|string|max:50',
            'payment_date' => 'required|date'
        ];
        
        $messages = [
            'customer_id.required' => 'Select Cutomer', 'customer_id.integer' => 'Select Customer from given list',
            'payment_amount.numeric' => 'Payment Amount must be numeric', 'payment_amount.min' => 'Payment cannot be in negative',
            
        ];
        
        return Validator::make($payment, $validator, $messages);
    }
    
    private function getPaginationData(object $payments): array {
        $result = [];
        foreach ($payments as $payment)
            {

                $nestedData['customer_name'] = ucwords($payment->customer->name);
                $nestedData['payment_amount'] = $payment->payment_amount;
                $nestedData['payment_mode'] = $payment->payment_mode;
                $nestedData['payment_date'] = date('d-m-Y', strtotime($payment->payment_date));
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
    
    private function insertPayment(array $payment): object {
        return Payment::create($payment);
    }
}
