<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\State;

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
		$state = State::where('abbr',$this->state)->first();
		$tax = ((float)$state->tax)/100;
		return ((float)$this->total*$tax)+(float)$this->total;
	}
	public function getTaxAttribute(){
		$state = State::where('abbr',$this->state)->first();
		$tax = ((float)$state->tax)/100;
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
}
