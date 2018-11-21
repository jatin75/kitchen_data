@extends('layouts/main') @section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}"/>
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}"/>
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"/>
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" /> {{--
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/switchery/dist/switchery.min.css')}}" /> --}}
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('assets/css/pages/addjob.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/Magnific-Popup-master/dist/magnific-popup.css')}}" />
@stop
@section('content')
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
								<a class="previous-href btn btn-circle" href="{{route('activejobs')}}" title="Previous">
									<i class="ti-arrow-left"></i>
								</a>
							</div>
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
										<form id="formAddJob" method="post" enctype="multipart/form-data">
											{{ csrf_field() }} {{--
											<input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$accountDetail->email or ''}}"> --}} {{--
											<input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
											<input type="hidden" name="hiddenJobId" id="hiddenJobId" value="{{$jobDetails->job_id or ''}}">
											<input type="hidden" name="hiddenisclone" id="hiddenisclone" value="{{$cloneflag or ''}}">
											<input type="hidden" id="hiddenImages" name="hiddenImages" value="{{$jobDetails->job_images_url or ''}}">
											<input type="hidden" id="hiddenFiles" name="hiddenFiles" value="{{$jobDetails->job_files_url or ''}}">
											<input type="hidden" id="hiddenThumbnail" name="hiddenThumbnail" value="{{$jobDetails->image_thumbnails_url or ''}}">
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>JOB TITLE *</b>
														</label>
														<input type="text" name="jobTitle" id="jobTitle" value="{{$jobDetails->job_title or ''}}" class="form-control" placeholder="Job Title">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label"><b>JOB COMPANY NAME *</b></label>
														<select id="jobCompanyName" name="jobCompanyName" class="form-control select2">
															<option value="">-- Select Company --</option>
															@foreach($comapnyList as $company)
															<option value="{{ $company->company_id }}" @if(isset($jobDetails->company_id) && $jobDetails->company_id == $company->company_id) {{"selected='selected'"}} @endif>{{ $company->name }}</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group dropdown_select" style="overflow: visible!important;">
														<label class="control-label"><b>COMPANY CLIENTS *</b></label>
														<select data-size="5" id="comapnyClients" name="comapnyClients[]" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
															@if(isset($companyClientList) && sizeof($companyClientList) > 0)
															@foreach($companyClientList as $client)
															<option value="{{ $client->id }}"
																@if(isset($jobDetails->company_clients_id) && sizeof($jobDetails->company_clients_id) > 0)
																@foreach($jobDetails->company_clients_id as $single_client)
																@if($single_client == $client->id) {{"selected='selected'"}}@endif @endforeach @endif>{{ $client->client_name }}
															</option>
															@endforeach
															@endif
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>JOB STATUS</b>
														</label>
														<select id="jobType" name="jobType" class="form-control ">
															@foreach($jobTypeDetails as $jobType)
															<option value="{{ $jobType->job_status_id }}" @if(isset($jobDetails->job_status_id) && $jobDetails->job_status_id == $jobType->job_status_id) {{"selected='selected'"}}
															@elseif($jobType->job_status_id == 2)
															{{"selected='selected'"}}
															@endif>{{ $jobType->job_status_name }}</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>ADDRESS 1 *</b>
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
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label"><b>CITY</b></label>
															<input type="text" name="city" id="city" value="{{$jobDetails->city or ''}}" class="form-control" placeholder="Enter City">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label"><b>STATE</b></label>
															<input type="text" name="state" id="state" value="{{$jobDetails->state or ''}}" class="form-control" placeholder="Enter State">
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label"><b>ZIPCODE</b></label>
														<input type="text" placeholder="Enter Zipcode" name="zipcode" id="zipcode" value="{{$jobDetails->zipcode or ''}}" class="form-control">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>JOB ACTIVE/INACTIVE</b>
														</label>
														<select id="jobStatus" name="jobStatus" class="form-control ">
															<option value="1" @if(isset($jobDetails->is_active) && $jobDetails->is_active == '1') {{ "selected='selected'" }} @endif>ACTIVE</option>
															<option value="0" @if(isset($jobDetails->is_active) && $jobDetails->is_active == '0') {{ "selected='selected'" }} @endif>INACTIVE</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group dropdown_select" style="overflow: visible!important;">
														<label class="control-label"><b>SERVICE EMPLOYEES </b></label>
														<select data-size="5" id="serviceEmployee" name="serviceEmployee[]" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
															@foreach($employeeList as $employee)
															<option value="{{ $employee->id }}"
																@if(isset($jobDetails->service_employee_id) && sizeof($jobDetails->service_employee_id) > 0)
																@foreach($jobDetails->service_employee_id as $single_id)
																@if($single_id == $employee->id) {{"selected='selected'"}}@endif @endforeach @endif
																>{{ $employee->employee_name }}
															</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label"><b>SALES PERSON</b></label>
														<select id="salesEmployee" name="salesEmployee" class="form-control ">
															<option value="">-- Select Sales Person --</option>
															@foreach($salesemployeelist as $semployee)
															<option value="{{ $semployee->id }}"
																@if(sizeof($jobDetails->sales_employee_id) > 0)
																@foreach($jobDetails->sales_employee_id as $single_id)
																@if($single_id == $semployee->id) {{"selected='selected'"}}@endif @endforeach @endif
																>
																{{ $semployee->employee_name }}
															</option>
															@endforeach
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group dropdown_select" style="overflow: visible!important;">
														<label class="control-label"><b>WORKING EMPLOYEES *</b></label>
														<select data-size="5" id="workingEmployee" name="workingEmployee[]" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
															@foreach($employeeList as $employee)
															<option value="{{ $employee->id }}"
																@if(isset($jobDetails->working_employee_id) && sizeof($jobDetails->working_employee_id) > 0)
																@foreach($jobDetails->working_employee_id as $single_id)
																@if($single_id == $employee->id) {{"selected='selected'"}}@endif @endforeach @endif
																>{{ $employee->employee_name }}
															</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label"><b>JOB START DATE *</b></label>
														<input type="text" name="jobStartDate" id="jobStartDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
														maxlength="10" value="{{ $jobDetails->start_date or '' }}">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label"><b>EXPECTED COMPLETION DATE *</b>
														</label>
														<input type="text" name="jobEndDate" id="jobEndDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy" maxlength="10"
														value="{{ $jobDetails->end_date or '' }}">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label"><b>PLUMBING INSTALLATION DATE</b>
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
															<div class="col-md-4">
																<input type="text" name="deliveryDate" id="deliveryDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
																maxlength="10" value="{{ $jobDetails->delivery_date or '' }}">
															</div>
															<div class="col-md-8">
																<div class="input-group clockpicker " data-placement="top">
																	<input type="text" id="deliveryTime" name="deliveryTime" class="form-control" placeholder="hh:mm" value="{{ $jobDetails->delivery_time or '' }}">
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>JOB SUPER NAME *</b>
														</label>
														<input type="text" name="jobSuperName" id="jobSuperName" value="{{$jobDetails->super_name or ''}}" class="form-control" placeholder="Job Super Name">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>JOB SUPER PHONE NUMBER *</b>
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
														<input style="text-transform: lowercase;" type="email" name="contractorEmail" id="contractorEmail" value="{{$jobDetails->contractor_email or ''}}"
														class="form-control" placeholder="Enter Contractor Email">
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>CONTRACTOR PHONE NUMBER</b>
														</label>
														<input type="text" placeholder="(xxx) xxx-xxxx" name="contractorPhoneNumber" id="contractorPhoneNumber" value="{{$jobDetails->contractor_phone_number or ''}}"
														class="form-control">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>DELIVERY  INSTALLATION</b>
														</label>
														<select id="deliveryInstallationSelect" name="deliveryInstallationSelect" class="form-control ">
															<option value="1" @if(isset($jobDetails->is_select_delivery_installation) && $jobDetails->is_select_delivery_installation == '1') {{ "selected='selected'" }} @endif>Awaiting Material</option>

															<option value="2" @if(isset($jobDetails->is_select_delivery_installation) && $jobDetails->is_select_delivery_installation == '2') {{ "selected='selected'" }} @endif>Awaiting Approval</option>

															<option value="3" @if(isset($jobDetails->is_select_delivery_installation) && $jobDetails->is_select_delivery_installation == '3') {{ "selected='selected'" }} @endif>Received</option>

															<option value="4" @if(isset($jobDetails->is_select_delivery_installation) && $jobDetails->is_select_delivery_installation == '4') {{ "selected='selected'" }} @endif>Scheduled</option>
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>INSTALLATION</b>
														</label>
														<select id="installationSelect" name="installationSelect" class="form-control ">
															<option value="1" @if(isset($jobDetails->is_select_installation) && $jobDetails->is_select_installation == '1') {{ "selected='selected'" }} @endif>Awaiting Install</option>

															<option value="2" @if(isset($jobDetails->is_select_installation) && $jobDetails->is_select_installation == '2') {{ "selected='selected'" }} @endif>Awaiting Approval</option>

															<option value="3" @if(isset($jobDetails->is_select_installation) && $jobDetails->is_select_installation == '3') {{ "selected='selected'" }} @endif>Scheduled</option>
														</select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label class="control-label">
															<b>STONE INSTALLATION</b>
														</label>
														<select id="stoneInstallationSelect" name="stoneInstallationSelect" class="form-control ">
															<option value="">-- Select Stone Installation</option>
															<option value="1" @if(isset($jobDetails->is_select_stone_installation) && $jobDetails->is_select_stone_installation == '1') {{ "selected='selected'" }} @endif>Awaiting Approval</option>
															<option value="2" @if(isset($jobDetails->is_select_stone_installation) && $jobDetails->is_select_stone_installation == '2') {{ "selected='selected'" }} @endif>Scheduled</option>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<!-- delivery datetime -->
												<div class="col-md-4">
													<div class="hidden" id="deliveryDateDiv">
														<div class="form-group">
															<label class="control-label">
																<b>DELIVERY INSTALLATION DATE AND TIME *</b>
															</label>
															<div class="row">
																<div class="col-md-4">
																	<input type="text" name="deliveryInstallationDate" id="deliveryInstallationDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
																	maxlength="10" value="{{ $jobDetails->delivery_installation_date or '' }}">
																</div>
																<div class="col-md-8">
																	<div class="input-group clockpicker " data-placement="top">
																		<input type="text" id="deliveryInstallationTime" name="deliveryInstallationTime" class="form-control" placeholder="hh:mm" value="{{ $jobDetails->delivery_installation_time or '' }}">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- installation datetime -->
												<div class="col-md-4">
													<div class="hidden" id="installationDateDiv">
														<div class="form-group">
															<label class="control-label">
																<b>INSTALLATION DATE AND TIME *</b>
															</label>
															<div class="row">
																<div class="col-md-4">
																	<input type="text" name="installationDate" id="installationDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
																	maxlength="10" value="{{ $jobDetails->installation_date or '' }}">
																</div>
																<div class="col-md-8">
																	<div class="input-group clockpicker " data-placement="top">
																		<input type="text" id="installationTime" name="installationTime" class="form-control" placeholder="hh:mm" value="{{$jobDetails->installation_time or ''}}">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<!-- stone datetime -->
												<div class="col-md-4">
													<div class="hidden" id="stoneDateDiv">
														<div class="form-group">
															<label class="control-label">
																<b>STONE INSTALLATION DATE AND TIME</b>
															</label>
															<div class="row">
																<div class="col-md-4">
																	<input type="text" name="stoneInstallationDate" id="stoneInstallationDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
																	maxlength="10" value="{{ $jobDetails->stone_installation_date or '' }}">
																</div>
																<div class="col-md-8">
																	<div class="input-group clockpicker " data-placement="top">
																		<input type="text" id="stoneInstallationTime" name="stoneInstallationTime" class="form-control" placeholder="hh:mm" value="{{ $jobDetails->stone_installation_time or '' }}">
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<!-- delivery employee -->
												<div class="col-md-4">
													<div class="hidden" id="deliveryEmployeeDiv">
														<div class="form-group dropdown_select" style="overflow: visible!important;">
															<label class="control-label"><b>DELIVERY INSTALLATION EMPLOYEES *</b></label>
															<select data-size="5" id="deliveryInstallationEmployees" name="deliveryInstallationEmployees[]" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
																@foreach($deliveryEmployeeList as $delivery)
																<option value="{{ $delivery->id }}"
																	@if(isset($jobDetails->delivery_installation_employee_id) && sizeof($jobDetails->delivery_installation_employee_id) > 0)
																	@foreach($jobDetails->delivery_installation_employee_id as $single_id)
																	@if($single_id == $delivery->id) {{"selected='selected'"}}@endif @endforeach @endif>{{ $delivery->employee_name }}
																</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
												<!-- installation employee -->
												<div class="col-md-4">
													<div class="hidden" id="installationEmployeeDiv">
														<div class="form-group dropdown_select" style="overflow: visible!important;">
															<label class="control-label"><b>INSTALLATION EMPLOYEES *</b></label>
															<select data-size="5" id="installationEmployees" name="installationEmployees[]" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
																@foreach($installEmployeeList as $installer)
																<option value="{{ $installer->id }}"
																	@if(isset($jobDetails->installation_employee_id) && sizeof($jobDetails->installation_employee_id) > 0)
																	@foreach($jobDetails->installation_employee_id as $single_id)
																	@if($single_id == $installer->id) {{"selected='selected'"}}@endif @endforeach @endif>{{ $installer->employee_name }}
																</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
												<!-- stone employee -->
												<div class="col-md-4">
													<div class="hidden" id="stoneEmployeeDiv">
														<div class="form-group dropdown_select" style="overflow: visible!important;">
															<label class="control-label"><b>STONE INSTALLATION EMPLOYEES *</b></label>
															<select data-size="5" id="stoneInstallationEmployees" name="stoneInstallationEmployees[]" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
																@foreach($stoneEmployeeList as $stone)
																<option value="{{ $stone->id }}"
																	@if(isset($jobDetails->stone_installation_employee_id) && sizeof($jobDetails->stone_installation_employee_id) > 0)
																	@foreach($jobDetails->stone_installation_employee_id as $single_id)
																	@if($single_id == $stone->id) {{"selected='selected'"}}@endif @endforeach @endif>{{ $stone->employee_name }}
																</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 form-group">
													<label class="control-label"><b>UPLOAD FILE</b></label>
													<input type="file" id="addAttachment" name="addAttachment[]" multiple class="form-control" />
													<br/>
													<div class="card" style="min-height:132px">
														<div class="row m-t-5 m-b-5 m-l-5 m-r-5" id="image_preview"></div>
													</div>
												</div>
											</div>
											<div class="form-group text-left p-t-md">
												@if(!isset($jobDetails->job_id) || isset($cloneflag))
												<button type="submit" class="btn btn-success jobformsubmit">CREATE JOB</button>
												@endif @if(isset($jobDetails->job_id) && !isset($cloneflag))
												<button type="submit" class="btn btn-info jobformsubmit">UPDATE</button>
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
	<script src="{{ asset('scripts/jquery.maskedinput.min.js') }}"></script>
	<script type="text/javascript" src="{{asset('plugins/bower_components/custom-select/custom-select.min.js')}}"></script>
	<script src="{{ asset('scripts/company-location.js') }}"></script>
	<script type="text/javascript" src="{{asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('plugins/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('plugins/bower_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js')}}"></script>
	<script type="text/javascript">
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$(document).ready(function () {
			if (typeof ($('#jobContractorName').val()) != "undefined" && $('#jobContractorName').val() !== null)
				$('#jobContractorName').val($('#jobContractorName').val().toUpperCase());
			if (typeof ($('#clientLastName').val()) != "undefined" && $('#clientLastName').val() !== null)
				$('#clientLastName').val($('#clientLastName').val().toUpperCase());
			if (typeof ($('#contractorEmail').val()) != "undefined" && $('#contractorEmail').val() !== null)
				$('#contractorEmail').val($('#contractorEmail').val().toLowerCase());

			$("#jobCompanyName").change(function(){
				var company_id = $(this).val();
				$.ajax({
					url: '{{ route('getcompanyclients') }}',
					data: {company_id:company_id},
					type: 'post',
					dataType: 'json',
					success:function(response){
						if(response.key == 1)
						{
							var len = response.clients_data.length;
							$("#comapnyClients").empty();
							for( var i = 0; i<len; i++){
								var id = response.clients_data[i]['id'];
								var name = response.clients_data[i]['client_name'];
								$("#comapnyClients").append("<option value='"+id+"' >"+name+"</option>");
							}
						}
						else
						{
							$("#comapnyClients").empty();
							$('#formAddJob').bootstrapValidator('revalidateField', 'comapnyClients');
						}
						$('#comapnyClients').selectpicker('refresh');
					}
				});
			});
			$('#resetPermission').click(function () {
				location.reload();
			});

			/* Uploaded files */
			$('#image_preview').html("");
			var hidden_image=$("#hiddenImages").val();
			var hidden_files=$("#hiddenFiles").val();
			var hidden_thumbnail=$("#hiddenThumbnail").val();
			if(hidden_image != "")
			{
				var imageArray = hidden_image.split(",");
				var thumbImageArray = hidden_thumbnail.split(",");
				for(var i=0;i<imageArray.length;i++)
				{
					$('#image_preview').append('<div id="imageRemove_'+i+'" class="col-md-2 col-xs-2 col-sm-2"><i class="image-remove ti-close" onclick="removeFiles(\''+i+'\',\''+imageArray[i]+'\',\''+thumbImageArray[i]+'\');"></i><a class="image-popup-vertical-fit" href="'+imageArray[i]+'"><img class="screenshot m-t-5 m-r-5 m-b-5 m-l-5" src="'+thumbImageArray[i]+'" alt="No Preview"></a></div>');
				}
			}
			if(hidden_files != "")
			{
				var fileArray = hidden_files.split(",");
				for(var i = 0;i<fileArray.length;i++)
				{
					var extension = fileArray[i].split('.').pop().toLowerCase();
					if(extension == 'doc' || extension == 'docx') {
					var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file-word-o" style="font-size:107px"></i>';
					}else if(extension == 'xls' || extension == 'xlsx' || extension == 'csv') {
					var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file-excel-o" style="font-size:107px"></i>';
					}else if(extension == 'pdf') {
					var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file-pdf-o" style="font-size:107px"></i>';
					}else {
					var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file" style="font-size:107px"></i>';
					}
					$('#image_preview').append('<div id="imageRemove_'+'file'+i+'" class="col-md-2 col-xs-2 col-sm-2"><i class="image-remove ti-close" onclick="removeFiles(\''+'file'+i+'\',\''+fileArray[i]+'\');"></i>'+upload_img+'</div>');
				}
			}
			$('.image-popup-vertical-fit').magnificPopup({
				type: 'image',
				closeOnContentClick: true,
				mainClass: 'mfp-with-zoom',
				image: {
					verticalFit: true
				},
				zoom: {
					enabled: true,
					duration: 300,
					easing: 'ease-in-out',
					opener: function(openerElement) {
						return openerElement.is('img') ? openerElement : openerElement.find('img');
					}
				}
			});

			/*delivery status*/
			if($('#deliveryInstallationSelect').val() != "")
			{
				switch ($('#deliveryInstallationSelect').val()) {
					case '1':
					$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('deliveryInstallationDate', false)
					.enableFieldValidators('deliveryInstallationTime', false)
					.enableFieldValidators('deliveryInstallationEmployees[]', false);
					break;
					case '2':
					$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('deliveryInstallationDate', false)
					.enableFieldValidators('deliveryInstallationTime', false)
					.enableFieldValidators('deliveryInstallationEmployees[]', false);
					break;
					case '3':
					$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('deliveryInstallationDate', false)
					.enableFieldValidators('deliveryInstallationTime', false)
					.enableFieldValidators('deliveryInstallationEmployees[]', false);
					break;
					case '4':
					$('#deliveryDateDiv,#deliveryEmployeeDiv').removeClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('deliveryInstallationDate', true)
					.enableFieldValidators('deliveryInstallationTime', true)
					.enableFieldValidators('deliveryInstallationEmployees[]', true);
					break;
				}
			}
			else
			{
				$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
			}

			/*installation status*/
			if($('#installationSelect').val() != "")
			{
				switch ($('#installationSelect').val()) {
					case '1':
					$('#installationDateDiv,#installationEmployeeDiv').addClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('installationDate', false)
					.enableFieldValidators('installationTime', false)
					.enableFieldValidators('installationEmployees[]', false);
					break;
					case '2':
					$('#installationDateDiv,#installationEmployeeDiv').addClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('installationDate', false)
					.enableFieldValidators('installationTime', false)
					.enableFieldValidators('installationEmployees[]', false);
					break;
					case '3':
					$('#installationDateDiv,#installationEmployeeDiv').removeClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('installationDate', true)
					.enableFieldValidators('installationTime', true)
					.enableFieldValidators('installationEmployees[]', true);
					break;
				}
			}
			else
			{
				$('#installationDateDiv,#installationEmployeeDiv').addClass('hidden');
			}

			/*stone status*/
			if($('#stoneInstallationSelect').val() != "")
			{
				switch ($('#stoneInstallationSelect').val()) {
					case '1':
					$('#stoneDateDiv,#stoneEmployeeDiv').addClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('stoneInstallationDate', false)
					.enableFieldValidators('stoneInstallationTime', false)
					.enableFieldValidators('stoneInstallationEmployees[]', false);
					break;
					case '2':
					$('#stoneDateDiv,#stoneEmployeeDiv').removeClass('hidden');
					$('#formAddJob').data('bootstrapValidator')
					.enableFieldValidators('stoneInstallationDate', true)
					.enableFieldValidators('stoneInstallationTime', true)
					.enableFieldValidators('stoneInstallationEmployees[]', true);
					break;
				}
			}
			else
			{
				$('#stoneDateDiv,#stoneEmployeeDiv').addClass('hidden');
			}
		});

	$("#deliveryInstallationSelect").change(function(){
		if($('#deliveryInstallationSelect').val() != "")
		{
			switch ($('#deliveryInstallationSelect').val()) {
				case '1':
				$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('deliveryInstallationDate', false)
				.enableFieldValidators('deliveryInstallationTime', false)
				.enableFieldValidators('deliveryInstallationEmployees[]', false);
				break;
				case '2':
				$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('deliveryInstallationDate', false)
				.enableFieldValidators('deliveryInstallationTime', false)
				.enableFieldValidators('deliveryInstallationEmployees[]', false);
				break;
				case '3':
				$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('deliveryInstallationDate', false)
				.enableFieldValidators('deliveryInstallationTime', false)
				.enableFieldValidators('deliveryInstallationEmployees[]', false);
				break;
				case '4':
				$('#deliveryDateDiv,#deliveryEmployeeDiv').removeClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('deliveryInstallationDate', true)
				.enableFieldValidators('deliveryInstallationTime', true)
				.enableFieldValidators('deliveryInstallationEmployees[]', true);
				break;
			}
		}
		else
		{
			$('#deliveryDateDiv,#deliveryEmployeeDiv').addClass('hidden');
		}
	});

	$("#installationSelect").change(function(){
		if($('#installationSelect').val() != "")
		{
			switch ($('#installationSelect').val()) {
				case '1':
				$('#installationDateDiv,#installationEmployeeDiv').addClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('installationDate', false)
				.enableFieldValidators('installationTime', false)
				.enableFieldValidators('installationEmployees[]', false);
				break;
				case '2':
				$('#installationDateDiv,#installationEmployeeDiv').addClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('installationDate', false)
				.enableFieldValidators('installationTime', false)
				.enableFieldValidators('installationEmployees[]', false);
				break;
				case '3':
				$('#installationDateDiv,#installationEmployeeDiv').removeClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('installationDate', true)
				.enableFieldValidators('installationTime', true)
				.enableFieldValidators('installationEmployees[]', true);
				break;
			}
		}
		else
		{
			$('#installationDateDiv,#installationEmployeeDiv').addClass('hidden');
		}
	});

	$("#stoneInstallationSelect").change(function(){
		if($('#stoneInstallationSelect').val() != "")
		{
			switch ($('#stoneInstallationSelect').val()) {
				case '1':
				$('#stoneDateDiv,#stoneEmployeeDiv').addClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('stoneInstallationDate', false)
				.enableFieldValidators('stoneInstallationTime', false)
				.enableFieldValidators('stoneInstallationEmployees[]', false);
				break;
				case '2':
				$('#stoneDateDiv,#stoneEmployeeDiv').removeClass('hidden');
				$('#formAddJob').data('bootstrapValidator')
				.enableFieldValidators('stoneInstallationDate', true)
				.enableFieldValidators('stoneInstallationTime', true)
				.enableFieldValidators('stoneInstallationEmployees[]', true);
				break;
			}
		}
		else
		{
			$('#stoneDateDiv,#stoneEmployeeDiv').addClass('hidden');
		}
	});

	$("#deliveryInstallationTime").change(function(){
		$('#formAddJob').data('bootstrapValidator')
		.enableFieldValidators('deliveryInstallationTime', true);
		$('#formAddJob').bootstrapValidator('revalidateField', 'deliveryInstallationTime');
	});

	$("#deliveryInstallationEmployees").change(function(){
		$('#formAddJob').data('bootstrapValidator')
		.enableFieldValidators('deliveryInstallationEmployees[]', true);
		$('#formAddJob').bootstrapValidator('revalidateField', 'deliveryInstallationEmployees[]');
	});

	$("#installationTime").change(function(){
		$('#formAddJob').data('bootstrapValidator')
		.enableFieldValidators('installationTime', true);
		$('#formAddJob').bootstrapValidator('revalidateField', 'installationTime');
	});

	$("#installationEmployees").change(function(){
		$('#formAddJob').data('bootstrapValidator')
		.enableFieldValidators('installationEmployees[]', true);
		$('#formAddJob').bootstrapValidator('revalidateField', 'installationEmployees[]');
	});

	$("#stoneInstallationTime").change(function(){
		$('#formAddJob').data('bootstrapValidator')
		.enableFieldValidators('stoneInstallationTime', true);
		$('#formAddJob').bootstrapValidator('revalidateField', 'stoneInstallationTime');
	});

	$("#stoneInstallationEmployees").change(function(){
		$('#formAddJob').data('bootstrapValidator')
		.enableFieldValidators('stoneInstallationEmployees[]', true);
		$('#formAddJob').bootstrapValidator('revalidateField', 'stoneInstallationEmployees[]');
	});

	/*check revalidation*/
	$(".jobformsubmit").click(function(){
		var installationStatus = $("#installationSelect").val();
		var stoneInstallationStatus = $("#stoneInstallationSelect").val();
		var deliveryInstallationStatus = $("#deliveryInstallationSelect").val();
		var installStatus = (installationStatus == 3) ? true : false;
		var StoneInstallStatus = (stoneInstallationStatus == 2) ? true : false;
		var DeliveryInstallStatus = (deliveryInstallationStatus == 4) ? true : false;

		$('#formAddJob').data('bootstrapValidator')
		.enableFieldValidators('installationDate', installStatus)
		.enableFieldValidators('installationTime', installStatus)
		.enableFieldValidators('installationEmployees[]', installStatus)
		.enableFieldValidators('stoneInstallationDate', StoneInstallStatus)
		.enableFieldValidators('stoneInstallationTime', StoneInstallStatus)
		.enableFieldValidators('stoneInstallationEmployees[]', StoneInstallStatus)
		.enableFieldValidators('deliveryInstallationDate', DeliveryInstallStatus)
		.enableFieldValidators('deliveryInstallationTime', DeliveryInstallStatus)
		.enableFieldValidators('deliveryInstallationEmployees[]', DeliveryInstallStatus);
	});

	/* For select 2*/
	$(".select2").select2();
	$('.selectpicker').selectpicker();
	$('.clockpicker').clockpicker({
		twelvehour: true,
		autoclose: true,
	});

	/* Upload file */
	$("#addAttachment").change(function(){
		$('#image_preview').html("");
		var total_file=$("#addAttachment").get(0).files.length;
		for(var i=0;i<total_file;i++)
		{
			var file_name=$("#addAttachment").get(0).files[i].name;
			var extension = file_name.split('.').pop().toLowerCase();
    		if(extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
              var upload_img = '<img class="screenshot m-t-5 m-r-5 m-b-5 m-l-5" src="'+URL.createObjectURL(event.target.files[i])+'" alt="No Preview">';
            }else if(extension == 'doc' || extension == 'docx') {
              var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file-word-o" style="font-size:107px"></i>';
            }else if(extension == 'xls' || extension == 'xlsx' || extension == 'csv') {
              var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file-excel-o" style="font-size:107px"></i>';
            }else if(extension == 'pdf') {
              var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file-pdf-o" style="font-size:107px"></i>';
            }else {
              var upload_img = '<i class="screenshot p-t-6 p-l-14 m-t-5 m-r-5 m-b-5 m-l-5 fa fa-file" style="font-size:107px"></i>';
            }
			$('#image_preview').append('<div id="imageRemove_'+i+'" class="col-md-2">'+upload_img+'</div>');
		}
	  });

	$('#formAddJob').on('success.form.bv', function (e) {
		e.preventDefault();
		$('#loader').show();
		var formData = new FormData(this);
		$.ajax({
			url: '{{ route('storejob') }}',
			data:formData,
			type: 'post',
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			success: function (data) {
				if (data.key == 1) {
					location.href = '{{ route('activejobs') }}';
				} else if(data.key == 2) {
					$('#loader').hide();
					notify('Job has been updated Successfully.', 'blackgloss');
				} else {
					$('#loader').hide();
					notify('Something went wrong.', 'blackgloss');
				}
			}
		});
	});

	$('#jobContractorName').keyup(function () {
		this.value = this.value.toUpperCase();
	});

	$('#contractorEmail').keyup(function () {
		this.value = this.value.toLowerCase();
	});

	$('#jobStartDate,#jobEndDate,#plumbingInstallationDate,#deliveryDate,#deliveryTime,#deliveryInstallationTime,#deliveryInstallationDate,#installationDate,#installationTime,#stoneInstallationDate,#stoneInstallationTime').attr('readonly', true);

	/*prevent form to submit on enter*/
	$(document).on("keypress", ":input:not(textarea)", function (event) {
		return event.keyCode != 13;
	});

