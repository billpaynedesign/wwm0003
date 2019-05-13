<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use CodeItNow\BarcodeBundle\Utils\BarcodeGenerator;

class UnitOfMeasure extends Model {

	protected $table = 'units_of_measure';

	public function products()
    {
        return $this->belongsTo('App\Product','product_id');
    }
    /**
     * Alias for products
     * @return BelongsTo Belongs to product
     */
    public function product(){
        return $this->belongsTo('App\Product','product_id');
    }
	public function vendors(){
		return $this->belongsToMany('App\Vendor','uom_vendor','uom_id')->withPivot('cost');
	}
    public function getPriceStringAttribute(){
        return '$'.\number_format((float)$this->price,2);
    }
    public function getMsrpStringAttribute(){
        return '$'.\number_format((float)$this->msrp,2);
    }
    /**
     * Generates barcode base64 to be placed
     * in a src="" attribute of an img tag
     * @return String|Bool Base64 string or false if cannot generate
     */
    public function getBarcodeAttribute(){
        if($this->products){
            $barcode = new BarcodeGenerator;
            $barcode->setText(sprintf('%08d', $this->id));
            $barcode->setType(BarcodeGenerator::Code128);
            $barcode->setScale(2);
            $barcode->setThickness(20);
            $barcode->setFontSize(10);
            $code = $barcode->generate();

            return 'data:image/png;base64,'.$code;
        }
        return false;
    }
}
