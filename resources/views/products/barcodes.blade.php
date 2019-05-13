<html>
  <head>
    <style>
      * {
        box-sizing: border-box;
      }
      @page{
        margin: 0;
        padding: 0;
        margin-top: 0.5in;
      }
      html,
      body{
        margin: 0;
        padding: 0;
      }
      table{
        table-layout: fixed;
        width: 8.5in;
        height: 10in;
        border-collapse: collapse;
        vertical-align: top;
      }
      td:first-child{
        vertical-align: top;
        text-align: right;
        padding-left: 0.5in;
      }
      td:last-child{
        vertical-align: top;
        text-align: left;
        padding-right: 0.5in;
      }
      td{
        font-size: 12px;
        background: transparent;
        width: 2.125in;
        height: 0.67in;
        max-width: 2.125in;
        max-height: 0.67in;
        overflow: hidden;

        padding-left: 0.293in;
        padding-right: 0.293in;
      }
      td img{
        width: auto;
        max-width: 119px;
      }
      .page-break{
        clear: both;
        display:block;
        page-break-before:always;
      }
    </style>
  </head>
  <body>
    @if($count = $uoms->count())
      <?php $i = 1; ?>
      @if($count>=15)
        @foreach ($uoms->chunk(15) as $row)
          <table>
            @foreach ($row as $uom)
                <tr>
                  <?php $i++; ?>
                  <td>{{ $uom->products->name }} - {{ $uom->name }}</td>
                  <td><img src="{!! $uom->barcode !!}"></td>
                </tr>
            @endforeach
          </table>
          @if($i !== $count)
            <div class="page-break"></div>
          @endif
        @endforeach
      @else
        <table>
          @foreach ($uoms as $uom)
            <tr>
                <?php $i++; ?>
                <td>{{ $uom->products->name }} - {{ $uom->name }}</td>
                <td><img src="{!! $uom->barcode !!}"></td>
            </tr>
          @endforeach
          {{-- fill in rest of page becuase it borks otherwise --}}
          @for($k=$i; $k < 15; $k++)
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          @endfor
        </table>
      @endif
    @endif

    @if(isset($_GET['print']))
      <script>
        window.onload = function(){
          window.print();
        }
      </script>
    @endif
  </body>
</html>
