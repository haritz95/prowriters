@extends('installer.template')
@section('title', ($data['status'] == 1) ? 'Installation Complete' : 'Installation Failed')
@section('content')

<div class="row">
	<div class="offset-md-2 col-md-8">
		<div class="card mt-5" style="margin-bottom: 10%;">
			<div class="card-body">
			   <h4 class="card-title"><i class="fas {{ $data['icon'] }}"></i> {{ $data['title'] }}</h4>
			   <hr>
				 <p><b><?php echo $data['msg']; ?></b></p> 
				 
				 @if($data['status'] == 1)
					 <p class="text-muted" style="font-size: 14px;">
						Before using the application, please navigate to the "Manage" menu and configure all the service and application settings individually. This includes settings for email, payment gateways, Google reCAPTCHA, TinyMCE Free API Key, etc.
					 </p>
					 <p class="text-muted" style="font-size: 14px;">
						Please be aware that if you begin using the application without configuring your settings first, you may encounter error pages. Therefore, please ensure that you set up everything before you start using the application.
					 </p>
					 <p style="font-size: 14px;">For support please send us an email at <b>support@microelephant.io</b> with your purchase code</p>
					 <table class="table table-sm">
						 <tr>
							 <td>Email</td>
							 <td class="text-end">admin@demo.com</td>
						 </tr>
						 <tr>
							 <td>Password</td>
							 <td class="text-end">123456</td>
						 </tr>
					 </table>        
					 <a href="{{ route('login') }}"class="btn btn-primary">Go to Login page</a>
				 @endif
			</div>
		 </div>

	</div>
</div>
@endsection      