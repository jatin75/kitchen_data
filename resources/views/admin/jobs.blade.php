@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
<style type="text/css">
.modal-footer {
	padding-bottom: 0px !important;
	margin-bottom: 0px !important;
}
tr th{
	padding-left: 10px !important;
}
.word-wrap{word-break: normal;}
.scrollit { height:150px; width: auto; overflow-y:scroll; border: 1px solid; background: #f4f8fb;}
</style>
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
				<h3 class="box-title m-b-0 pull-left">All JOBS</h3>

				<a href="{{route('addjob')}}" class="btn btn-success btn-rounded waves-effect waves-light pull-right m-b-15 m-r-15"><span>Add Job</span> <i class="fa fa-plus m-l-5"></i></a>
				{{-- <a href="{{route('viewjobdetails')}}" class="btn btn-success btn-rounded waves-effect waves-light pull-right m-b-15 m-r-15"><span>View Jobs</span> <i class="ti-eye m-l-5"></i></a> --}}
				<div class="table-responsive">
					<table id="jobList" class="display nowrap" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th class="text-center">Actions</th>
								<th>Job Name</th>
								<th>Job Id</th>
								<th>Job Status</th>
								<th>Start Date</th>
								<th>Expected Completion Date</th>
							</tr>
						</thead>
						<tbody>
							@foreach($jobDetails as $job)
							<tr>
								<td class="text-center">
										<span data-toggle="modal" data-target="#jobDetailModel">
											<a data-toggle="tooltip" data-placement="top" title="View Job" class="btn btn-success btn-circle view-job" data-id="{{ $job->job_id }}">
												<i class="ti-eye"></i>
											</a>
										</span>
										<a data-toggle="tooltip" data-placement="top" title="Edit Job" class="btn btn-info btn-circle" href="{{route('editjob',['job_id' => $job->job_id])}}">
											<i class="ti-pencil-alt"></i>
										</a>
										<span data-toggle="modal" data-target="#jobNotesModel">
											<a data-toggle="tooltip" data-placement="top" title="Add Job Notes" class="btn btn-warning btn-circle add-job-note" data-id="{{ $job->job_id }}">
												<i class="ti-plus"></i>
											</a>
										</span>
										<span data-toggle="modal" data-target="#Auditmodel">
											<a data-toggle="tooltip" data-placement="top" title="View Audit" class="btn btn-primary btn-circle view-audit" data-id="{{ $job->job_id }}">
												<i class="ti-receipt"></i>
											</a>
										</span>
										<a class="btn btn-danger btn-circle" onclick="return confirm('Are you sure you want to deactivate this job?');" href="{{route('deactivatejob',['job_id' => $job->job_id])}}" data-toggle="tooltip" data-placement="top" title="Deactivate Job"><i class="ti-lock"></i> </a>
									</td>
									<td>{{$job->job_title}}</td>
									<td>{{$job->job_id}}</td>
									<td>
										<select class="form-control select2 jobType" name="jobType" id="jobType" placeholder="Select your job type" data-id="{{$job->job_id}}">
											@foreach($jobTypeDetails as $jobType)
											<option value="{{ $jobType->job_status_id }}" @if(isset($job->job_status_id) && $job->job_status_id == $jobType->job_status_id) {{"selected='selected'"}} @endif> {{ $jobType->job_status_name }}</option>
											@endforeach
										</select>
									</td>
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
						<input type="hidden" id="hiddenJobId" name="hiddenJobId" value="">
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
	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="Auditmodel" style="display: none; z-index:100000;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Job Audit</h4>
				</div>
				<div class="modal-body">
					<div class="table-responsive" id="auditData">
                    {{-- <table id="auditList" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name Of Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Date Of Edit</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>job_title</td>
                                <td>Momai Costruction</td>
                                <td>TATA Costruction</td>
                                <td>05/04/2018</td>
                                <th>Vivek Italiya</th>
                            </tr>
                        </tbody>
                    </table> --}}
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
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
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB STATUS</label>
					<br><span id="jobStatus"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">JOB ID</label>
						<br><span id="jobId"></span>
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
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">CITY</label>
						<br><span id="city"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">STATE</label>
						<br><span id="state"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">ZIPCODE</label>
						<br><span id="zipcode"></span>
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
						<label class="control-label">JOB COMPANY NAME</label>
						<br><span id="jobCompanyName"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">COMPANY CLIENTS</label>
						<br><span id="comapnyClients"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">WORKING EMPLOYEES</label>
						<br><span id="workingEmployee"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">INSTALLATION</label>
						<br><span id="installationSelect"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">INSTALLATION DATE AND TIME</label>
						<br><span id="installationDateTime"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">INSTALLATION EMPLOYEES</label>
						<br><span id="installationEmployees"></span>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">STONE INSTALLATION</label>
						<br><span id="stoneInstallationSelect"></span>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 p-b-20">
						<label class="control-label">STONE INSTALLATION DATE AND TIME</label>
						<br><span id="stoneInstallationDateTime"></span>
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
							{{-- <div class="row">
								<div class="col-md-4 col-sm-4 col-xs-4" class="word-wrap">
									<span id="note"></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<span id="updated_by"></span>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3">
									<span id="updated_date"></span>
								</div>
								<div class="col-xs-2">
									<a title="Edit" href="#">
										<i class="ti-pencil-alt"></i>
									</a>
								</div>
								<div class="col-xs-2">
									<a onclick="return confirm('Are you sure you want remove this note?');" title="Remove" href="#">
										<i class="ti-trash"></i>
									</a>
								</div>
							</div> --}}
						</div>
					</div>
					</div>
				</div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!--/.jobDetail model-->
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
					columns: [ 1,2,3,4,5 ],
					format: {
						body: (data, row, col, node) => {
							let node_text = '';
							const spacer = node.childNodes.length > 1 ? ' ' : '';
							node.childNodes.forEach(child_node => {
								const temp_text = child_node.nodeName == "SELECT" ? '' : child_node.textContent;
								node_text += temp_text ? `${temp_text}${spacer}` : '';
								if(child_node.nodeName == "SELECT"){
									node_text = node_text.trim();
								}
							});
							return node_text;
						}
					},
				},
				//mColumns: "visible",
			},
			{
				extend: 'excel',
				title: value,
				exportOptions: {
					columns: [ 1,2,3,4,5 ],
					format: {
						body: (data, row, col, node) => {
							let node_text = '';
							const spacer = node.childNodes.length > 1 ? ' ' : '';
							node.childNodes.forEach(child_node => {
								const temp_text = child_node.nodeName == "SELECT" ? '' : child_node.textContent;
								node_text += temp_text ? `${temp_text}${spacer}` : '';
								if(child_node.nodeName == "SELECT"){
									node_text = node_text.trim();
								}
							});
							return node_text;
						}
					},
				},
			},
			{
				extend: 'pdf',
				pageSize: 'LEGAL',
				title: value,
				exportOptions: {
					columns: [ 1,2,3,4,5 ],
					format: {
						body: (data, row, col, node) => {
							let node_text = '';
							const spacer = node.childNodes.length > 1 ? ' ' : '';
							node.childNodes.forEach(child_node => {
								const temp_text = child_node.nodeName == "SELECT" ? /*child_node.selectedOptions[0].textContent*/ '' : child_node.textContent;
								node_text += temp_text ? `${temp_text}${spacer}` : '';
								if(child_node.nodeName == "SELECT"){
									node_text = node_text.trim();
								}
							});
							return node_text;
						}
					},
				},
			},
			{
				extend: 'print',
				title: value,
				exportOptions: {
					columns: [ 1,2,3,4,5 ],
					format: {
						body: (data, row, col, node) => {
							let node_text = '';
							const spacer = node.childNodes.length > 1 ? ' ' : '';
							node.childNodes.forEach(child_node => {
								const temp_text = child_node.nodeName == "SELECT" ? /*child_node.selectedOptions[0].textContent*/ '' : child_node.textContent;
								node_text += temp_text ? `${temp_text}${spacer}` : '';
								if(child_node.nodeName == "SELECT"){
									node_text = node_text.trim();
								}
							});
							return node_text;
						}
					},
				},
			},
			],
		});
	});

	/*set job id on models*/
	$(".add-job-note").click(function(){
		var jobId = $(this).attr('data-id');
		$('#hiddenJobId').val(jobId);
	});

	/*change job status*/
	$(".jobType").change(function() {
		var jobStatusId = $(this).val();
		var jobId = $(this).attr('data-id');
		var checkJob;
		$("#loader").show();
		$.ajax({
			url:'{{ route('changejobstatus') }}',
			data:{jobStatusId:jobStatusId,jobId:jobId,checkJob:1},
			type: 'post',
			dataType: 'json',
			success:function(data){
				if(data.key == 1 ) {
					location.reload();
				}else {
					$("#loader").hide();
					notify('Job Status has been Changed Successfully.','blackgloss');
				}
			}

		});
	});

	/*set audit*/
	$(".view-audit").click(function(){
		var jobId = $(this).attr('data-id');
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
					$('#loader').hide();
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
				}
			}
		});
	});

	/*view job model*/
	$(".view-job").click(function(){
		var jobId = $(this).attr('data-id');
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
					$('#jobTitle').html(data.employee_detail.job_title);
					$('#jobStatus').html(data.employee_detail.is_active);
					$('#jobId').html(data.employee_detail.job_id);
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
					$('#workingEmployee').html(data.employee_detail.working_employee_name);
					if(data.employee_detail.is_select_installation == 1)
					{
						$('#installationSelect').html('Yes');
						$('#installationDateTime').html(data.employee_detail.installation_datetime);
						$('#installationEmployees').html(data.employee_detail.installation_employee_name);
					}
					else
					{
						$('#installationSelect').html('No');
						$('#installationDateTime').html('--');
						$('#installationEmployees').html('--');
					}
					if(data.employee_detail.is_select_stone_installation == 1)
					{
						$('#stoneInstallationSelect').html('Yes');
						$('#stoneInstallationDateTime').html(data.employee_detail.stone_installation_datetime);
						$('#stoneInstallationEmployees').html(data.employee_detail.stone_installation_employee_name);
					}
					else
					{
						$('#stoneInstallationSelect').html('No');
						$('#stoneInstallationDateTime').html('--');
						$('#stoneInstallationEmployees').html('--');
					}
					$('#notesData').html(data.job_notes_detail);
				}
			}
		});
	});

	/* For select 2*/
	$(".select2").select2();

	$('#formAddNote').on('submit', function(e) {
		e.preventDefault();
		$('#loader').show();
		$('#jobNotesModel').modal('hide');
		var hidden_job_id = $('#hiddenJobId').val();
		var job_note_desc = $('#jobNote').val();
		$.ajax({
			url:'{{ route('storejobnote') }}',
			data:{
				hidden_job_id:hidden_job_id,
				job_note_desc:job_note_desc,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data.key == 1)
				{
					$('#loader').hide();
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