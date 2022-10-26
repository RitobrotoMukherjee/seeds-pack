<?php

namespace App\Services;

/**
 * Description of ReportService
 *
 * @author Ritobroto
 */

use App\Models\Billing;
use App\Models\Product;

class ReturnService {
    
    public function viewReturn(int $bill_id): ?Billing {
        return Billing::select('id', 'invoice_number', 'net_amount')->find($bill_id);
    }
    
    public function returnInvoice(array $inputs): bool {
        print_r($inputs);die();
        return false;
    }
}