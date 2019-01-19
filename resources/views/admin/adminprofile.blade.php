@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('assets/css/pages/adminprofile.css')}}" />
@stop
@section('content')
<div class="container-fluid">
	<div class="row bg-title">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Admin</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-wrapper collapse in" aria-expanded="true">
					<!--header-->
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-4 m-t-15 m-l-15">
							<a class="btn btn-default btn-circle" href="{{URL::previous()}}" title="Previous"><i class="ti-arrow-left"></i> </a>
						</div>
						{{-- <div class="col-md-4 col-sm-4 col-xs-4 text-center">
							<h2 class="_600" id="pageName">Add Account</h2>
						</div> --}}
					</div>
					<!--/header-->
					<div class="panel-body">
						<ul class="nav nav-pills m-b-30">
							<li class="active nav-item"> <a href="#tab1" class="nav-link" data-toggle="tab" aria-expanded="true">MY  PROFILE</a> </li>
							@if(isset($accountSetting))
							<li class="nav-item"> <a href="#tab2" class="nav-link" data-toggle="tab" aria-expanded="true">ACCOUNT SETTINGS</a> </li>
							@endif
						</ul>
						<div class="form-body form-material">
							<div class="tab-content br-n pn">
								<!--tab1-->
								<div id="tab1" class="tab-pane active">
									<form id="formAddAdmin" method="post">
										{{ csrf_field() }}
										<input type="hidden" name="hiddenAdminId" id="hiddenAdminId" value="{{$adminDetail->id or ''}}">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>FIRST NAME</b></label>
													<input style="text-transform:uppercase;" type="text" name="adminFirstName" id="adminFirstName" value="{{$adminDetail->first_name or ''}}" class="form-control" placeholder="ADMIN FIRST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>LAST NAME</b></label>
													<input style="text-transform: uppercase;" type="text" name="adminLastName" id="adminLastName" value="{{$adminDetail->last_name or ''}}" class="form-control"  placeholder="ADMIN LAST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>PHONE NUMBER</b></label>
													<input type="text" placeholder="(xxx) xxx-xxxx" name="adminPhoneNo" id="adminPhoneNo" value="{{$adminDetail->phone_number or ''}}" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMAIL ADDRESS</b></label>
													<input style="text-transform: lowercase;" type="email" name="adminEmail" id="adminEmail" value="{{$adminDetail->email or ''}}" class="form-control"  placeholder="Enter your email">
												</div>
											</div>
										</div>
										<div class="form-group text-left p-t-md">
											@if(!isset($adminDetail->id))
											<button type="submit" class="btn btn-success">Add</button>
											@endif
											@if(isset($adminDetail->id))
											<button type="submit" class="btn btn-info">UPDATE</button>
											@endif
										</div>
									</form>
								</div>
								<!--/.tab1-->
								<!--tab2-->
								@if(isset($accountSetting))
								<div id="tab2" class="tab-pane">
									<form id="formAccountSetting" method="post">
										{{ csrf_field() }}
										<input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$adminDetail->email or ''}}">
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
<script src="{{ asset('scripts/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/custom-select/custom-select.min.js')}}"></script>
<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(document).ready(function() {
		if(typeof($('#adminFirstName').val()) != "undefined" && $('#adminFirstName').val() !== null)
			$('#adminFirstName').val($('#adminFirstName').val().toUpperCase());
		if(typeof($('#adminLastName').val()) != "undefined" && $('#adminLastName').val() !== null)
			$('#adminLastName').val($('#adminLastName').val().toUpperCase());
		if(typeof($('#adminEmail').val()) != "undefined" && $('#adminEmail').val() !== null)
			$('#adminEmail').val($('#adminEmail').val().toLowerCase());
	});

	/* For select 2*/
	$(".select2").select2();

	$('#formAddAdmin').on('success.form.bv', function(e) {
		e.preventDefault();
		$('#loader').show();
		var hidden_adminId = $('#hiddenAdminId').val();
		var admin_firstName = $('#adminFirstName').val();
		var admin_lastName = $('#adminLastName').val();
		var admin_contactNo = $('#adminPhoneNo').val();
		var admin_email = $('#adminEmail').val();

		$.ajax({
			url:'{{ route('storeadmin') }}',
			data:{
				hidden_adminId:hidden_adminId,
				admin_firstName:admin_firstName,
				admin_lastName:admin_lastName,
				admin_contactNo:admin_contactNo,
				admin_email:admin_email,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data.key == 1)
				{
					if(typeof(data.name) != "undefined" && data.name !== null)
					{
						$('#sessionName').html(data.name);
					}
					$('#loader').hide();
					notify('Account has been updated successfully','blackgloss');
				}
				else if(data.key == 2)
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
		var hidden_email = $('#hiddenMail').val();
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
				hidden_email:hidden_email,
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

	$('#adminFirstName').keyup(function() {
		this.value = this.value.toUpperCase();
	});

	$('#adminLastName').keyup(function() {
		this.value = this.value.toUpperCase();
	});

	$('#adminEmail').keyup(function() {
		this.value = this.value.toLowerCase();
	});

	/*prevent form to submit on enter*/
	$(document).on("keypress", ":input:not(textarea)", function(event) {
		return event.keyCode != 13;
	});

	/*Mask phone Number Digits*/
	$("#adminPhoneNo").mask("(999) 999 - 9999");

	@if(Session::has('successMessage'))
	notify('{{  Session::get('successMessage') }}','blackgloss');
	{{ Session::forget('successMessage') }}
	@endif
</script>
@stop