<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;

            /* margin: 1rem auto; */
          border-radius: 10px;
          border-top: #d74034 2px solid;
          border-bottom: #d74034 2px solid;
          box-shadow: 0 2px 18px rgba(0, 0, 0, 0.2);
         
  
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        p {
            margin-bottom: 20px;
        }
        .text-end{
          text-align: right;
        }
        .text-center{
          text-align: center;
        }

    </style>
</head>
<body>
  <div class="container">
     <div>
        <div style="margin-top: 20px;">{{ __('app.order_summary.greeting', ['name' => $invoice->customer->full_name]) }},</div>
        <p style="font-size:14px;">{{ __('app.order_summary.message_line_1', ['company_name' => get_company_name()]) }}</p>
     </div>
     <div style="margin-bottom: 20px;">
        <div class="text-end">{{ __('INVOICE') }} # {{ $invoice->number }} </div>
        <address class="text-end" style="margin-top: 10px;">
           <div><?php echo get_company_name() ?></div>
           <div><?php echo display_html_content(get_company_address()) ?></div>
        </address>
     </div>
     <table style="font-size: 12px;">
        <thead>
           <tr>
              <th>#</th>
              <th>
                 {{ __("Item") }}
              </th>
              <th>{{ __("Quantity") }}</th>
              <th class="text-end">{{ __("Rate") }}</th>
              <th class="text-end">{{ __("Sub Total") }}</th>
           </tr>
        </thead>
        <tbody>
           @foreach($invoice->items as $key=>$item)
           <tr>
              <td>{{ $key + 1 }}</td>
              <td>
                 <div>{{ $item->name }}</div>
                 <p >
                    <?php echo display_html_content($item->description) ?>
                 </p>
              </td>
              <td>{{ $item->quantity }} </td>
              <td  class="text-end">{{ format_money($item->price) }}</td>
              <td  class="text-end">{{ format_money($item->sub_total) }}</td>
           </tr>
           @endforeach
        </tbody>
        <tfoot>
           <tr>
              <td colspan="4" class="text-end">{{ __("Sub Total") }}</td>
              <td class="total text-end">{{ format_money($invoice->sub_total) }}</td>
           </tr>
           <tr>
              <td colspan="4" class="text-end">{{ __("Discount") }}</td>
              <td class="text-end">{{ format_money($invoice->discount) }}</td>
           </tr>
           <tr>
              <td colspan="4" class="text-end">{{ __("Discount Coupon") }}</td>
              <td class="text-end">{{ format_money($invoice->coupon_discount) }}</td>
           </tr>
           <tr>
              <td colspan="4" class="text-end">{{ __("Sales Tax") }}</td>
              <td class="text-end">{{ format_money($invoice->sales_tax_amount) }}</td>
           </tr>
           <tr>
              <td colspan="4"  class="total text-end">{{ __("Total") }}</td>
              <td class="text-end">{{ format_money($invoice->total) }}</td>
           </tr>
           <tr>
              <td colspan="4"  class="total text-end">{{ __("Amount Paid") }}</td>
              <td class="text-end">{{ format_money($invoice->amount_paid) }}</td>
           </tr>
        </tfoot>
     </table>
     <p class="text-center">{{ __('app.order_summary.footer_text') }}!</p>
     <div class="email-footer">
        <div class="footer-text">
           &copy; <a href="{{ URL::to('/') }}"  target="_blank">{{ get_company_name() }}</a>
        </div>
     </div>
  </div>
</body>
</html>
