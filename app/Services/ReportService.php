<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use App\Models\Billing;
use App\Models\Customer;

/**
 * Description of ReportService
 *
 * @author Ritobroto
 */
class ReportService {
    
    private $from, $to;
    
    public function __construct() {
        $this->to = date('Y-m-d');
        $this->from = date('Y-m-d',strtotime("-1 days"));
    }
    
    public function getdata(array $input): array {
        $return = [];
        $this->to = date('Y-m-d', strtotime($input['to_date']));
        $this->from = date('Y-m-d', strtotime($input['from_date']));
        $return['billing_data'] = $this->getBillingdata($input['customer_id']);
        $return['payment_data'] = $this->getCustomerPayment($input['customer_id']);
//        dd($return);
        return $return;
    }
    
    private function getBillingdata($id){
        $result = Billing::with(['customer', 'invoice_detail.product'])
                ->whereBetween('billing_date', [$this->from." 00:00:00", $this->to." 23:59:59"])
                ->orderBy('id', 'desc')->where('customer_id', $id)->get();
        $configure = [];
        foreach($result as $data){
            foreach($data->invoice_detail as $details){
                $configure[] = [
                    date('d-m-Y', strtotime($data->billing_date)), isset($details->product) ? $details->product->name : "",
                    $details->quantity, $details->unit_price, ($details->unit_price * $details->quantity)
                ];
            }
            
        }
        return $configure;
    }
    
    private function getCustomerPayment($id){
        $customer = Customer::with(['billing', 'payment', 'latest_payment'])->find($id);
        $result = ['customer' => null,'billing_amount' => 0, 'paid_amount' => 0, 'last_paid_at' => null, 'last_paid' => 0];
        
        $result['customer']['name'] = $customer->name;
        $result['customer']['address'] = $customer->city_village;
        if(isset($customer->billing)) { 
            $result['billing_amount'] = $customer->billing->sum('net_amount');
        }
        if(isset($customer->payment)) { 
            $result['paid_amount'] = $customer->payment->sum('payment_amount');
        }
        if(isset($customer->latest_payment)){
            $result['last_paid_at'] = date('d-m-Y', strtotime($customer->latest_payment->payment_date));
            $result['last_paid'] = $customer->latest_payment->payment_amount;
        }
        return $result;
    }
}
