@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}"
/>
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/owl.carousel/owl.carousel.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('assets/css/pages/jobs.css')}}" />
@stop
@section('content')
<div class="container-fluid">
	<input type="hidden" id="formatedDate" name="formatedDate" value="{{ date('Y_m_d') }}">
	<div class="row bg-title">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Jobs > Active</h4>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="white-box">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<h3 class="box-title m-b-0 pull-left">All JOBS</h3>
						<a href="{{route('addjob')}}" class="btn btn-success btn-rounded waves-effect waves-light pull-right m-b-15 m-r-15"><span>Add Job</span> <i class="fa fa-plus m-l-5"></i></a>
					</div>
				</div>
				<div class="nav_toggle user-profile" style="padding-top: 0;padding-bottom: 20px;text-align: left">
					<div class="dropdown user-pro-body" style="margin: 0px  !important">
						<a href="#" class="u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i style="border:  1px solid #d1d1d1; padding:  6px; border-radius: 3px;cursor: pointer;" class="ti-menu"></i></a>
						<ul class="dropdown-menu animated flipInY" style="margin-left: 0;top:30px;padding: 0;">
							<li><a class="toolbar_dropdownbtn toolbarmenu_active" href="javascript:void(0)" onclick="getJobDetailsList(0)" data-id="0"> All </a></li>
							@foreach($jobTypeDetails as $jobType)
							<li><a class="toolbar_dropdownbtn" href="javascript:void(0)" onclick="getJobDetailsList({{ $jobType->job_status_id }})" data-id="{{ $jobType->job_status_id }}"> {{ strtoupper($jobType->job_status_name) }} </a></li>
							@endforeach
						</ul>
					</div>
				</div>
				<div class="row button-box nav_toolbar_menu m-b-30">
					<div class=""><button class="btn toolbar_btn toolbaractive" data-id="0" onclick="getJobDetailsList(0)">All</button></div>
					@foreach($jobTypeDetails as $jobType)
					<div class="">
						<button class="btn toolbar_btn" data-id="{{ $jobType->job_status_id }}" onclick="getJobDetailsList({{ $jobType->job_status_id }})">{{ strtoupper($jobType->job_status_name) }}</button>
					</div>
					@endforeach
				</div>
				<div class="table-responsive jobDetailList">
					<table id="jobList" class="display nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center">Actions</th>
								<th>Job Name</th>
								<th>Job Status</th>
								<th>Employee</th>
								<th>Address</th>
								<th>Start Date</th>
								<th>Expected Completion Date</th>
							</tr>
						</thead>
						<tbody id="jobListTbody">
							@foreach($jobDetails as $job)
							<tr class="changestatus_{{ $job->job_id }}">
								<td class="text-center">
									<span data-toggle="" data-target="#jobDetailModel">
										<a data-toggle="tooltip" data-placement="top" title="View Job" class="btn btn-success btn-circle view-job" data-id="{{ $job->job_id }}">
											<i class="ti-eye"></i>
										</a>
									</span>
									<a data-toggle="tooltip" data-placement="top" title="Edit Job" class="btn btn-info btn-circle" href="{{route('editjob',['job_id' => $job->job_id])}}">
										<i class="ti-pencil-alt"></i>
									</a>

									<a data-toggle="tooltip" data-placement="top" title="Clone Job" class="btn btn-dribbble btn-circle" href="{{route('clonejob',['job_id' => $job->job_id])}}">
										<i class="ti-layers"></i>
									</a>

									<span data-toggle="modal" data-target="#jobNotesModel">
										<a data-toggle="tooltip" data-placement="top" title="Add Job Notes" class="btn btn-warning btn-circle add-job-note" data-id="{{ $job->job_id }}">
											<i class="ti-plus"></i>
										</a>
									</span>
									<span data-toggle="" data-target="#Auditmodel">
										<a data-toggle="tooltip" data-placement="top" title="View Audit" class="btn btn-primary btn-circle view-audit" data-id="{{ $job->job_id }}">
											<i class="ti-receipt"></i>
										</a>
									</span>
									<span data-toggle="" data-target="#jobImageModel">
										<a data-toggle="tooltip" data-placement="top" title="View Image" class="btn btn-dribbble btn-circle view-images" data-id="{{ $job->job_id }}"><i class="ti-image"></i></a>
									</span>
									<a class="btn btn-danger btn-circle" onclick="return confirm('Are you sure you want to inactivate this job?');" href="{{route('deactivatejob',['job_id' => $job->job_id])}}" data-toggle="tooltip" data-placement="top" title="Inactivate Job"><i class="ti-lock"></i></a>
								</td>
								<td>{{$job->job_title}}</td>
								<td>
									<select class="form-control select2 jobType" name="jobType" id="jobType_{{$job->job_id}}" placeholder="Select your job type" data-id="{{$job->job_id}}">
										@foreach($jobTypeDetails as $jobType)
										<option value="{{ $jobType->job_status_id }}" @if(isset($job->job_status_id) && $job->job_status_id == $jobType->job_status_id) {{"selected='selected'"}} @endif> {{ $jobType->job_status_name }}</option>
										@endforeach
									</select>
								</td>
								<td><div class="word-wrap">{{$job->employee_name}}</div></td>
								<td><div class="word-wrap">{{$job->address}}</div></td>
								<td>{{ date('m/d/Y',strtotime($job->start_date))}}</td>
								<td>{{ date('m/d/Y',strtotime($job->end_date))}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!--jobNotes model-->
	<div class="modal fade" id="jobNotesModel" tabindex="-1" data-backdrop="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="exampleModalLabel1">Add&nbsp;Note</h4>
				</div>
				<div class="modal-body">
					<div class="form-body">
						<form method="POST" id="formAddNote">
							{{ csrf_field() }}
							<input type="hidden" id="hiddenJobId" name="hiddenJobId">
							<input type="hidden" id="hiddenJobStatus" name="hiddenJobStatus">
							<div class="row m-t-10">
								<div class="row col-md-12">
									<div class="col-md-12">
										<div class="form-group">
											<label class="col-md-12">ADD JOB NOTE</label>
											<div class="col-md-12">
												<textarea class="form-control" rows="5" name="jobNote" id="jobNote" placeholder="Enter notes . . ."></textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer form-group">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>&nbsp;
								<button type="submit" id="jobNoteSubmit" class="btn btn-success">Add</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--/.jobNotes model-->
	<!--Audit model-->
	<div class="modal fade" tabindex="-1"  id="Auditmodel" aria-hidden="true" data-backdrop="true" style="display: none; z-index:100000;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Job Audit</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive" id="auditData"></div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!--/.Audit model-->
<!--jobDetail model-->
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="jobDetailModel" style="display: none; z-index:100000;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Job Details</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB TITLE</label>
						<br><span id="jobTitle"></span>
					</div>
					<!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB ID</label>
						<br><span id="jobId"></span>
					</div> -->
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB COMPANY NAME</label>
						<br><span id="jobCompanyName"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">COMPANY CLIENTS</label>
						<br><span id="comapnyClients"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB STATUS</label>
						<br><span id="jobType"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">ADDRESS 1</label>
						<br><span id="address1"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">ADDRESS 2</label>
						<br><span id="address2"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">APARTMENT NUMBER</label>
						<br><span id="apartmentNo"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20 p-l-0">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<label class="control-label">CITY</label>
							<br><span id="city"></span>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
							<label class="control-label">STATE</label>
							<br><span id="state"></span>
						</div>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">ZIPCODE</label>
						<br><span id="zipcode"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB ACTIVE/INACTIVE</label>
						<br><span id="jobStatus"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">SERVICE EMPLOYEES</label>
						<br><span id="serviceEmployee"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">SALES PERSON</label>
						<br><span id="salesEmployee"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">WORKING EMPLOYEES</label>
						<br><span id="workingEmployee"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB START DATE</label>
						<br><span id="jobStartDate"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">EXPECTED COMPLETION DATE</label>
						<br><span id="jobEndDate"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">PLUMBING INSTALLATION DATE</label>
						<br><span id="plumbingInstallationDate"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">DELIVERY DATE AND TIME</label>
						<br><span id="deliveryDateTime"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB SUPER NAME</label>
						<br><span id="jobSuperName"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB SUPER PHONE NUMBER</label>
						<br><span id="superPhoneNumber"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB CONTRACTOR NAME</label>
						<br><span id="jobContractorName"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">CONTRACTOR EMAIL ADDRESS</label>
						<br><span id="contractorEmail"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">CONTRACTOR PHONE NUMBER</label>
						<br><span id="contractorPhoneNumber"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">DELIVERY INSTALLATION</label>
						<br><span id="deliveryInstallationSelect"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">INSTALLATION</label>
						<br><span id="installationSelect"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">STONE INSTALLATION</label>
						<br><span id="stoneInstallationSelect"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">DELIVERY INSTALLATION DATE & TIME</label>
						<br><span id="deliveryInstallationDateTime"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">INSTALLATION DATE & TIME</label>
						<br><span id="installationDateTime"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">STONE INSTALLATION DATE & TIME</label>
						<br><span id="stoneInstallationDateTime"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">DELIVERY INSTALLATION EMPLOYEES</label>
						<br><span id="deliveryInstallationEmployees"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">INSTALLATION EMPLOYEES</label>
						<br><span id="installationEmployees"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">STONE INSTALLATION EMPLOYEES</label>
						<br><span id="stoneInstallationEmployees"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="scrollit p-l-10">
							<div class="text-center p-b-10"><span><b>Job Notes</b></span></div>
							<div class="row p-b-10">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
									<span><b>Job Note</b></span>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
									<span><b>Updated By</b></span>
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
									<span><b>Date</b></span>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
									<span><b>Action</b></span>
								</div>
							</div>
							<div id="notesData">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/.jobDetail model-->
<!--Job status change event model-->
<div class="modal fade" id="statusWiseJobModel" tabindex="-1" data-backdrop="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title addDeliveryDateTime" id="exampleModalLabel1">Add&nbsp;Delivery Details</h4>
				<h4 class="modal-title addInstallingDateTime" id="exampleModalLabel1">Add&nbsp;Installation Details</h4>
				<h4 class="modal-title addStoneInstallingDateTime" id="exampleModalLabel1">Add&nbsp;Stone Installation Details</h4>
			</div>
			<div class="modal-body">
				<div class="form-body form-material">
					<input type="hidden" id="hiddenChangeJobId" name="hiddenChangeJobId">
					<input type="hidden" id="hiddenChangeJobStatus" name="hiddenChangeJobStatus">
					<input type="hidden" id="hiddenChangeJobActiveStatus" name="hiddenChangeJobActiveStatus">
					<form method="POST" id="formAddDeliveryDateTime" class="addDeliveryDateTime">
						{{ csrf_field() }}
						<div class="row m-t-10">
							<div class="row col-md-12">
								<div class="col-md-12">
									<div class="form-group">
										<label class="col-md-12">DELIVERY DATE AND TIME </label>
										<div class="">
											<div class="col-md-4">
												<input type="text" name="deliveryDate" id="deliveryDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
												maxlength="10" value="">
											</div>
											<div class="col-md-4">
												<div class="input-group clockpicker " data-placement="top">
													<input type="text" id="deliveryTime" name="deliveryTime" class="form-control" placeholder="hh:mm" value="">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer form-group">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>&nbsp;
							<button type="submit" class="btn btn-success">Add</button>
						</div>
					</form>
					<form method="POST" id="formAddInstallingDateTime" class="addInstallingDateTime">
						{{ csrf_field() }}
						<div class="row">
							<div class="row col-md-12">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">INSTALLATION DATE AND TIME </label>
										<div class="">
											<div class="col-md-4">
												<input type="text" name="installationDate" id="installationDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
												maxlength="10" value="">
											</div>
											<div class="col-md-4">
												<div class="input-group clockpicker " data-placement="top">
													<input type="text" id="installationTime" name="installationTime" class="form-control" placeholder="hh:mm" value="">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row col-md-12">
								<div class="col-md-8">
									<div class="form-group" style="overflow: visible!important;">
										<label class="control-label"><b>INSTALLATION EMPLOYEES</b></label>
										<select data-size="5" id="selectInstallationEmployees" name="selectInstallationEmployees" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
											@foreach($installEmployeeList as $installer)
											<option value="{{ $installer->id }}">{{ $installer->employee_name }}
											</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer form-group">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>&nbsp;
							<button type="submit" class="btn btn-success">Add</button>
						</div>
					</form>
					<form method="POST" id="formAddStoneInstallingDateTime" class="addStoneInstallingDateTime">
						{{ csrf_field() }}
						<div class="row">
							<div class="row col-md-12">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">STONE INSTALLATION DATE AND TIME </label>
										<div class="">
											<div class="col-md-4">
												<input type="text" name="stoneInstallationDate" id="stoneInstallationDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
												maxlength="10" value="">
											</div>
											<div class="col-md-4">
												<div class="input-group clockpicker " data-placement="top">
													<input type="text" id="stoneInstallationTime" name="stoneInstallationTime" class="form-control" placeholder="hh:mm" value="">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row col-md-12">
								<div class="col-md-8">
									<div class="form-group" style="overflow: visible!important;">
										<label class="control-label"><b>STONE INSTALLATION EMPLOYEES</b></label>
										<select data-size="5" id="selectStoneInstallationEmployees" name="selectStoneInstallationEmployees" class="form-control selectpicker" multiple data-actions-box="true"  data-style="form-control">
											@foreach($stoneEmployeeList as $stone)
											<option value="{{ $stone->id }}">{{ $stone->employee_name }}
											</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer form-group">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>&nbsp;
							<button type="submit" class="btn btn-success">Add</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/.Job status change event model-->
<!--jobImage model-->
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="jobImageModel" style="display: none; z-index:100000;">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Job Images</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<!-- START carousel-->
						<div id="slide-id" data-ride="carousel" class="carousel slide">
							<ol class="carousel-indicators"></ol>
							<div class="carousel-inner"></div>
							<a href="#slide-id" role="button" data-slide="prev" class="left carousel-control"> <span aria-hidden="true" class="fa fa-angle-left"></span> <span class="sr-only">Previous</span> </a>
							<a href="#slide-id" role="button" data-slide="next" class="right carousel-control"> <span aria-hidden="true" class="fa fa-angle-right"></span> <span class="sr-only">Next</span> </a>
						</div>
						<!-- END carousel-->
					</div>
				</div>
				<div class="modal-footer p-r-0">
					<button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/.jobImage model-->
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
<script type="text/javascript" src="{{asset('plugins/bower_components/custom-select/custom-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" src="{{asset('plugins/bower_components/owl.carousel/owl.carousel.min.js')}}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		var date = $('#formatedDate').val();
		var value = 'Kitchen_job_' + date;
		$('#jobList').DataTable({
			dom: 'Bfrtip',
			buttons: [
			{
				extend: 'csv',
				title: value,
				exportOptions: {
					columns: [ 1,2,3,4,5,6 ],
					format: {
						body: function(data) {
							return data;
						}
					},
				},
			},
			{
				extend: 'excel',
				title: value,
				exportOptions: {
					columns: [ 1,2,3,4,5,6 ],
					format: {
						body: function(data) {
							return data;
						}
					},
				},
			},
			{
				extend: 'pdf',
				pageSize: 'LEGAL',
				orientation: 'landscape',
				title: value,
				exportOptions: {
					columns: [ 1,2,3,4,5,6 ],
					format: {
						body: function(data) {
							return data;
						}
					},
				},
			},
			{
				extend: 'print',
				title: value,
				exportOptions: {
					columns: [ 1,2,3,4,5,6 ],
					format: {
						body: function(data) {
							return data;
						}
					},
				},
			},
			],
		});
	});

