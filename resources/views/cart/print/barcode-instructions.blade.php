<html>

<head>
    <style>
        * {
            box-sizing: border-box;
        }

        <blade page {
            />
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        ol li img {
            width: 1.25in;
            height: auto;
            margin-top: 0.125in;
            margin-bottom: 0.125in;
        }
    </style>
</head>

<body>
    <ol>
        <li>
            Before scanning items, scan the following barcode.
            <br>
            <img src="{{ asset('/images/barcodes/pre-scan.jpg') }}">
        </li>
        <li>
            Scan items
        </li>
        <li>
            Go to website.
        </li>
        <li>
            Login if you are not
        </li>
        <li>
            Go to cart
        </li>
        <li>
            Click "Barcode Scanner" button
        </li>
        <li>
            After scanning items and on barcode scanner page, scan the following barcode.
            <br>
            <img src="{{ asset('/images/barcodes/post-scan.jpg') }}">
        </li>
        <li>
            Click "Add to cart" button to add to your cart
        </li>
        <li>
            Optional Step: Clear items from barcode scanner
            <br>
            <img src="{{ asset('/images/barcodes/clear-scan.jpg') }}">
        </li>

        <script>
            window.onload = function() {
                window.print();
            }
        </script>
</body>

</html>
