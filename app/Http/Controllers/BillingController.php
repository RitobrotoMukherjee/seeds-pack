<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BillingService;
use App\Utils\ApplicationUtils;
use App\Models\Billing;
use PDF;

class BillingController extends Controller
{
    protected $service;
    public function __construct() {
        parent::__construct();
        
        $this->service = new BillingService();
        
        $this->data['product_type'] = ApplicationUtils::getProductType();
        $this->data['products'] = ApplicationUtils::getProducts();
        $this->data['customers'] = ApplicationUtils::getCustomers();
    }
    
    public function generateBill() {
        session()->forget(['products']);
        $this->data['today'] = $this->today;
        return view('billing.generate', $this->data);
    }
    
    public function upsertBill(Request $request){
        $customerin = $request->input('customer');
        $bill = $request->input('bill');
        $products = $request->input('product');
        $customer = $this->service->customerUpsert($customerin);
        if(isset($customer->id)){
            $invoice = $this->service->billInsert($bill, $customer->id);
            if(isset($invoice->id)){
                $this->service->saveInvoiceDetails($products['details'], $invoice->id);
                return redirect()->route('bill.print', ['id' => $invoice->id]);
            }
        }
        return redirect()->route('billing.list')->with('message', 'Bill not generated. COntact Technical team.');
    }
    
    public function delete(int $id){
        (bool)$return = $this->service->delete($id);
        if($return){
            return redirect()->route('billing.list')->with('message', 'Bill Deleted Successfully');
        }
        return redirect()->route('billing.list')->with('error', 'Bill Not Deleted Properly!');
    }
    
    public function printBill($id){
        $data = $this->get_invoice_data($id);
    //    dd($data);
        $pdf = PDF::loadView('billing.orderPDF', $data);
    
        return $pdf->stream($data['invoice']['invoice_number'].'.pdf', ["Attachment" => false]);
    }
    protected function get_invoice_data($id) {
        $invoice = $this->service->getBillById($id);
//        dd($invoice->toArray());
        $data['invoice'] = [
            'date' => date('d-m-Y H:i', strtotime($invoice->created_at)),
            'invoice_number' => $invoice->invoice_number,
            'hamali_charges' => $invoice->hamali_charges,
            'net_amount' => $invoice->net_amount,
            'transporter_name' => ucwords($invoice->transporter_name),
            'billing_date' => date('d-m-Y', strtotime($invoice->billing_date)),
            'dispatched_date' => date('d-m-Y', strtotime($invoice->dispatched_date)),
            'customer_name' => ucwords($invoice->customer->name),
            'customer_address' => ucwords($invoice->customer->city_village),
            'customer_outstanding_amount' => $invoice->customer->outstanding_amount,
            'customer_last_paid_amount' => $invoice->customer->last_paid_amount,
            'customer_last_payment_date' => $invoice->customer->last_paid_date,
        ];
        foreach($invoice->invoice_detail as $dtl){
            $data['invoice']['products'][$dtl->product_id] = $this->service->setInvoiceProductDetails($dtl);
        }
        return $data;
    }
    
    public function billingList() {
        return view('billing.list', $this->data);
    }
    public function serverList(Request $request): string {
        $result = [];
        $totalData = Billing::count();
        $totalFiltered = $totalData;
        
        $limit = $request->input('length');
        $start = $request->input('start');
        
        $qry = Billing::with(['customer']);
        if(!empty($request->input('search.value'))){ 
            $search = $request->input('search.value'); 
            
            $qry->where('lr_no','LIKE',"%{$search}%")->orWhere('invoice_number','LIKE',"%{$search}%");
            
            $totalFiltered = Billing::where('lr_no','LIKE',"%{$search}%")
                    ->orWhere('invoice_number','LIKE',"%{$search}%")->count();
        }
        $billings = $qry->offset($start)->limit($limit)->orderBy('id','DESC')->get();
        if(!empty($billings)){
            $result = $this->getPaginationData($billings);
        }
        $json_data = $this->getReturnData($request, $totalData, $totalFiltered, $result);
        return json_encode($json_data); 
    }
    
    private function getPaginationData(object $billings): array {
        $result = [];
        foreach ($billings as $billing)
            {
                $view = route('bill.print',$billing->id);
                $delete = route('bill.delete',$billing->id);

                $nestedData['invoice_number'] = $billing->invoice_number;
                $nestedData['customer_name'] = $billing->customer->name;
                $nestedData['hamali_charges'] = $billing->hamali_charges;
                $nestedData['net_amount'] = $billing->net_amount;
                $nestedData['transporter'] = $billing->transporter_name;
                $nestedData['billing_date'] = date('d-m-Y',  strtotime($billing->billing_date));
                $nestedData['dispatch_date'] = date('d-m-Y',  strtotime($billing->dispatched_date));
                $nestedData['options'] = "<a class='btn btn-xs btn-default text-primary mx-1 shadow' title='Print Bill' href='{$view}' target='_blank'><i class='fa fa-lg fa-fw fa-eye'></i></a>"
                . "<a class='btn btn-xs btn-default text-danger mx-1 shadow' title='Delete' href='{$delete}'><i class='fa fa-lg fa-fw fa-trash'></i></a>";
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
