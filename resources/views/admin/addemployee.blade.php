@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
{{-- <link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" /> --}}
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('assets/css/pages/addemployee.css')}}" />
@stop
@section('content')
<div class="container-fluid">
	<div class="row bg-title">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Employees</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-wrapper collapse in" aria-expanded="true">
					<!--header-->
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-4 m-t-15 m-l-15">
							<a class="btn btn-default btn-circle" href="{{route('showemployees')}}" title="Previous"><i class="ti-arrow-left"></i> </a>
						</div>
						{{-- <div class="col-md-4 col-sm-4 col-xs-4 text-center">
							<h2 class="_600" id="pageName">Add Account</h2>
						</div> --}}
					</div>
					<!--/header-->
					<div class="panel-body">
						<ul class="nav nav-pills m-b-30">
							<li class="active nav-item"> <a href="#tab1" class="nav-link" data-toggle="tab" aria-expanded="true">@if(isset($accountSetting)) {{ "MY PROFILE" }} @else {{ "EMPLOYEE PROFILE" }} @endif</a> </li>
							@if(isset($accountSetting))
							<li class="nav-item"> <a href="#tab2" class="nav-link" data-toggle="tab" aria-expanded="true">ACCOUNT SETTINGS</a> </li>
							@endif
						</ul>
						<div class="form-body form-material">
							<div class="tab-content br-n pn">
								<!--tab1-->
								<div id="tab1" class="tab-pane active">
									<form id="formAddEmployee" method="post">
										{{ csrf_field() }}
										{{-- <input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$accountDetail->email or ''}}"> --}}
										{{-- <input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
										<input type="hidden" name="hiddenEmployeeId" id="hiddenEmployeeId" value="{{$employeeDetail->id or ''}}">
										<input type="hidden" name="hiddenEmployeeEmail" id="hiddenEmployeeEmail" value="{{$employeeDetail->email or ''}}">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>FIRST NAME</b></label>
													<input style="text-transform:uppercase;" type="text" name="employeeFirstName" id="employeeFirstName" value="{{$employeeDetail->first_name or ''}}" class="form-control" placeholder="EMPLOYEE FIRST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>LAST NAME</b></label>
													<input style="text-transform: uppercase;" type="text" name="employeeLastName" id="employeeLastName" value="{{$employeeDetail->last_name or ''}}" class="form-control"  placeholder="EMPLOYEE LAST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMPLOYEE ID</b></label><br>
													<span class="disabled-color" id="employeeId">{{$employeeDetail->id or '' }}</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>PHONE NUMBER</b></label>
													<input type="text" placeholder="(xxx) xxx-xxxx" name="employeePhoneNo" id="employeePhoneNo" value="{{$employeeDetail->phone_number or ''}}" class="form-control">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMAIL ADDRESS</b></label>
													<input style="text-transform: lowercase;" type="email" name="employeeEmail" id="employeeEmail" value="{{$employeeDetail->email or ''}}" class="form-control"  placeholder="Enter your email">
												</div>
											</div>
											@if(isset($employeeDetail) && Session::get('employee_id') != $employeeDetail->id)
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMPLOYEE TYPE</b></label><br>
													<select class="form-control select2" name="employeeType" id="employeeType" placeholder="Select your employee type">
														<option value="">-- Select employee type  --</option>
														@foreach($employeeTypes as $employeeType)
														<option value="{{ $employeeType->login_type_id }}" @if(isset($employeeDetail->login_type_id) && $employeeDetail->login_type_id == $employeeType->login_type_id) {{"selected='selected'"}} @endif> {{ $employeeType->type_name }}</option>
														@endforeach
													</select>
												</div>
											</div>
											@elseif(isset($employeeDetail) && Session::get('employee_id') == $employeeDetail->id)
												<input type="hidden" name="employeeType" id="employeeType" value="{{ $employeeDetail->login_type_id }}">

											@elseif(!isset($employeeDetail))
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMPLOYEE TYPE</b></label><br>
													<select class="form-control select2" name="employeeType" id="employeeType" placeholder="Select your employee type">
														<option value="">-- Select employee type  --</option>
														@foreach($employeeTypes as $employeeType)
														<option value="{{ $employeeType->login_type_id }}" > {{ $employeeType->type_name }}</option>
														@endforeach
													</select>
												</div>
											</div>
											@endif
										</div>
										<div class="form-group text-left p-t-md">
											@if(!isset($employeeDetail->id))
											<button type="submit" class="btn btn-success">Add</button>
											@endif
											@if(isset($employeeDetail->id))
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
										<input type="hidden" name="hiddenId" id="hiddenId" value="{{$employeeDetail->id or ''}}">
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
<script src="{{ asset('scripts/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/custom-select/custom-select.min.js')}}"></script>
<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(document).ready(function() {
		if(typeof($('#employeeFirstName').val()) != "undefined" && $('#employeeFirstName').val() !== null)
			$('#employeeFirstName').val($('#employeeFirstName').val().toUpperCase());
		if(typeof($('#employeeLastName').val()) != "undefined" && $('#employeeLastName').val() !== null)
			$('#employeeLastName').val($('#employeeLastName').val().toUpperCase());
		if(typeof($('#employeeEmail').val()) != "undefined" && $('#employeeEmail').val() !== null)
			$('#employeeEmail').val($('#employeeEmail').val().toLowerCase());

		$('#resetPermission').click(function(){
			location.reload();
		});
	});

	/* For select 2*/
	$(".select2").select2();

	$('#formAddEmployee').on('success.form.bv', function(e) {
		e.preventDefault();
		$('#loader').show();
		var hidden_employeeId = $('#hiddenEmployeeId').val();
		var hidden_employeeEmail = $('#hiddenEmployeeEmail').val();
		var employee_firstName = $('#employeeFirstName').val();
		var employee_lastName = $('#employeeLastName').val();
		var employee_contactNo = $('#employeePhoneNo').val();
		var employee_email = $('#employeeEmail').val();
		var employee_type = $('#employeeType').val();

		$.ajax({
			url:'{{ route('storeemployee') }}',
			data:{
				hidden_employeeId:hidden_employeeId,
				hidden_employeeEmail:hidden_employeeEmail,
				employee_firstName:employee_firstName,
				employee_lastName:employee_lastName,
				employee_contactNo:employee_contactNo,
				employee_email:employee_email,
				employee_type:employee_type,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data.key == 1)
				{
					location.href = '{{ route('showemployees') }}';
				}
				if(data.key == 2)
				{
					if(typeof(data.name) != "undefined" && data.name !== null)
					{
						$('#sessionName').html(data.name);
					}
					$('#loader').hide();
					notify('Employee detail has been updated successfully.','blackgloss');
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

	$('#employeeFirstName').keyup(function() {
		this.value = this.value.toUpperCase();
	});

	$('#employeeLastName').keyup(function() {
		this.value = this.value.toUpperCase();
	});

	$('#employeeEmail').keyup(function() {
		this.value = this.value.toLowerCase();
	});

	/*prevent form to submit on enter*/
	$(document).on("keypress", ":input:not(textarea)", function(event) {
		return event.keyCode != 13;
	});

	/*Mask phone Number Digits*/
	/*$("#leagueContactNo").mask("999-999-999-9?999999");*/
	$("#employeePhoneNo").mask("(999) 999 - 9999");

	@if(Session::has('successMessage'))
	notify('{{  Session::get('successMessage') }}','blackgloss');
	{{ Session::forget('successMessage') }}
	@endif
</script>
@stop