/*get job detail list*/
function getJobDetailsList(jobStatusId){
	$('#jobListTbody').html('<tr id="jobchart" class="box" style="padding: inherit;"><td colspan="7" style="text-align: center;margin: 10px;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:24px"></i></td></tr>');
	$.ajax({
		url:'{{ route('showfilterwisejob') }}',
		data:{
			jobStatusId:jobStatusId,
		},
		type:'post',
		dataType:'json',
		success: function(data)
		{
			$('#jobList').DataTable().destroy();
			$('#jobListTbody').html(data.html);
			var date = $('#formatedDate').val();
			var value = 'Kitchen_job_' + date;
			$('#jobList').DataTable({
				dom: 'Bfrtip',
				buttons: [
				{
					extend: 'csv',
					title: value,
					exportOptions: {
						columns: [ 1,2,3,4,5,6 ],
						format: {
							body: function( data, row, col, node ) {
								return data;
							}
						},
					},
				},
				{
					extend: 'excel',
					title: value,
					exportOptions: {
						columns: [ 1,2,3,4,5,6 ],
						format: {
							body: function( data, row, col, node ) {
								return data;
							}
						},
					},
				},
				{
					extend: 'pdf',
					pageSize: 'LEGAL',
					title: value,
					exportOptions: {
						columns: [ 1,2,3,4,5,6],
						format: {
							body: function( data, row, col, node ) {
								return data;
							}
						},
					},
				},
				{
					extend: 'print',
					title: value,
					exportOptions: {
						columns: [ 1,2,3,4,5,6 ],
						format: {
							body: function( data, row, col, node ) {
								return data;
							}
						},
					},
				}
				],
			});
			/* For select 2*/
			$(".select2").select2();
			/*tooltip*/
			$('[data-toggle="tooltip"]').tooltip();
		}
	});
}

