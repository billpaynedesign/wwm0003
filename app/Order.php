<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\State;
use Log;
use QBInvoice;

class Order extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders';

	public function details(){
		return $this->hasMany('App\OrderDetail');
	}
	public function transaction(){
		return $this->belongsTo('App\Transaction');
	}
	public function user(){
		return $this->belongsTo('App\User');
	}
	public function getInvoiceNumAttribute(){
		return sprintf("%07d", $this->id);
	}
	public function getShipStatusAttribute(){
      if($this->details->count() == $this->details()->where('shipped','=',1)->count()) {
        $shipstatus = 'Shipped';
      }
      elseif(($this->details->count()>$this->details()->where('shipped','!=',1)->count()) && ($this->details()->where('shipped',1)->count() != 0)){
        $shipstatus = 'Back Ordered/Partial Shipped';
      }
      else{
        $shipstatus = 'Not Shipped';
      }
      return $shipstatus;
	}
	public function getTotalWithTaxAttribute(){
		// $state = State::where('abbr',$this->state)->first();
		// $tax = ((float)$state->tax)/100;
		$tax = (float)$this->user->tax;
		return ((float)$this->total*$tax)+(float)$this->total;
	}
	public function getTaxAttribute(){
		// $state = State::where('abbr',$this->state)->first();
		// $tax = ((float)$state->tax)/100;
		$tax = (float)$this->user->tax;
		return (floatval($this->total)*$tax);
	}
	public function getInvoiceArrayAttribute(){
		$ds = DIRECTORY_SEPARATOR;
		$invoices = array();
		$path = public_path().$ds.'invoices'.$ds.$this->id.$ds;
		if(file_exists($path)){
			foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file){
			    // filter out "." and ".."
			    if ($file->isDir()) continue;
			    $invoices[] = $file->getFilename();
			}
		}
		return $invoices;
	}
	public function getInvoiceHtmlListAttribute(){
		$ds = DIRECTORY_SEPARATOR;
		$invoices = false;
		$path = public_path().$ds.'invoices'.$ds.$this->id.$ds;
		if(file_exists($path)){
			$invoices = "<ul>";
			foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file){
			    // filter out "." and ".."
			    if ($file->isDir()) continue;
			    $filename = $file->getFilename();
			    $invoices .= "<li><a href='".asset("invoices/".$this->id."/".$filename)."' target='_blank'>$filename</a></li>";
			}
			$invoices .= "</ul>";
		}
		return $invoices;
	}
	public function qbCheckOrCreate($dataService){
    	if($this->qb_id){
	    	$entities = $dataService->Query("select * from Invoice where Id='{$this->qb_id}'");
	    	if($entities != null){
				if(!empty($entities) && sizeof($entities) == 1){
				    $invoice = current($entities);
				    return $invoice;
				}
			}
		}
		else{
	    	$entities = $dataService->Query("select * from Invoice where DocNumber='{$this->id}'");
	    	if($entities != null){
				if(!empty($entities) && sizeof($entities) == 1){
				    $invoice = current($entities);
				    return $invoice;
				}
			}
		}
		$user = $this->user;
		if($customer = $user->qbCheckOrCreate($dataService)){
			$transaction = $this->transaction;
	        $lines = array();
	        foreach($this->details as $detail){
	        	$product = $detail->product;
	        	if($item = $product->qbCheckOrCreate($dataService)){
		        	$lines[] = [
			            "Description" => $product->item_number.' - '.$product->name.' - '.$detail->options,
			            "Amount" => (float)$detail->subtotal,
			            "DetailType" => "SalesItemLineDetail",
			            "SalesItemLineDetail" => [
			                "ItemRef" => [
			                    "value" => $item->Id,
			                    "name" => $item->Name
			                ],
			                "Qty" => $detail->quantity,
			                "TaxCodeRef" => [
			                	"value" => "TAX"
			                ]
			            ]
				  	];
	        	}
	        	else{
		        	$lines[] = [
			            "Description" => $product->item_number.' - '.$product->name.' - '.$detail->options,
			            "Amount" => (float)$detail->subtotal,
			            "DetailType" => "SalesItemLineDetail",
			            "SalesItemLineDetail" => [
			                "Qty" => $detail->quantity,
			                "TaxCodeRef" => [
			                	"value" => "TAX"
			                ]
			            ]
				  	];
				}
			}
			$invoice = QBInvoice::create([
			  	"DocNumber" => $this->id,
			  	"Line" => $lines,
			  	"CustomerRef" => [
			    	"value" => $customer->Id,
			    	"name" => $customer->DisplayName
			  	],
		        "BillAddr" => [
		            "Line1" => $transaction->name,
		            "Line2" => $transaction->address1.($transaction->address2?' '.$transaction->address2:''),
		            "City" => $transaction->city,
		            "CountrySubDivisionCode" => $transaction->state,
		            "PostalCode" => $transaction->zip,
		        ],
		        "ShipAddr" => [
		            "Line1" => $this->shippingname,
		            "Line2" => $this->address1.($this->address2?' '.$this->address2:''),
		            "City" => $this->city,
		            "CountrySubDivisionCode" => $this->state,
		            "PostalCode" => $this->zip,
		        ],
		        "TxnTaxDetail" => [
		        	"TxnTaxCodeRef" => [
		        		"value" => "4"
		        	],
		        	"TotalTax" => (float)$this->tax
		        ]
			]);
			$response = $dataService->Add($invoice);
			if (null != $error = $dataService->getLastError()) {
				$errormessage = "Invoice Creation Error: \n";
			    $errormessage .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
			    $errormessage .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
			    $errormessage .= "The Response message is: " . $error->getResponseBody() . "\n";
			    Log::error($errormessage);
			    return false;
			}
			$this->qb_id = $response->Id;
			$this->save();
			return $response;
		}
		return false;

	}
}
