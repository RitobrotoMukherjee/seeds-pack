<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Utils\ApplicationUtils;
use App\Services\ProductService;
use Validator;

class ProductController extends Controller
{
    protected $service;
    public function __construct() {
        parent::__construct();
        
        $this->data['product_type'] = ApplicationUtils::getProductType();
        $this->service = new ProductService();
    }
    public function deleteProduct($id) {
        Product::destroy($id);
        
        return redirect()->route('product.list')->with('message', 'Product Successfully Deleted');
    }
    
    public function productAdd() {
        $this->data['products'] = ApplicationUtils::getProducts();
        return view('product.add', $this->data);
    }
    
    public function upsertProduct(Request $request){
        $product = $request->input('product');
        $purchase_details = $request->input('purchase_details');
        $validator = $this->validator($product, $purchase_details);
        if ($validator->passes()) {
            $result = $this->service->upsertProduct($product, $purchase_details);
            if(isset($result->product_id)){
                return redirect()->route('product.detail', ['id' => $result->product_id]);
            }
            return redirect()->route('product.list');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    public function productDetail(int $id){
        $this->data['product'] = Product::with(['productpurchase_detail'])->where('id', $id)->first();
        
        return view('product.detail', $this->data);
    }
    
    public function productList() {
        return view('product.list', $this->data);
    }
    public function serverList(Request $request): string {
        $result = [];
        $totalData = Product::count();
        $totalFiltered = $totalData;
        
        $limit = $request->input('length');
        $start = $request->input('start');
        
        $qry = Product::query();
        if(!empty($request->input('search.value'))){ 
            $search = $request->input('search.value'); 
            
            $qry->where('name','LIKE',"%{$search}%");
            
            $totalFiltered = Product::where('name','LIKE',"%{$search}%")->count();
        }
        $products = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        if(!empty($products)){
            $result = $this->getPaginationData($products);
        }
        $json_data = $this->getReturnData($request, $totalData, $totalFiltered, $result);
        return json_encode($json_data); 
    }
    
    protected function validator(array $product, array $purchase_detail)
    {
        $data = array_merge($product, $purchase_detail);
//        dd($data);
        $validator = [
            'name' => 'required|string','type' => 'required|integer',
            'sale_price_per_kg' => 'required|numeric|min:0', 'purchase_from' => 'required|string', 
            'purchase_quantity' => 'required|numeric|min:0', 'purchase_date' => 'required|date',
            'purchase_price_per_kg' => 'required|numeric|min:0'
        ];
        
        return Validator::make($data, $validator);
    }
    
    private function getPaginationData(object $products): array {
        $result = [];
        foreach ($products as $product)
            {
                $view = route('product.detail',$product->id);
                $delete = route('product.delete',$product->id);

                $nestedData['name'] = ucwords($product->name);
                $nestedData['product_type'] = $this->data['product_type'][$product->type];
                $nestedData['available'] = $product->available." Kgs";
                $nestedData['sale_price_per_kg'] = $product->sale_price_per_kg;
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='View/Edit' href='{$view}' target='_blank'><i class='fa fa-lg fa-fw fa-eye'></i></a>"
                . "<a class='btn btn-xs btn-default text-danger mx-1 shadow' title='Delete' href='{$delete}' target='_blank'><i class='fa fa-lg fa-fw fa-trash'></i></a>";
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