/*set job id on models*/
$(document).on('click',".add-job-note",function(){
	var jobId = $(this).attr('data-id');
	$("#jobNoteSubmit").prop("disabled",false);
	$('#hiddenJobId').val(jobId);
	$('#jobNote').val('');
	$('#jobNoteSubmit').html('Add');
	$('#hiddenJobStatus').val(1);
});

/*view image model*/
$(document).on('click',".view-images",function(){
	var jobId = $(this).attr('data-id');
	$('#loader').show();
	$.ajax({
		url:'{{ route('getjobimages') }}',
		data:{
			jobId:jobId,
		},
		type:'post',
		dataType:'json',
		success: function(response)
		{
			if(response.key == 1)
			{
				$('.carousel-indicators').html(response.html1);
				$('.carousel-inner').html(response.html2);
				$('#loader').hide();
				$('#jobImageModel').modal('show');
			}
			else
			{
				$('#loader').hide();
				notify('Job images not found.','blackgloss');
			}
		}
	});
});


/*change job status*/
$(document).on('change','.jobType',function(){
	var jobStatusId = $(this).val();
	var jobId = $(this).attr('data-id');
	var date = ''; var time = ''; var employee = '';

	if (window.matchMedia('(max-width: 767px)').matches) {
		var activeJobStatus = $(".toolbarmenu_active").attr("data-id");
	} else {
		var activeJobStatus = $(".toolbaractive").attr("data-id");
	}
	$("#hiddenChangeJobId").val(jobId);
	$("#hiddenChangeJobStatus").val(jobStatusId);
	$("#hiddenChangeJobActiveStatus").val(activeJobStatus);
	if(jobStatusId == 5 || jobStatusId == 6 || jobStatusId == 7) {
		$("#loader").show();
		$.ajax({
			url:'{{ route('editjobdatetimemodel') }}',
			data:{jobId:jobId},
			type: 'post',
			dataType: 'json',
			success:function(data){
				$('#loader').hide();
				if(data.key == 1) {
					var jobstatus = data.job_detail.job_status_id;
					$("#jobType_"+jobId).find('option').removeAttr("selected");
					$("#jobType_"+jobId).select2("val", jobstatus);
					$('#deliveryDate').val(data.job_detail.delivery_date);
					$('#deliveryTime').val(data.job_detail.delivery_time);
					$('#installationDate').val(data.job_detail.installation_date);
					$('#installationTime').val(data.job_detail.installation_time);
					$('#selectInstallationEmployees').selectpicker('val', data.job_detail.installation_employee_id);
					$('#stoneInstallationDate').val(data.job_detail.stone_installation_date);
					$('#stoneInstallationTime').val(data.job_detail.stone_installation_time);
					$('#selectStoneInstallationEmployees').selectpicker('val', data.job_detail.stone_installation_employee_id);
					$('#statusWiseJobModel').modal('show');
				}
			}
		});
}

if(jobStatusId == 5) {
	$('.addInstallingDateTime').hide();
	$('.addDeliveryDateTime').show();
	$('.addStoneInstallingDateTime').hide();

}else if(jobStatusId == 6) {
	$('.addInstallingDateTime').show();
	$('.addDeliveryDateTime').hide();
	$('.addStoneInstallingDateTime').hide();

}else if(jobStatusId == 7) {
	$('.addStoneInstallingDateTime').show();
	$('.addDeliveryDateTime').hide();
	$('.addInstallingDateTime').hide();

}
else {
	changestatuswisejob(jobStatusId,jobId,activeJobStatus,date,time,employee);
}
});

