<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Services;

use App\Models\Product;
use App\Models\ProductpurchaseDetail;

/**
 * Description of ProductService
 *
 * @author Ritobroto
 */
class ProductService {
    
    public function getProductById($id) {
        return Product::findOrFail($id);
    }
    public function upsertProduct($product, $purchase_details){
        $purchase = null;
        $prod = Product::updateOrCreate(
                ['id' => $product['id']],
                [
                    'name' => $product['name'],'type' => $product['type'],
                    'sale_price_per_kg' => $product['sale_price_per_kg']
                ]
            );
        if(isset($prod->id)){
            $purchase_details['product_id'] = $prod->id;
            $purchase = ProductpurchaseDetail::create($purchase_details);
        }
        if(isset($purchase)){
            $prod = Product::find($prod->id);
            $prod->available += $purchase->purchase_quantity;
            $prod->save();
        }
        return $purchase;
    }
    
}
