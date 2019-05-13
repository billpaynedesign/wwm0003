/* ========================================================================
 * Barcode Reader: jquery.mmp.barcodereader.js
 * https://github.com/mimopo/jquery.mmp.barcodereader
 * http://mimopo.es
 * ========================================================================
 * Copyright 2014 - Miguel Montes Porras - @mimopoweb
 * Licensed under WTFPL (http://www.wtfpl.net/)
 * ========================================================================
 * Edited By: Leopold Bodden
 * Date: 05-01-2018
 * ======================================================================== */

(function( $ ) {
    $.fn.mmpBarcodeReader = function() {
        // Initialize buffer, it will contain the barcode scanner output
        $(this).data('mmpBarcodeBuffer', '');
        // Listen to barcode scanner output
        $(this).keydown(function(e){
            switch (e.which) {
                // A Prefix (Start of Text)
                case 65:
                    $(this).trigger('start.mmp.barcodereader');
                    $(this).data('mmpBarcodeReading', true);
                    break;
                // CR/Enter Suffix (End of Text)
                case 13:
                    $(this).trigger('end.mmp.barcodereader', $(this).data('mmpBarcodeBuffer'));
                    $(this).data('mmpBarcodeReading', false);
                    $(this).data('mmpBarcodeBuffer', '');
                    break;
                // Regular char
                default:
                    if ($(this).data('mmpBarcodeReading')){
                        $(this).trigger('char.mmp.barcodereader', String.fromCharCode(e.which));
                        $(this).data('mmpBarcodeBuffer', $(this).data('mmpBarcodeBuffer') + String.fromCharCode(e.which));
                    }
                    break;
            }
        });
        // Sometimes the STX Prefix triggers alternately the keyup & keydown events. Let's fix it!
        $(this).keyup(function(e){
            if (e.which == 20){
                $(this).trigger('start.mmp.barcodereader');
                $(this).data('mmpBarcodeReading', true);
            }
        });
    };
}( jQuery ));