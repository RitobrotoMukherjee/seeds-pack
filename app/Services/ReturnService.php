<?php

namespace App\Services;

/**
 * Description of ReportService
 *
 * @author Ritobroto
 */

use App\Models\Billing;
use App\Models\ReturnProduct;
use App\Models\Product;

use Illuminate\Support\Facades\DB;

class ReturnService {
    
    public function viewReturn(int $bill_id): ?Billing {
        return Billing::select('id', 'invoice_number', 'net_amount')->find($bill_id);
    }
    
    public function returnInvoice(array $inputs): string {
        print_r($inputs);die();
        DB::beginTransaction();
        
        try {
            $rp = $this->createReturn($inputs);
            
        } catch (\Throwable $ex) {
            DB::rollBack();
            return $ex->getMessage();
        }
    }
    
    protected function createReturn(array $inputs): ?ReturnProduct {
        return ReturnProduct::create($inputs);
    }
    
    private function updateBilling(int $billing_id): ?Billing {
        return Billing::where
    }
}