/*Mask phone Number Digits*/
$("#superPhoneNumber,#contractorPhoneNumber").mask("(999) 999 - 9999");

jQuery('#jobStartDate').datepicker({
	autoclose: true,
	todayHighlight: true,
}).on('changeDate', function() {
	$('#jobEndDate').datepicker('setStartDate', new Date($(this).val()))
});

/*Date picker*/
jQuery('#jobEndDate,#plumbingInstallationDate,#deliveryDate,#deliveryInstallationDate,#installationDate,#stoneInstallationDate').datepicker({
	autoclose: true,
	todayHighlight: true,
});

/*Stone installation date*/
$("#stoneInstallationDate").change(function(){
	$('#formAddJob').data('bootstrapValidator')
	.enableFieldValidators('stoneInstallationDate', true);
	$('#formAddJob').bootstrapValidator('revalidateField', 'stoneInstallationDate');
});
/*installation date*/
$("#installationDate").change(function(){
	$('#formAddJob').data('bootstrapValidator')
	.enableFieldValidators('installationDate', true);
	$('#formAddJob').bootstrapValidator('revalidateField', 'installationDate');
});
/*delivery installation date*/
$("#deliveryInstallationDate").change(function(){
	$('#formAddJob').data('bootstrapValidator')
	.enableFieldValidators('deliveryInstallationDate', true);
	$('#formAddJob').bootstrapValidator('revalidateField', 'deliveryInstallationDate');
});

	function removeFiles(image_id,image_link,image_thumb_link=null)
	{
		var job_id = $('#hiddenJobId').val();
		$('#imageRemove_'+image_id).remove();
		$.ajax({
			url:'{{ url('removefiles') }}',
			data:{
				job_id:job_id,
				image_link:image_link,
				image_thumb_link:image_thumb_link,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data == 1)
				{
					$('#loader').hide();
					notify('Screenshot has been removed successfully.','blackgloss');
				}
			}
		});

	}

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