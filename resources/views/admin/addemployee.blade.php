@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
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
			<h4 class="page-title">Employee</h4>
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
							<li class="active nav-item"> <a href="#tab1" class="nav-link" data-toggle="tab" aria-expanded="true">EMPLOYEE PROFILE</a> </li>
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
													<label class="control-label"><b>PHONE NUMBER</b></label>
													<input type="text" placeholder="(xxx) xxx-xxxx" name="employeePhoneNo" id="employeePhoneNo" value="{{$employeeDetail->phone_number or ''}}" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMAIL ADDRESS</b></label>
													<input style="text-transform: lowercase;" type="email" name="employeeEmail" id="employeeEmail" value="{{$employeeDetail->email or ''}}" class="form-control"  placeholder="Enter your email">
												</div>
											</div>
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
										</div>
									</div>
									<div class="form-group text-left p-t-md">
										@if(!isset($employeeDetail->id))
										<button type="submit" class="btn btn-success">Add</button>
										@endif
										@if(isset($employeeDetail->id))
										<button type="submit" class="btn btn-info">UPDATE</button>
										@endif
									</div>
								</form>
							</div>
							<!--/.tab1-->
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
<script type="text/javascript" src="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
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
	});

	/* For select 2*/
	$(".select2").select2();

	$('#formAddEmployee').on('success.form.bv', function(e) {
		e.preventDefault();
		$('#loader').show();
		var hidden_employeeId = $('#hiddenEmployeeId').val();
		var employee_firstName = $('#employeeFirstName').val();
		var employee_lastName = $('#employeeLastName').val();
		var employee_contactNo = $('#employeePhoneNo').val();
		var employee_email = $('#employeeEmail').val();
		var employee_type = $('#employeeType').val();

		$.ajax({
			url:'{{ route('storeemployee') }}',
			data:{
				hidden_employeeId:hidden_employeeId,
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
				else if(data.key == 2)
				{
					$('#loader').hide();
					notify('Entered email address already exists.','blackgloss');
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