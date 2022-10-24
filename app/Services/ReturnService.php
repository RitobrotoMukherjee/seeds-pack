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
    public function returnInvoice(array $inputs): bool {
        print_r($inputs);die();
        return false;
    }
}