function changestatuswisejob(jobStatusId,jobId,activeJobStatus,date,time,employee) {
	$("#loader").show();
	$.ajax({
		url:'{{ route('changejobstatus') }}',
		data:{jobStatusId:jobStatusId,jobId:jobId,date:date,time:time,employee:employee},
		type: 'post',
		dataType: 'json',
		success:function(data){
			$('#loader').hide();
			if(activeJobStatus != 0) {
				$('.changestatus_'+jobId).fadeOut(300, function(){
					var table = $('#jobList').DataTable();
					table.row('.changestatus_'+jobId).remove().draw(false);
				});
			}
			notify('Job Status has been Changed Successfully.','blackgloss');
		}
	});
}

$('#formAddDeliveryDateTime').on('success.form.bv', function(e) {
	e.preventDefault();
	$('#statusWiseJobModel').modal('hide');
	var jobId = $("#hiddenChangeJobId").val();
	var jobStatusId = $("#hiddenChangeJobStatus").val();
	var activeJobStatus = $("#hiddenChangeJobActiveStatus").val();
	var date = $("#deliveryDate").val();
	var time = $("#deliveryTime").val();
	var employee = '';
	$("#jobType_"+jobId).select2("val", jobStatusId);
	if(jobStatusId == 5 && date != '' && time != '') {
		changestatuswisejob(jobStatusId,jobId,activeJobStatus,date,time,employee);
	}
});

