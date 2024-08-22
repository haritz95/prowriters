@extends('installer.template')
@section('title', "Server Requirements")
@section('content')
<?php
   $strOk = '<i class="fas fa-check-circle"></i>';
   $strFail = '<i class="fas fa-exclamation-triangle"></i>';
   $strUnknown = '<i class="fas fa-question"></i>';
   
   ?>
<h3 class="text-center mt-5 mb-4">Welcome to Prowriters</h3>

<div class="shadow p-3 mb-5 bg-white rounded mx-auto w-75" style="font-size: 13px;">
   <h5>Server Requirements</h5>
   <div>Prowriters Version: {{ get_software_version() }}</div>
   <hr>
   <p>PHP >= {{   $data['required_php_version'] }} <?php echo ($data['requirement_statuses']['php'] ? $strOk : $strFail); ?>
   , Installed PHP version : {{ PHP_VERSION }}
   </p>
   
   <div class="row">
      <div class="col-md-6">
         <table class="table table-sm table-bordered">
            <thead>
               <tr>
                  <th scope="col">PHP Extensions</th>
                  <th scope="col"></th>
               </tr>
            </thead>
            <tbody>
               @foreach ($data['requirement_statuses'] as $extension_name=>$status)
               <tr>
                  <td>{{ $extension_name }}</td>
                  <td><?php echo ($status) ? $strOk : $strFail; ?></td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
      <div class="col-md-6">
         <table class="table table-sm table-bordered">
            <thead>
               <tr>
                  <th scope="col">Folders</th>
                  <th scope="col"></th>
               </tr>
            </thead>
            <tbody>
               @foreach($data['folder_permissions'] as $row)
               <tr>
                  <td>{{ $row['folder'] }}</td>
                  <td class="text-center"><?php echo ($row['is_set'] == 1) ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>' ?></td>
               </tr>
               @endforeach
            </tbody>
         </table>
         <table class="table table-sm table-bordered">
            <thead>
               <tr>
                  <th scope="col">SymLink</th>
                  <th scope="col"></th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td> {{ public_path('storage') }} <br><b>to</b><br>  {{ storage_path('app/public') }}</td>
                  <td class="text-center"><?php echo ($data['is_symlink_enabled'] == TRUE) ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>' ?></td>
               </tr>
            </tbody>
         </table>
         <p class="text-danger" style="font-size: 14px;">For support please send us an email at <b>support@microelephant.io</b> with your purchase code</p>
      </div>
   </div>
   @if($data['number_of_errors'] == 0)
   <a class="btn btn-primary float-md-right" href="{{ route('run_installation_step_2_page') }}"> <i class="fas fa-arrow-circle-right"></i> &nbsp Next  &nbsp &nbsp</a>          
   <div class="clearfix"></div>
   @else
   <p class="text-danger">Your server does not meet all the requirements to install the application</p>
   @endif
</div>
@endsection