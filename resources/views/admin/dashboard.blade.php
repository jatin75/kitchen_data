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
									{{-- <li role="separator" class="divider"></li> --}}
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
								columns: [ 0,1,2,3,4 ],
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
							extend: 'excel',
							title: value,
							exportOptions: {
								columns: [ 0,1,2,3,4 ],
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
								columns: [ 0,1,2,3,4],
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
								columns: [ 0,1,2,3,4 ],
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
						}
						],
					});
					/* For select 2*/
					$(".select2").select2();
				}
			}
		});
	}

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
			url:'{{ route('jobstatuschange') }}',
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