$('#formAddInstallingDateTime').on('success.form.bv', function(e) {
	e.preventDefault();
	$('#statusWiseJobModel').modal('hide');
	var jobId = $("#hiddenChangeJobId").val();
	var jobStatusId = $("#hiddenChangeJobStatus").val();
	var activeJobStatus = $("#hiddenChangeJobActiveStatus").val();
	var date = $("#installationDate").val();
	var time = $("#installationTime").val();
	var employee = $("#selectInstallationEmployees").val();
	$("#jobType_"+jobId).select2("val", jobStatusId);
	if(jobStatusId == 6 && date != '' && time != '' && employee != '') {
		changestatuswisejob(jobStatusId,jobId,activeJobStatus,date,time,employee);
	}
});

$('#formAddStoneInstallingDateTime').on('success.form.bv', function(e) {
	e.preventDefault();
	$('#statusWiseJobModel').modal('hide');
	var jobId = $("#hiddenChangeJobId").val();
	var jobStatusId = $("#hiddenChangeJobStatus").val();
	var activeJobStatus = $("#hiddenChangeJobActiveStatus").val();
	var date = $("#stoneInstallationDate").val();
	var time = $("#stoneInstallationTime").val();
	var employee = $("#selectStoneInstallationEmployees").val();
	$("#jobType_"+jobId).select2("val", jobStatusId);
	if(jobStatusId == 7 && date != '' && time != '' && employee != '') {
		changestatuswisejob(jobStatusId,jobId,activeJobStatus,date,time,employee);
	}
});



