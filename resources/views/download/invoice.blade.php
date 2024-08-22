<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Invoice</title>

		<style>
			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 10px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;				
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}
            .text-end{
                text-align: right;
            }
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="4">
						<table>
							<tr>
								<td class="title">
									{{ $company['name'] }}
									{{-- <img src="{{ $company['logo'] }}" style="width: 100%; max-width: 300px" /> --}}
								</td>

								<td>
									<h2>{{ strtoupper(__('Invoice')) }} # {{ $invoice->number }}</h2>
									{{ __('Created') }}: {{ localize_date($invoice->invoice_date) }}<br />
									{{ __('Due') }}: {{ localize_date($invoice->due_date) }}<br />
                                    {{ __('Status') }}: {{ $invoice->status->name }}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="4">
						<table>
							<tr>
								<td>
                                    <strong>{{ __("Bill To") }} :</strong>
                                    <div>{{ $invoice->customer->full_name }}</div>
									<div>{{ $invoice->customer->email }}</div>   
								</td>

								<td>
                                    <strong>{{ __("Bill From") }} :</strong>
                                    <div>{{ $company['name'] }}<br /></div>
                                    <address>{!! nl2br($company['address']) !!}</address>						
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>{{ __('Item') }}</td>
					<td>{{ __('Quantity') }}</td>
					<td class="text-end">{{ __('Rate') }}</td>
					<td class="text-end">{{ __('Sub Total') }}</td>
				</tr>

				@foreach ($invoice->items as $item)
                <tr class="item">
					<td>
                        {{ $item->name  }} 
                        @if ($item->invoiceable)
                            <span style="font-size:14px;">( {{ $invoiceable_types[$item->invoiceable_type] }} {{ $item->invoiceable->number }})</span>
                        @endif
                        <div style="font-size:12px;">{{ $item->description }}</div>
                    </td>
					<td>{{ $item->quantity  }}</td>
                    <td class="text-end">{{ format_money($item->price)  }}</td>
					<td class="text-end">{{ format_money($item->sub_total)  }}</td>
				</tr>
                @endforeach

				<tr class="total">					
					<td colspan="3" class="text-end">{{ __('Sub Total') }}</td>
					<td class="text-end">{{ format_money($invoice->sub_total) }}</td>
				</tr>
				<tr class="total">					
					<td colspan="3" class="text-end">
						{{ __('Discount') }}						
					</td>
					<td class="text-end">{{ format_money($invoice->discount) }}</td>
				</tr>
				@if($invoice->coupon_discount)
				<tr class="total">					
					<td colspan="3" class="text-end">
						{{ __('Discount Coupon') }}
						@if($invoice->coupon_code)
						<small>({{ round($invoice->coupon_code, 2) }}%)</small>					  
						@endif
					</td>
					<td class="text-end">{{ format_money($invoice->coupon_discount) }}</td>
				</tr>
				@endif
				<tr class="total">					
					<td colspan="3" class="text-end">
						{{ __('Sales Tax') }}
						@if($invoice->sales_tax_rate)
						<small>({{ round($invoice->sales_tax_rate, 2) }}%)</small>					  
						@endif
					</td>
					<td class="text-end">{{ format_money($invoice->sales_tax_amount) }}</td>
				</tr>
				<tr class="total">					
					<td colspan="3" class="text-end">{{ __('Total') }}</td>
					<td class="text-end">{{ format_money($invoice->total) }}</td>
				</tr>
				<tr class="total">					
					<td colspan="3" class="text-end">{{ __('Amount Paid') }}</td>
					<td class="text-end">{{ format_money($invoice->amount_paid) }}</td>
				</tr>
				<tr class="total">					
					<td colspan="3" class="text-end">{{ __('Due') }}</td>
					<td class="text-end">{{ format_money($invoice->total - $invoice->amount_paid) }}</td>
				</tr>

                <tr>
                    <td>                   
                        @if ($invoice->customer_note)
                        <br>
                        <strong>{{ __("Note") }}</strong>
                        <div>{{ nl2br($invoice->customer_note) }}</div>                        
                        @endif   
                        
                        @if ($invoice->terms_and_conditions)     
                        <br>                   
                        <strong>{{ __("Terms and Conditions") }}</strong>
                        <div>{{ nl2br($invoice->terms_and_conditions) }}</div>
                        @endif 
                    </td>
                </tr>
			</table>

           
		</div>
	</body>
</html>