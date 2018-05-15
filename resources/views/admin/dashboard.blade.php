@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
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
tr th{
	padding-left: 10px !important;
}
.nav_toolbar_menu{
	padding-left: 8px;
}
.toolbar_btn{
	padding: 10px 18px !important;
	background-color: #fff !important;
	font-weight: bold;
	letter-spacing: 0.6px;
	margin: 0 2px 3px 0 !important;
	border: 1px solid #dcdbdbe0;
}
.toolbar_btn:focus {
	background-color: #4c5667 !important;
	color: #fff;
	box-shadow: none;
}
.toolbaractive {
	background-color: #4c5667 !important;
	color: #fff !important;
	box-shadow: none;
}
.nav_toggle ul li > a{
	padding: 8px 12px !important;
	border-bottom: 1px solid #e5e5e5;
}
.word-wrap{word-break: normal;}
.scrollit { height:150px; width: auto; overflow-y:scroll; border: 1px solid; background: #f4f8fb;}
</style>
@stop
@section('content')
<!-- Page Content -->
<div class="container-fluid">
	<input type="hidden" id="formatedDate" name="formatedDate" value="{{ date('Y_m_d') }}">
	<div class="row bg-title">
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4 class="page-title">Kitchen Dashboard</h4>
		</div>
	</div>
	<!--/row -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-info">
				<div class="panel-wrapper collapse in" aria-expanded="true">
					<div class="panel-body dashboard-job-list">
						{{-- <div class="nav_toggle"><i style="border:  1px solid #d1d1d1; padding:  6px; border-radius: 3px;cursor: pointer;" class="ti-menu"></i></div> --}}
						<div class="nav_toggle user-profile" style="padding-top: 0;padding-bottom: 20px;text-align: left">
							<div class="dropdown user-pro-body" style="margin: 0px  !important">
								<a href="#" class="u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i style="border:  1px solid #d1d1d1; padding:  6px; border-radius: 3px;cursor: pointer;" class="ti-menu"></i></a>
								<ul class="dropdown-menu animated flipInY" style="margin-left: 0;top:30px;padding: 0;">
									<li><a class="toolbar_dropdownbtn toolbaractive" href="javascript:void(0)" onclick="getJobDetailsList(0)"> All </a></li>
									@foreach($jobTypeDetails as $jobType)
									<li><a class="toolbar_dropdownbtn" href="javascript:void(0)" onclick="getJobDetailsList({{ $jobType->job_status_id }})"> {{ strtoupper($jobType->job_status_name) }} </a></li>
									@endforeach
								</ul>
							</div>
						</div>
						{{-- <ul class="nav nav-pills m-b-30" id="nav_menu">
							<li data-id="0" class="nav-item active"> <a href="javascript:void(0)" onclick="getJobDetailsList(0)" class="nav-link" data-toggle="tab" aria-expanded="true">All</a> </li>
							@foreach($jobTypeDetails as $jobType)
							<li data-id="{{ $jobType->job_status_id }}" class="nav-item"> <a href="javascript:void(0)" onclick="getJobDetailsList({{ $jobType->job_status_id }})" class="nav-link" data-toggle="tab" aria-expanded="true">{{ strtoupper($jobType->job_status_name) }}</a> </li>
							@endforeach
						</ul> --}}
						<div class="row button-box nav_toolbar_menu m-b-30">
							<div class=""><button class="btn toolbar_btn toolbaractive" data-id="0" onclick="getJobDetailsList(0)">All</button></div>
							@foreach($jobTypeDetails as $jobType)
							<div class="">
								<button class="btn toolbar_btn" data-id="{{ $jobType->job_status_id }}" onclick="getJobDetailsList({{ $jobType->job_status_id }})">{{ strtoupper($jobType->job_status_name) }}</button>
							</div>
							@endforeach
						</div>
						<div class="table-responsive jobDetailList">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- row -->
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
		/*get job detail list*/
		getJobDetailsList(0);

	});

	/*get job detail list*/
	function getJobDetailsList(jobStatusId){
		$('.jobDetailList').html('<div id="jobchart" class="box" style="padding: inherit;"><p style="text-align: center;margin: 10px;"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="font-size:24px"></i></p></div>');
		$.ajax({
			url:'{{ route('showjobdetailstatus') }}',
			data:{
				jobStatusId:jobStatusId,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data.html != '')
				{
					$('.jobDetailList').html(data.html);
					var date = $('#formatedDate').val();
					var value = 'Kitchen_job' + date;
					$('#jobList').DataTable({
						dom: 'Bfrtip',
						buttons: [
						{
							extend:'pageLength',
						},
						{
							extend: 'csv',
							title: value,
							exportOptions: {
								columns: [ 1,2,3,4,5 ],
								format: {
									body: function( data, row, col, node ) {
										if (col == 2) {
											return $('#jobList').DataTable()
											.cell( {row: row, column: 3} )
											.nodes()
											.to$()
											.find(':selected')
											.text()
										} else {
											return data;
										}
									}
								},
							},
						},
						{
							extend: 'excel',
							title: value,
							exportOptions: {
								columns: [ 1,2,3,4,5 ],
								format: {
									body: function( data, row, col, node ) {
										if (col == 2) {
											return $('#jobList').DataTable()
											.cell( {row: row, column: 3} )
											.nodes()
											.to$()
											.find(':selected')
											.text()
										} else {
											return data;
										}
									}
								},
							},
						},
						{
							extend: 'pdf',
							pageSize: 'LEGAL',
							title: value,
							exportOptions: {
								columns: [ 1,2,3,4,5],
								format: {
									body: function( data, row, col, node ) {
										if (col == 2) {
											return $('#jobList').DataTable()
											.cell( {row: row, column: 3} )
											.nodes()
											.to$()
											.find(':selected')
											.text()
										} else {
											return data;
										}
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
									body: function( data, row, col, node ) {
										if (col == 2) {
											return $('#jobList').DataTable()
											.cell( {row: row, column: 3} )
											.nodes()
											.to$()
											.find(':selected')
											.text()
										} else {
											return data;
										}
									}
								},
							},
						}
						],
					});
					/* For select 2*/
					$(".select2").select2();
				}
			}
		});
	}

	/*view job model*/
	$(document).on('click','.view-job', function() {
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
					$('#loader').hide();
					$('#jobDetailModel').modal('show');
				}
			}
		});
	});

	/*edit Note*/
	$(document).on('click','.edit-note', function(){
		var jobId = $(this).attr('data-id');
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

	$('#formAddNote').on('submit', function(e) {
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
                    notify('Job note has been added successfully.','blackgloss');
                }
            }
        });
	});

	/*job status menu*/
	$(".nav_toggle").click(function(){
		$("#nav_menu").toggle();
	});

	/*change job status*/
	$(document).on('change','.jobType',function(){
		var jobStatusId = $(this).val();
		var jobId = $(this).attr('data-id');
		$("#loader").show();
		$.ajax({
			url:'{{ route('changedashboardjobstatus') }}',
			data:{jobStatusId:jobStatusId,jobId:jobId},
			type: 'post',
			dataType: 'json',
			success:function(data){
				if(data.key == 1 ) {
					location.reload();
				}
			}
		});
	});

	$(".toolbar_btn").on('click', function(){
		$(".toolbar_btn").removeClass('toolbaractive');
		$(this).addClass('toolbaractive');
	});

	$(".toolbar_dropdownbtn").on('click', function(){
		$(".toolbar_dropdownbtn").removeClass('toolbaractive');
		$(this).addClass('toolbaractive');
	});

	@if(Session::has('successMessage'))
	notify('{{  Session::get('successMessage') }}','blackgloss');
	{{ Session::forget('successMessage') }}
	@endif
</script>
@stop