/*set audit*/
$(document).on('click','.view-audit',function(){
	//$(".view-audit").click(function(){
		var jobId = $(this).attr('data-id');
		$('#loader').show();
		$.ajax({
			url:'{{ route('showaudittrail') }}',
			data:{
				job_id:jobId,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data.key == 1)
				{
					$('#auditData').html(data.audit_data);
					var date = $('#formatedDate').val();
					var value = 'Audit_job_' + date;
					$('#auditList').DataTable({
						dom: 'Bfrtip',
						buttons: [
						{
							extend: 'csv',
							title: value,
							exportOptions: {columns: [ 0,1,2,3,4 ]},
						},
						{
							extend: 'excel',
							title: value,
							exportOptions: {columns: [ 0,1,2,3,4 ]},
						},
						{
							extend: 'pdf',
							pageSize: 'LEGAL',
							title: value,
							exportOptions: {columns: [ 0,1,2,3,4]},
						},
						{
							extend: 'print',
							title: value,
							exportOptions: {columns: [ 0,1,2,3,4 ]},
						},
						],
					});
					$('#loader').hide();
					$('#Auditmodel').modal('show');
				}
			}
		});
	});

/*view job model*/
$(document).on('click','.view-job', function(){
	var jobId = $(this).attr('data-id');
	$('#loader').show();
	$.ajax({
		url:'{{ route('viewjobdetails') }}',
		data:{
			job_id:jobId,
		},
		type:'post',
		dataType:'json',
		success: function(data)
		{
			if(data.key == 1)
			{
				$('#notesData').html(data.job_notes_detail);
				$('#jobTitle').html(data.employee_detail.job_title);
				$('#jobStatus').html(data.employee_detail.is_active);
				// $('#jobId').html(data.employee_detail.job_id);
				$('#jobType').html(data.employee_detail.job_status_name);
				$('#address1').html(data.employee_detail.address_1);
				$('#address2').html(data.employee_detail.address_2);
				$('#apartmentNo').html(data.employee_detail.apartment_number);
				$('#city').html(data.employee_detail.city);
				$('#state').html(data.employee_detail.state);
				$('#zipcode').html(data.employee_detail.zipcode);
				$('#jobStartDate').html(data.employee_detail.start_date);
				$('#jobEndDate').html(data.employee_detail.end_date);
				$('#plumbingInstallationDate').html(data.employee_detail.plumbing_installation_date);
				$('#deliveryDateTime').html(data.employee_detail.delivery_datetime);
				$('#jobSuperName').html(data.employee_detail.super_name);
				$('#superPhoneNumber').html(data.employee_detail.super_phone_number);
				$('#jobContractorName').html(data.employee_detail.contractor_name);
				$('#contractorEmail').html(data.employee_detail.contractor_email);
				$('#contractorPhoneNumber').html(data.employee_detail.contractor_phone_number);
				$('#jobCompanyName').html(data.employee_detail.company_name);
				$('#comapnyClients').html(data.employee_detail.company_clients_name);
				$('#salesEmployee').html(data.employee_detail.sales_employee_name);
				$('#serviceEmployee').html(data.employee_detail.service_employee_name);
				$('#workingEmployee').html(data.employee_detail.working_employee_name);

				if(data.employee_detail.is_select_delivery_installation == 4)
				{
					$('#deliveryInstallationSelect').html('Scheduled');
					$('#deliveryInstallationDateTime').html(data.employee_detail.delivery_installation_datetime);
					$('#deliveryInstallationEmployees').html(data.employee_detail.delivery_installation_employee_name);
				}
				else
				{
					switch (data.employee_detail.is_select_delivery_installation) {
						case 3:
						$('#deliveryInstallationSelect').html('Received');
						break;
						case 2:
						$('#deliveryInstallationSelect').html('Awaiting Approval');
						break;
						case 1:
						$('#deliveryInstallationSelect').html('Awaiting Material');
						break;
					}
					$('#deliveryInstallationDateTime').html('--');
					$('#deliveryInstallationEmployees').html('--');
				}
				if(data.employee_detail.is_select_installation == 3)
				{
					$('#installationSelect').html('Scheduled');
					$('#installationDateTime').html(data.employee_detail.installation_datetime);
					$('#installationEmployees').html(data.employee_detail.installation_employee_name);
				}
				else
				{
					switch (data.employee_detail.is_select_installation) {
						case 2:
						$('#installationSelect').html('Awaiting Approval');
						$('#installationDateTime').html('--');
						$('#installationEmployees').html('--');
						break;
						case 1:
						$('#installationSelect').html('Awaiting Install');
						$('#installationDateTime').html('--');
						$('#installationEmployees').html('--');
						break;
					}
				}
				if(data.employee_detail.is_select_stone_installation == 2)
				{
					$('#stoneInstallationSelect').html('Scheduled');
					$('#stoneInstallationDateTime').html(data.employee_detail.stone_installation_datetime);
					$('#stoneInstallationEmployees').html(data.employee_detail.stone_installation_employee_name);
				}
				else
				{
					$('#stoneInstallationSelect').html('Awaiting Approval');
					$('#stoneInstallationDateTime').html('--');
					$('#stoneInstallationEmployees').html('--');
				}
				$('#loader').hide();
				$('#jobDetailModel').modal('show');
			}
		}
	});
});

