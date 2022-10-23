<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use App\Models\Billing;
use App\Models\Customer;
use App\Models\InvoiceDetail;
use App\Utils\ApplicationUtils;

/**
 * Description of BillingService
 *
 * @author Ritobroto
 */
class BillingService {
    public function getCustomerById(int $id): object {
        return $this->getCustomerPaymentDetails($id);
    }
    
    public function customerUpsert($customer) {
        return Customer::updateOrCreate(
                ['id' => $customer['id']],
                [
                    'name' => $customer['name'],
                    'city_village' => $customer['city_village'],
                ]
            );
    }
    
    public function billInsert($invoicein, $cid) {
        return Billing::Create(
                [
                    'customer_id' => $cid, 'invoice_number' => $invoicein['invoice_number'],
                    'net_amount' => $invoicein['net_amount'], 
                    'billing_date' => date('Y-m-d', strtotime($invoicein['billing_date'])), 
                    'transporter_name' => $invoicein['transporter_name'], 
                    'dispatched_date' => date('Y-m-d', strtotime($invoicein['dispatched_date']))
                ]
        );
    }
    
    public function saveInvoiceDetails($details, $in) {
        $return = false;
        $dtl = json_decode($details, true);
        foreach ($dtl as $pid => $pval){
            $save = InvoiceDetail::create(
                [
                    'billing_id' => $in, 'product_id' => $pid, 
                    'unit_price' => $pval['unit_price'], 'quantity' => $pval['quantity_need']
                ]
            );
            if(isset($save->id)){
                $update = \App\Models\Product::find($pid);
                $update->available = $update->available - $pval['quantity_need'];
                $update->save();
                $return = true;
            }
        }
        return $return;
    }
    
    public function delete(int $id): bool {
        $return = false;
        $invDtl = InvoiceDetail::where('billing_id', $id)->get();
        foreach($invDtl as $dt){
            $prod = \App\Models\Product::find($dt->product_id);
            $prod->available = $prod->available + $dt->quantity;
            $prod->save();
        }
        $return = Billing::destroy($id);
        return $return;
    }
    
    public function setInvoiceProductDetails(object $product):array{
        $productTypes = ApplicationUtils::getProductType();
        return [
            'product_name' => isset($product->product) ? $product->product->name : "Product Deleted", 
            'product_type' => isset($product->product) ? $productTypes[$product->product->type] : "",
            'product_quantity' => $product->quantity, 'unit_price' => $product->unit_price
        ];
    }
    
    public function getBillById($id) {
        $billing = Billing::with(['customer', 'invoice_detail.product', 'customer.latest_payment'])->where('id', $id)->first();
        
        $customer = $this->getCustomerPaymentDetails($billing->customer->id);
        
        $billing->customer->outstanding_amount = $customer->outstanding_amount;
        $billing->customer->last_paid_amount = isset($customer->last_paid_amount) ? $customer->last_paid_amount : 0;
        $billing->customer->last_paid_date = isset($customer->last_paid_date) ? $customer->last_paid_date : null;
        
        return $billing;
    }
    
    private function getCustomerPaymentDetails($c_id){
        $billing_amount = $paid_amount = 0;
        $customer = Customer::with(['payment', 'billing', 'latest_payment'])->findOrFail($c_id);
        if(isset($customer->billing)) { 
            $billing_amount = $customer->billing->sum('net_amount');
        }
        if(isset($customer->payment)) { 
            $paid_amount = $customer->payment->sum('payment_amount');
        }
        $customer->outstanding_amount = $billing_amount - $paid_amount;
        if(isset($customer->latest_payment)){
            $customer->last_paid_date = date('Y-m-d', strtotime($customer->latest_payment->payment_date));
            $customer->last_paid_amount = $customer->latest_payment->payment_amount;
        }
        
        return $customer;
    }
    
    
}
