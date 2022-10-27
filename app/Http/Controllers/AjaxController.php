<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\BillingService;
use App\Utils\ApplicationUtils;

class AjaxController extends Controller
{
    protected $productService, $billingService;
    public function __construct() {
        parent::__construct();
        $this->productService = new ProductService();
        $this->billingService = new BillingService();
    }
    
    public function getProductById(Request $req){
        $input = $req->input('product_id');
        return $this->productService->getProductById($input);
    }
    
    public function getCustomerById(Request $req) {
        $input = $req->input('customer_id');
        $c_id = isset($input) ? $input : 0;
        return $this->billingService->getCustomerById($c_id);
    }
    
    public function productCart(Request $req){
        $cart = session()->get('products');
        $product = $req->input('product');
        
        
        $prod_details = ApplicationUtils::getProductById($product['product_id']);
        
        if(isset($cart['products'][$prod_details->id])){
            $cart['products'][$prod_details->id]['quantity'] += $product['quantity'];
        }
        if(!isset($cart['products'][$prod_details->id])){
            $cart['products'][$prod_details->id] = [
                'product_name' => $prod_details->name,
                'available' => $prod_details->available,
                'quantity_need' => $product['quantity'],
                'unit_price' => $prod_details->sale_price_per_kg
            ];
        }
        $cart['net_amount'] = $prod_details->sale_price_per_kg * $product['quantity'];
        session()->put('products', $cart);
        return $cart;
    }
}