/*edit Note*/
$(document).on('click','.edit-note', function(){
	var jobId = $(this).attr('data-id');
	$("#jobNoteSubmit").prop("disabled",false);
	$.ajax({
		url:'{{ route('editnote') }}',
		data:{
			job_id:jobId,
		},
		type:'post',
		dataType:'json',
		success: function(data)
		{
			if(data.key == 1)
			{
				$('#hiddenJobId').val(data.job_note_detail.id);
				$('#jobNote').val(data.job_note_detail.job_note);
				$('#hiddenJobStatus').val(2);
				$('#jobNoteSubmit').html('Update');
				$('#jobDetailModel').modal('hide');
				$('#jobNotesModel').modal('show');
			}
		}
	});
});

$(document).on('click','.view-note-images', function(){
	var jobId = $(this).attr('data-id');
	$('#jobDetailModel').modal('hide');
	$("#loader").show();
	$.ajax({
		url:'{{ route('getjobimages') }}',
		data:{
			jobId:jobId,
		},
		type:'post',
		dataType:'json',
		success: function(response)
		{
			if(response.key == 1)
			{
				$('.carousel-indicators').html(response.html1);
				$('.carousel-inner').html(response.html2);
				$('#loader').hide();
				$('#jobImageModel').modal('show');
			}
			else
			{
				$('#loader').hide();
				notify('Job images not found.','blackgloss');
			}
		}
	});

});


