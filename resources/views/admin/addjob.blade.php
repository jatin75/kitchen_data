@extends('layouts/main') @section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}"
/>
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}"
/>
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"
/>
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" /> {{--
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/switchery/dist/switchery.min.css')}}" /> --}}
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css')}}" />
<style type="text/css">
<style type="text/css">
	.nav-link.active {
		background: #4c5667 !important;
	}

	.nav-pills>li.active>a,
	.nav-pills>li.active>a:focus,
	.nav-pills>li.active>a:hover {
		background: #4c5667 !important;
		color: #ffffff !important;
	}
	.disabled-color {
		color: #90989c !important;
	}
	.bootstrap-select .dropdown-toggle:focus {
	outline: 0px auto -webkit-focus-ring-color!important;
	}
	.dropdown-toggle::after {
    display: inline-block;
    position: relative;
    right: 20px;
}
.previous-href{
	background: #e4e7ea !important;
	border: 1px solid #e4e7ea !important;
	padding: 7px 5px !important;
    font-size: 13px !important;
    padding-bottom: 8px !important;
	font-weight: 100 !important;
}
.btn-default {
    background: #ffffff !important;
    border: 1px solid #e4e7ea;
    padding: 10px 5px !important;
    font-size: 13px !important;
    padding-bottom: 8px !important;
	font-weight: 100 !important;
}
.btn-default:hover {
    background: #e4e7ea !important;
}
</style>
@stop @section('content')
<div class="container-fluid">
	<div class="row bg-title">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Jobs</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-wrapper collapse in" aria-expanded="true">
					<!--header-->
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-4 m-t-15 m-l-15">
							<a class="previous-href btn btn-circle" href="{{URL::previous()}}" title="Previous">
								<i class="ti-arrow-left"></i>
							</a>
						</div>
						{{--
						<div class="col-md-4 col-sm-4 col-xs-4 text-center">
							<h2 class="_600" id="pageName">Add Account</h2>
						</div> --}}
					</div>
					<!--/header-->
					<div class="panel-body">
						<ul class="nav nav-pills m-b-30">
							<li class="active nav-item">
								<a href="#tab1" class="nav-link" data-toggle="tab" aria-expanded="true">JOB PROFILE</a>
							</li>
						</ul>
						<div class="form-body form-material">
							<div class="tab-content br-n pn">
								<!--tab1-->
								<div id="tab1" class="tab-pane active">
									<form id="formAddJob" method="post">
										{{ csrf_field() }} {{--
										<input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$accountDetail->email or ''}}"> --}} {{--
										<input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
										<input type="hidden" name="hiddenJobId" id="hiddenJobId" value="{{$jobDetails->client_id or ''}}">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB TITLE</b>
													</label>
													<input type="text" name="jobTitle" id="jobTitle" value="{{$jobDetails->first_name or ''}}" class="form-control" placeholder="Job Title">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB STATUS</b>
													</label>
													<select id="jobStatus" name="jobStatus" class="form-control select2">
														<option value="">-- Select Job --</option>
														@foreach($jobList as $job)
														<option value="{{ $job->job_status_id }}" @if(isset($jobDetails->job_status_id) && $jobDetails->job_status_id == $job->job_status_id) {{"selected='selected'"}} @endif>{{ $job->job_status_name }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB ID</b>
													</label>
													<br>
													<span class="disabled-color" id="clientId">{{$jobDetails->job_id or '' }}</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>ADDRESS 1</b>
													</label>
													<input type="text" name="locationAddress" id="locationAddress" value="{{$jobDetails->address_1 or ''}}" class="form-control"
													    placeholder="Address line 1">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>ADDRESS 2</b>
													</label>
													<br>
													<input type="text" name="subAddress" id="subAddress" value="{{$jobDetails->address_2 or ''}}" class="form-control" placeholder="Address line 2">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>APARTMENT NUMBER</b>
													</label>
													<br>
													<input type="text" name="apartmentNo" id="apartmentNo" value="{{$jobDetails->apartment_number or ''}}" class="form-control"
													    placeholder="Apartment Number">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>CITY</b>
													</label>
													<input type="text" name="city" id="city" value="{{$jobDetails->city or ''}}" class="form-control" placeholder="Enter City">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>STATE</b>
													</label>
													<input type="text" name="state" id="state" value="{{$jobDetails->state or ''}}" class="form-control" placeholder="Enter State">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>ZIPCODE</b>
													</label>
													<input type="text" placeholder="Enter Zipcode" name="zipcode" id="zipcode" value="{{$jobDetails->zipcode or ''}}" class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB START DATE</b>
													</label>
													<input type="text" name="jobStartDate" id="jobStartDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
													    maxlength="10" value="{{ $jobDetails->start_date or '' }}">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>EXPECTED COMPLETION DATE</b>
													</label>
													<input type="text" name="jobEndDate" id="jobEndDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy" maxlength="10"
													    value="{{ $jobDetails->end_date or '' }}">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>PLUMBING INSTALLATION DATE</b>
													</label>
													<input type="text" name="plumbingInstallationDate" id="plumbingInstallationDate" class="form-control complex-colorpicker"
													    placeholder="mm/dd/yyyy" maxlength="10" value="{{ $jobDetails->plumbing_installation_date or '' }}">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>DELIVERY DATE AND TIME</b>
													</label>
													<div class="row">
														<div class="col-md-3">
														<input type="text" name="deliveryDate" id="deliveryDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
															maxlength="10" value="{{ $jobDetails->delivery_datetime or '' }}">
														</div>
														<div class="col-md-9">
																<div class="input-group clockpicker " data-placement="top">
																<input type="text" id="deliveryTime" name="deliveryTime" class="form-control" placeholder="HH:mm" value="@if(isset($jobDetails->delivery_datetime)){{date('H:i', strtotime($jobDetails->delivery_datetime)) }}@endif">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB SUPER NAME</b>
													</label>
													<input type="text" name="jobSuperName" id="jobSuperName" value="{{$jobDetails->super_name or ''}}" class="form-control" placeholder="Job Super Name">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB SUPER PHONE NUMBER</b>
													</label>
													<input type="text" placeholder="(xxx) xxx-xxxx" name="superPhoneNumber" id="superPhoneNumber" value="{{$jobDetails->super_phone_number or ''}}"
													    class="form-control">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB CONTRACTOR NAME</b>
													</label>
													<input type="text" style="text-transform:uppercase" name="jobContractorName" id="jobContractorName" value="{{$jobDetails->contractor_name or ''}}"
													    class="form-control" placeholder="Enter Job Contractor Name">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>CONTRACTOR EMAIL ADDRESS</b>
													</label>
													<input style="text-transform: lowercase;" type="email" name="contractorEmail" id="contractorEmail" value="{{$jobDetails->email or ''}}"
													    class="form-control" placeholder="Enter Contractor Email">
												</div>
											</div>
											<div class="col-md-4">
													<div class="form-group" style="overflow: visible!important;">
														<label class="control-label"><b>WORKING EMPLOYEES</b></label>
														<select data-size="5" id="workingEmployee" name="workingEmployee" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
															@foreach($employeeList as $employee)
															<option value="{{ $employee->id }}"
																{{-- @if(sizeof($jobDetails->working_employee_id) > 0)
																@foreach($jobDetails->working_employee_id as $single_job)
																@if($single_job == $employee->id) {{"selected='selected'"}}@endif @endforeach @endif --}}
																>{{ $employee->first_name.' '}}{{ $employee->last_name }}
															</option>
															@endforeach
														</select>
													</div>
												</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>JOB COMPANY NAME</b>
													</label>
													<select id="jobCompanyName" name="jobCompanyName" class="form-control select2">
														<option value="">-- Select Company --</option>
														@foreach($jobList as $job)
														<option value="{{ $job->job_status_id }}" @if(isset($jobDetails->job_status_id) && $jobDetails->job_status_id == $job->job_status_id) {{"selected='selected'"}} @endif>{{ $job->job_status_name }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group" style="overflow: visible!important;">
													<label class="control-label"><b>COMPANY CLIENTS</b></label>
													<select data-size="5" id="comapnyClients" name="comapnyClients" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
														@foreach($employeeList as $employee)
														<option value="{{ $employee->id }}"
															{{-- @if(sizeof($jobDetails->working_employee_id) > 0)
															@foreach($jobDetails->working_employee_id as $single_job)
															@if($single_job == $employee->id) {{"selected='selected'"}}@endif @endforeach @endif --}}
															>{{ $employee->first_name.' '}}{{ $employee->last_name }}
														</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<div class="row">
														<div class="col-md-6">
															<label class="control-label">
																<b>INSTALLATION</b>
															</label>
															<select id="jobCompanyName" name="jobCompanyName" class="form-control ">
																<option value="2">NO</option>
																@foreach($jobList as $job)
																<option value="1" @if(isset($jobDetails->job_status_id) && $jobDetails->job_status_id == $job->job_status_id) {{"selected='selected'"}} @endif>YES</option>
																@endforeach
															</select>
														</div>
														<div class="col-md-6">
															<label class="control-label">
																<b>STONE INSTALLATION</b>
															</label>
															<select id="jobCompanyName" name="jobCompanyName" class="form-control ">
																<option value="2">NO</option>
																@foreach($jobList as $job)
																<option value="1" @if(isset($jobDetails->job_status_id) && $jobDetails->job_status_id == $job->job_status_id) {{"selected='selected'"}} @endif>YES</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label">
														<b>INSTALLATION DATE AND TIME</b>
													</label>
													<div class="row">
														<div class="col-md-3">
														<input type="text" name="deliveryDate" id="deliveryDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
															maxlength="10" value="{{ $jobDetails->delivery_datetime or '' }}">
														</div>
														<div class="col-md-9">
																<div class="input-group clockpicker " data-placement="top">
																<input type="text" id="deliveryTime" name="deliveryTime" class="form-control" placeholder="HH:mm" value="@if(isset($jobDetails->delivery_datetime)){{date('H:i', strtotime($jobDetails->delivery_datetime)) }}@endif">
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group" style="overflow: visible!important;">
													<label class="control-label"><b>INSTALLATION EMPLOYEES</b></label>
													<select data-size="5" id="comapnyClients" name="comapnyClients" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
														@foreach($employeeList as $employee)
														<option value="{{ $employee->id }}"
															{{-- @if(sizeof($jobDetails->working_employee_id) > 0)
															@foreach($jobDetails->working_employee_id as $single_job)
															@if($single_job == $employee->id) {{"selected='selected'"}}@endif @endforeach @endif --}}
															>{{ $employee->first_name.' '}}{{ $employee->last_name }}
														</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>STONE INSTALLATION DATE AND TIME</b>
														</label>
														<div class="row">
															<div class="col-md-3">
															<input type="text" name="deliveryDate" id="deliveryDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
																maxlength="10" value="{{ $jobDetails->delivery_datetime or '' }}">
															</div>
															<div class="col-md-9">
																	<div class="input-group clockpicker " data-placement="top">
																	<input type="text" id="deliveryTime" name="deliveryTime" class="form-control" placeholder="HH:mm" value="@if(isset($jobDetails->delivery_datetime)){{date('H:i', strtotime($jobDetails->delivery_datetime)) }}@endif">
																</div>
															</div>
														</div>
													</div>
												</div>
											<div class="col-md-4">
												<div class="form-group" style="overflow: visible!important;">
													<label class="control-label"><b>STONE INSTALLATION EMPLOYEES</b></label>
													<select data-size="5" id="comapnyClients" name="comapnyClients" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
														@foreach($employeeList as $employee)
														<option value="{{ $employee->id }}"
															{{-- @if(sizeof($jobDetails->working_employee_id) > 0)
															@foreach($jobDetails->working_employee_id as $single_job)
															@if($single_job == $employee->id) {{"selected='selected'"}}@endif @endforeach @endif --}}
															>{{ $employee->first_name.' '}}{{ $employee->last_name }}
														</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<div class="form-group text-left p-t-md">
											@if(!isset($jobDetails->client_id))
											<button type="submit" class="btn btn-success">CREATE JOB</button>
											@endif @if(isset($jobDetails->client_id))
											<button type="submit" class="btn btn-info">UPDATE</button>
											@endif &nbsp; &nbsp;
											<button id="resetPermission" type="button" class="btn btn-danger">CANCEL</button>
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
@stop @section('pageSpecificJs')
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/dataTables.buttons.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/buttons.flash.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/jszip.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/pdfmake.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/vfs_fonts.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/buttons.html5.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/datatables/buttons.print.min.js')}}"></script>

<script type="text/javascript" src="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
{{--
<script type="text/javascript" src="{{asset('plugins/bower_components/switchery/dist/switchery.min.js')}}"></script> --}}
<script src="{{ asset('scripts/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/custom-select/custom-select.min.js')}}"></script>
<script src="{{ asset('scripts/company-location.js') }}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>
<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(document).ready(function () {
		$('#subscriberList').DataTable({
			dom: 'Bfrtip',
			buttons: [
				'csv', 'excel', 'pdf', 'print'
			],
		});
		if (typeof ($('#jobContractorName').val()) != "undefined" && $('#jobContractorName').val() !== null)
			$('#jobContractorName').val($('#jobContractorName').val().toUpperCase());
		if (typeof ($('#clientLastName').val()) != "undefined" && $('#clientLastName').val() !== null)
			$('#clientLastName').val($('#clientLastName').val().toUpperCase());
		if (typeof ($('#contractorEmail').val()) != "undefined" && $('#contractorEmail').val() !== null)
			$('#contractorEmail').val($('#contractorEmail').val().toLowerCase());

		$('#resetPermission').click(function () {
			location.reload();
		});
	});

	/* For select 2*/
	$(".select2").select2();
	$('.selectpicker').selectpicker();
	$('.clockpicker').clockpicker({ twelvehour: true, autoclose: true,

	});


	/* Switchery*/
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	$('.js-switch').each(function () {
		new Switchery($(this)[0], $(this).data());
	});

	/*$('#formAddClient').on('success.form.bv', function(e) {*/
	$('#formAddJob').on('success.form.bv', function (e) {
		e.preventDefault();
		$('#loader').show();
		var hidden_client_id = $('#hiddenClientId').val();
		var client_first_name = $('#clientFirstName').val();
		var client_last_name = $('#clientLastName').val();
		var client_email = $('#contractorEmail').val();
		var client_contactNo = $('#clientContactNo').val();
		var client_company = $('#clientCompany').val();
		var address_1 = $('#locationAddress').val();
		var address_2 = $('#subAddress').val();
		var city = $('#city').val();
		var state = $('#state').val();
		var zipcode = $('#zipcode').val();
		var contact_preference = $('#contactPreference').val();
		$.ajax({
			url: '{{ route('storejob') }}',
			data: {
				hidden_client_id: hidden_client_id,
				client_first_name: client_first_name,
				client_last_name: client_last_name,
				client_email: client_email,
				client_contactNo: client_contactNo,
				client_company: client_company,
				address_1: address_1,
				address_2: address_2,
				city: city,
				state: state,
				zipcode: zipcode,
				contact_preference: contact_preference
			},
			type: 'post',
			dataType: 'json',
			success: function (data) {
				if (data.key == 1) {
					location.href = '{{ route('showclients') }}';
				} else if (data.key == 2) {
					if (typeof (data.name) != "undefined" && data.name !== null) {
						$('#sessionName').html(data.name);
					}
					$('#loader').hide();
					notify('Client has been updated successfully', 'blackgloss');
				} else if (data.key == 3) {
					$('#loader').hide();
					notify('Entered email address already exists.', 'blackgloss');
				}
			}
		});
	});

	$("#formAccountSetting").on('success.form.bv', function (e) {
		e.preventDefault();
		$("#loader").show();
		var current_password = $("#currentPassword").val();
		var new_password = $('#newPassword').val();
		var retype_password = $('#retypePassword').val();
		var hidden_email = $('#hiddenMail').val();
		if (new_password != retype_password) {
			$("#loader").hide();
			notify('New password and  Retype password is not match. Please try again.', 'blackgloss');
			return;
		}
		$.ajax({
			url: '{{ route('changepassword') }}',
			data: {
				current_password: current_password,
				new_password: new_password,
				hidden_email: hidden_email,
			},
			type: 'post',
			success: function (data) {
				if (data == 1) {
					$('#loader').hide();
					notify('Your password has been reset.', 'blackgloss');
				} else if (data == 2) {
					$('#loader').hide();
					notify('Current password is invalid. Please try again.', 'blackgloss');
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

	$('#jobContractorName').keyup(function () {
		this.value = this.value.toUpperCase();
	});

	$('#contractorEmail').keyup(function () {
		this.value = this.value.toLowerCase();
	});

	// hide previous button
	$(document).ready(function () {
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
	$(document).on("keypress", ":input:not(textarea)", function (event) {
		return event.keyCode != 13;
	});

	/*Mask phone Number Digits*/
	/*$("#leagueContactNo").mask("999-999-999-9?999999");*/
	$("#superPhoneNumber").mask("(999) 999 - 9999");

	/*Date picker*/
	jQuery('#jobStartDate,#jobEndDate,#plumbingInstallationDate,#deliveryDate').datepicker({
		autoclose: true,
		todayHighlight: true,
		startDate: new Date()
	});

	function changePermission(id) {
		var value = $('#access_' + id).val();
		if (value == 1) {
			$('#access_' + id).val(0);
		} else {
			$('#access_' + id).val(1);
		}
	}
</script>
@stop