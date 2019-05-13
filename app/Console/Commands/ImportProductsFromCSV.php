<?php namespace App\Console\Commands;

use App\Product;
use Illuminate\Console\Command;
use Excel;
use Storage;

class ImportProductsFromCSV extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'products:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports products from app storage.';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        $filename = storage_path('imports').'/products.csv';
        Excel::load($filename, function($sheet) {
            // Loop through all the sheets
            $sheet->each(function($row) {
                $rowData = array_map('trim', $row->toArray());
                $product = new Product($rowData);
                $product->save();

                if(!empty($rowData['imageitem'])){
                    $images = explode(',',$rowData['imageitem']);
                    if(count($images)>0){
                        $pictures = array();
                        foreach($images as $file){
                            if(filter_var($file, FILTER_VALIDATE_URL)){
                                $ch = curl_init($file);
                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
                                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
                                $contents = curl_exec($ch);
                                curl_close($ch);

                                if($contents){
                                    $ext = pathinfo(parse_url($file)['path'], PATHINFO_EXTENSION);
                                    $original_file_name = pathinfo(parse_url($file)['path'], PATHINFO_FILENAME).'.'.$ext;

                    				$destinationPath = public_path().'/pictures';
                    				$filename = $product->slug.'.'.$ext;
                    				Storage::disk('public_pictures')->put($filename, $contents);
                    				$product->picture = $filename;
                                    $product->save();
                                }
                            }
                            else{
                                // ???
                            }
                        }
                    }
                }
            });
        });
    }
}