/*delete Note*/
$(document).on('click','.delete-note', function(){
	if(confirm(' Are you sure you want to remove this note?')){
		var jobId = $(this).attr('data-id');
		$.ajax({
			url:'{{ route('destroynote') }}',
			data:{
				job_id:jobId,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data.key == 1)
				{
					$('#row_'+jobId).fadeOut(300, function(){
						$(this).remove();
					});
				}
			}
		});
	}
});

/* For select 2*/
$(".select2").select2();
$('.selectpicker').selectpicker();

/*job status menu*/
$(".nav_toggle").click(function(){
	$("#nav_menu").toggle();
});

$(".toolbar_btn").on('click', function(){
	$(".toolbar_btn").removeClass('toolbaractive');
	$(this).addClass('toolbaractive');
});

$(".toolbar_dropdownbtn").on('click', function(){
	$(".toolbar_dropdownbtn").removeClass('toolbarmenu_active');
	$(this).addClass('toolbarmenu_active');
});


/*Date picker*/
$('#deliveryDate,#installationDate,#stoneInstallationDate').datepicker({
	autoclose: true,
	todayHighlight: true,
});

$('.clockpicker').clockpicker({
	twelvehour: true,
	autoclose: true,
	placement: 'bottom',
});

$('#formAddNote').on('success.form.bv', function(e) {
	e.preventDefault();
	$('#loader').show();
	$('#jobNotesModel').modal('hide');
	var hidden_job_id = $('#hiddenJobId').val();
	var job_note_desc = $('#jobNote').val();
	var job_note_status = $('#hiddenJobStatus').val();
	$.ajax({
		url:'{{ route('storejobnote') }}',
		data:{
			hidden_job_id:hidden_job_id,
			job_note_desc:job_note_desc,
			job_note_status:job_note_status
		},
		type:'post',
		dataType:'json',
		success: function(data)
		{
			if(data.key == 1)
			{
				$('#loader').hide();
				$('#jobNote').val('');
				notify('Job note has been added successfully.','blackgloss');
			}
		}
	});
});

@if(Session::has('successMessage'))
notify('{{  Session::get('successMessage') }}','blackgloss');
{{ Session::forget('successMessage') }}
@endif
</script>
@stop