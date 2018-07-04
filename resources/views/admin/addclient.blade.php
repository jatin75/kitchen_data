@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
{{-- <link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" /> --}}
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
{{-- <link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/switchery/dist/switchery.min.css')}}" /> --}}
<style type="text/css">
.nav-link.active {
	background: #4c5667 !important;
}
.nav-pills > li.active > a, .nav-pills > li.active > a:focus, .nav-pills > li.active > a:hover {
	background: #4c5667 !important;
	color: #ffffff !important;
}
.disabled-color{
	color: #90989c !important;
}
</style>
@stop
@section('content')
<div class="container-fluid">
	<div class="row bg-title">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Clients</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-wrapper collapse in" aria-expanded="true">
					<!--header-->
					@if(Session::get('login_type_id') != 9)
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-4 m-t-15 m-l-15">
							<a class="btn btn-default btn-circle" href="{{route('showclients')}}" title="Previous"><i class="ti-arrow-left"></i> </a>
						</div>
					</div>
					@endif
					<!--/header-->
					<div class="panel-body">
						<ul class="nav nav-pills m-b-30">
							<li class="active nav-item"> <a href="#tab1" class="nav-link" data-toggle="tab" aria-expanded="true">@if(isset($accountSetting)) {{ "MY PROFILE" }} @else {{ "CLIENT PROFILE" }} @endif</a> </li>
							@if(isset($accountSetting))
							<li class="nav-item"> <a href="#tab2" class="nav-link" data-toggle="tab" aria-expanded="true">ACCOUNT SETTINGS</a> </li>
							@endif
							{{-- @if(!isset($accountSetting) && (isset($permissionList) && $permissionList->email != Session::get('email') && $permissionList->is_admin = 1))
							<li class="nav-item"> <a href="#tab3" class="nav-link" data-toggle="tab" aria-expanded="true">ACCOUNT PERMISSIONS</a> </li>
							@endif --}}
						</ul>
						<div class="form-body form-material">
							<div class="tab-content br-n pn">
								<!--tab1-->
								<div id="tab1" class="tab-pane active">
									<form id="formAddClient" method="post">
										{{ csrf_field() }}
										{{-- <input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$accountDetail->email or ''}}"> --}}
										{{-- <input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
										<input type="hidden" name="hiddenClientId" id="hiddenClientId" value="{{$clientDetails->client_id or ''}}">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>FIRST NAME</b></label>
													<input style="text-transform:uppercase;" type="text" name="clientFirstName" id="clientFirstName" value="{{$clientDetails->first_name or ''}}" class="form-control" placeholder=" FIRST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>LAST NAME</b></label>
													<input style="text-transform:uppercase;" type="text" name="clientLastName" id="clientLastName" value="{{$clientDetails->last_name or ''}}" class="form-control" placeholder=" LAST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>CLIENT ID</b></label><br>
													<span class="disabled-color" id="clientId">{{$clientDetails->client_id or '' }}</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMAIL ADDRESS</b></label>
													<input style="text-transform: lowercase;" type="email" name="clientEmail" id="clientEmail" value="{{$clientDetails->email or ''}}" class="form-control"  placeholder="Enter Your Email">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>PHONE NUMBER</b></label>
													<input type="text" placeholder="(xxx) xxx-xxxx" name="clientContactNo" id="clientContactNo" value="{{$clientDetails->phone_number or ''}}" class="form-control">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>COMPANY NAME</b></label>
													<select id="clientCompany" name="clientCompany" class="form-control select2">
														<option value="">-- Select Company --</option>
														@foreach($companyList as $company)
														<option value="{{ $company->company_id }}" @if(isset($clientDetails->company_id) && $clientDetails->company_id == $company->company_id) {{"selected='selected'"}} @endif>{{ $company->name }}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>ADDRESS 1</b></label>
													<input type="text" name="locationAddress" id="locationAddress" value="{{$clientDetails->address_1 or ''}}" class="form-control" placeholder="Address line 1">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>ADDRESS 2</b></label><br>
													<input type="text" name="subAddress" id="subAddress" value="{{$clientDetails->address_2 or ''}}" class="form-control" placeholder="Address line 2">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>CITY</b></label>
													<input type="text" name="city" id="city" value="{{$clientDetails->city or ''}}" class="form-control" placeholder="Enter City">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>STATE</b></label>
													<input type="text" name="state" id="state" value="{{$clientDetails->state or ''}}" class="form-control"  placeholder="Enter State">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>ZIPCODE</b></label>
													<input type="text" placeholder="Enter Zipcode" name="zipcode" id="zipcode" value="{{$clientDetails->zipcode or ''}}" class="form-control">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>CONTACT PREFERENCE</b></label>
													<select id="contactPreference" name="contactPreference" class="form-control">
														<option value="1" @if(isset($clientDetails->contact_preference) && $clientDetails->contact_preference == 1) {{ "selected='selected'" }} @endif>Email</option>
														<option value="2" @if(isset($clientDetails->contact_preference) && $clientDetails->contact_preference == 2) {{ "selected='selected'" }} @endif>App</option>
														<option value="3" @if(isset($accountDetail->contact_preference) && $clientDetails->contact_preference == 3) {{ "selected='selected'" }} @endif>Phone</option>
													</select>
												</div>
											</div>
										</div>
										<div class="form-group text-left p-t-md">
											@if(!isset($clientDetails->client_id))
											<button type="submit" class="btn btn-success">CREATE ACCOUNT</button>
											@endif
											@if(isset($clientDetails->client_id))
											<button type="submit" class="btn btn-info">UPDATE</button>
											@endif
											&nbsp;
											&nbsp;
											<button id="resetPermission" type="button" class="btn btn-danger">CANCEL</button>
										</div>
									</form>
								</div>
								<!--/.tab1-->
								<!--tab2-->
								@if(isset($accountSetting))
								<div id="tab2" class="tab-pane">
									<form id="formAccountSetting" method="post">
										{{ csrf_field() }}
										<input type="hidden" name="hiddenId" id="hiddenId" value="{{$clientDetails->client_id or ''}}">
										{{-- <input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
										{{-- <input type="hidden" name="hiddenClientId" id="hiddenClientId" value="{{$staffDetail->staff_id or ''}}"> --}}
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>CHANGE PASSWORD</b></label>
													<input type="password" name="currentPassword" id="currentPassword" class="form-control" placeholder="CURRENT PASSWORD">
													<input type="password" name="newPassword" id="newPassword" class="form-control" placeholder="NEW PASSWORD">
													<input type="password" name="retypePassword" id="retypePassword" class="form-control" placeholder="RETYPE PASSWORD">
												</div>
											</div>
										</div>
										<div class="form-group text-left p-t-md">
											<button type="submit" class="btn btn-info">UPDATE</button>
										</div>
									</form>
								</div>
								@endif
								<!--/.tab2-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('pageSpecificJs')
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/buttons.flash.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/buttons.print.min.js')}}"></script>
{{-- <script type="text/javascript" src="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script> --}}
{{-- <script type="text/javascript" src="{{asset('plugins/bower_components/switchery/dist/switchery.min.js')}}"></script> --}}
<script src="{{ asset('scripts/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/custom-select/custom-select.min.js')}}"></script>
<script src="{{ asset('scripts/company-location.js') }}"></script>
<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(document).ready(function() {
		$('#subscriberList').DataTable({
			dom: 'Bfrtip',
			buttons: [
			'csv', 'excel', 'pdf', 'print'
			],
		});
		if(typeof($('#clientFirstName').val()) != "undefined" && $('#clientFirstName').val() !== null)
			$('#clientFirstName').val($('#clientFirstName').val().toUpperCase());
		if(typeof($('#clientLastName').val()) != "undefined" && $('#clientLastName').val() !== null)
			$('#clientLastName').val($('#clientLastName').val().toUpperCase());
		if(typeof($('#clientEmail').val()) != "undefined" && $('#clientEmail').val() !== null)
			$('#clientEmail').val($('#clientEmail').val().toLowerCase());

		$('#resetPermission').click(function(){
			location.reload();
		});
	});

	/* For select 2*/
	$(".select2").select2();
	/* Switchery*/
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	$('.js-switch').each(function() {
		new Switchery($(this)[0], $(this).data());
	});

	/*$('#formAddClient').on('success.form.bv', function(e) {*/
		$('#formAddClient').on('success.form.bv', function(e) {
			e.preventDefault();
			$('#loader').show();
			var hidden_client_id = $('#hiddenClientId').val();
			var client_first_name = $('#clientFirstName').val();
			var client_last_name = $('#clientLastName').val();
			var client_email = $('#clientEmail').val();
			var client_contactNo = $('#clientContactNo').val();
			var client_company = $('#clientCompany').val();
			var address_1 = $('#locationAddress').val();
			var address_2 = $('#subAddress').val();
			var city = $('#city').val();
			var state = $('#state').val();
			var zipcode = $('#zipcode').val();
			var contact_preference = $('#contactPreference').val();
			$.ajax({
				url:'{{ route('storeclient') }}',
				data:{
					hidden_client_id:hidden_client_id,
					client_first_name:client_first_name,
					client_last_name:client_last_name,
					client_email:client_email,
					client_contactNo:client_contactNo,
					client_company:client_company,
					address_1:address_1,
					address_2:address_2,
					city:city,
					state:state,
					zipcode:zipcode,
					contact_preference:contact_preference
				},
				type:'post',
				dataType:'json',
				success: function(data)
				{
					if(data.key == 1)
					{
						location.href = '{{ route('showclients') }}';
					}
					else if(data.key == 2)
					{
						if(typeof(data.name) != "undefined" && data.name !== null)
						{
							$('#sessionName').html(data.name);
						}
						$('#loader').hide();
						notify('Client has been updated successfully','blackgloss');
					}
					else if(data.key == 3)
					{
						$('#loader').hide();
						notify('Entered email address already exists.','blackgloss');
					}
				}
			});
		});

		$("#formAccountSetting").on('success.form.bv',function(e){
			e.preventDefault();
			$("#loader").show();
			var current_password = $("#currentPassword").val();
			var new_password = $('#newPassword').val();
			var retype_password = $('#retypePassword').val();
			var hidden_Id = $('#hiddenId').val();
			if(new_password != retype_password) {
				$("#loader").hide();
				notify('New password and  Retype password is not match. Please try again.','blackgloss');
				return;
			}
			$.ajax({
				url:'{{ route('changepassword') }}',
				data:{
					current_password:current_password,
					new_password:new_password,
					hidden_Id:hidden_Id,
				},
				type:'post',
				success: function(data)
				{
					if(data == 1)
					{
						$('#loader').hide();
						notify('Your password has been reset.','blackgloss');
					}
					else if(data == 2)
					{
						$('#loader').hide();
						notify('Current password is invalid. Please try again.','blackgloss');
					}
				}
			});
		});

	/*function deactivateAccount(account_id)
	{
		if(confirm(' You cant reactivate prospect. Are you sure you want to remove this prospect?')){
			$('#loader').show();
			$.ajax({
				url:'{ url('accountstatus') }}',
				data:{
					account_id:account_id,
				},
				type:'post',
				success: function(data)
				{
					if(data == 'deactivated')
					{
						location.href = '{ route('activeaccount') }}';
					}
					else
					{
						location.href = '{ route('deactivatedaccount') }}';
					}
				}
			});
		}
	}*/

	$('#clientFirstName,#clientLastName').keyup(function() {
		this.value = this.value.toUpperCase();
	});

	$('#clientEmail').keyup(function() {
		this.value = this.value.toLowerCase();
	});

	// hide previous button
	$(document).ready(function(){
		// textarea_feedback
		// if(typeof($('#txtLeagueInfo').val()) != "undefined" && $('#txtLeagueInfo').val() !== null)
		// {
		// 	var text_max = 138;
		// 	var onFormLoad = text_max - $('#txtLeagueInfo').val().length;
		// 	if(onFormLoad <= 1)
		// 	{
		// 		$('#textarea_feedback').html(onFormLoad + ' character remaining');
		// 	}
		// 	else
		// 	{
		// 		$('#textarea_feedback').html(onFormLoad + ' characters remaining');
		// 	}
		// }
	});

	/*$('#txtLeagueInfo').keyup(function() {
		var text_max = 138;
		var text_length = $('#txtLeagueInfo').val().length;
		var text_remaining = text_max - text_length;

		if(text_remaining <= 1)
		{
			$('#textarea_feedback').html(text_remaining + ' character remaining');
		}
		else
		{
			$('#textarea_feedback').html(text_remaining + ' characters remaining');
		}
	});*/

	/*prevent form to submit on enter*/
	$(document).on("keypress", ":input:not(textarea)", function(event) {
		return event.keyCode != 13;
	});

	/*Mask phone Number Digits*/
	/*$("#leagueContactNo").mask("999-999-999-9?999999");*/
	$("#clientContactNo").mask("(999) 999 - 9999");

	/*Date picker*/
	/*jQuery('#startDate').datepicker({
		autoclose: true,
		todayHighlight: true,
		startDate: new Date()
	});*/
	function changePermission(id){
		var value = $('#access_'+id).val();
		if(value == 1){
			$('#access_'+id).val(0);
		} else {
			$('#access_'+id).val(1);
		}
	}
</script>
@stop