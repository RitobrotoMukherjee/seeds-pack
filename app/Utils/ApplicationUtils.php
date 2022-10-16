<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils;

/**
 * Description of newPHPClass
 *
 * @author Ritobroto
 */
class ApplicationUtils {
    public static function getProductType(): array{
        return [
            1 => 'Powder',
            2 => 'Solid'
        ];
    }
    
    public static function getProducts(): object {
        return \App\Models\Product::get();
    }
    public static function getCustomers(): object {
        return \App\Models\Customer::with(['payment', 'billing'])->get();
    }
    
    public static function getProductById($id): object {
        return \App\Models\Product::find($id);
    }
}
