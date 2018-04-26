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
							<li class="active nav-item"> <a href="#tab1" class="nav-link" data-toggle="tab" aria-expanded="true">CLIENT PROFILE</a> </li>
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
									<form id="formAddStaff" method="post">
										{{ csrf_field() }}
										{{-- <input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$accountDetail->email or ''}}"> --}}
										{{-- <input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
										<input type="hidden" name="hiddenStaffId" id="hiddenStaffId" value="{{$staffDetail->staff_id or ''}}">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>FIRST NAME</b></label>
													<input style="text-transform:uppercase;" type="text" name="staffFirstName" id="staffFirstName" value="{{$staffDetail->first_name or ''}}" class="form-control" placeholder=" FIRST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>LAST NAME</b></label>
													<input style="text-transform:uppercase;" type="text" name="staffLastName" id="staffLastName" value="{{$staffDetail->last_name or ''}}" class="form-control" placeholder=" LAST NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>STAFF ID</b></label><br>
													<span class="disabled-color" id="staffId">{{$staffDetail->staff_id or '' }}</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMAIL ADDRESS</b></label>
													<input style="text-transform: lowercase;" type="email" name="staffEmail" id="staffEmail" value="{{$staffDetail->email or ''}}" class="form-control"  placeholder="Enter Your Email">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>PHONE NUMBER</b></label>
													<input type="text" placeholder="(xxx) xxx-xxxx" name="staffContactNo" id="staffContactNo" value="{{$staffDetail->phone_number or ''}}" class="form-control">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>START DATE</b></label><br>
													<input type="text" name="startDate" id="startDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy" maxlength="10" value="{{$staffDetail->start_date or ''}}">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>DEPARTMENT</b></label>
													<select id="staffDepartment" name="staffDepartment" class="form-control select2">
														<option value="">-- Select Department --</option>
														@foreach($departmentList as $department)
														<option value="{{ $department->department_id }}" @if(isset($staffDetail->department_id) && $staffDetail->department_id == $department->department_id) {{"selected='selected'"}} @endif>{{ $department->department_name }}</option>
														@endforeach
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>ROLES</b></label>
													<select id="staffRoles" name="staffRoles" class="form-control select2">
														<option value="">-- Select Role --</option>
														@if(isset($staffDetail->staff_id))
														@foreach($roleList as $role)
														<option value="{{ $role->role_id }}" @if(isset($staffDetail->role_id) && $staffDetail->role_id == $role->role_id) {{"selected='selected'"}} @endif>{{ $role->role_name }}</option>
														@endforeach
														@endif
													</select>
												</div>
											</div>
										</div>
										<div class="form-group text-left p-t-md">
											@if(!isset($staffDetail->staff_id))
											<button type="submit" class="btn btn-success">CREATE ACCOUNT</button>
											@endif
											@if(isset($staffDetail->staff_id))
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
										<input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$staffDetail->email or ''}}">
										{{-- <input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
										{{-- <input type="hidden" name="hiddenStaffId" id="hiddenStaffId" value="{{$staffDetail->staff_id or ''}}"> --}}
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
								<!--tab3-->
								{{-- @if(!isset($accountSetting) && (isset($permissionList) && $permissionList->email != Session::get('email') && $permissionList->is_admin = 1))
								<div id="tab3" class="tab-pane">
									<form id="formStaffPermission" method="post">
										{{ csrf_field() }}
										<input type="hidden" name="hiddenStaffID" id="hiddenStaffID" value="{{$permissionList->staff_id or ''}}">
										<!--permissions-->
										<div>
											<div class="row">
												<div class="col-md-12">
													<div class="control-label"><b>MANAGE PERMISSIONS</b></div>
												</div>
											</div>
											<hr>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>EXPORT</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Export CSV, Excel, PDF, Print file.</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_export or 0}}" type="checkbox" @if(isset($permissionList->can_access_export) && $permissionList->can_access_export == 1) {{'checked="checked"'}} @endif id="access_export" name="access_export" class="js-switch" onchange="changePermission('export');" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" />
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>UPDATE STAFF</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Update Staff, Reset Password, Deactivate Staff, Reactivate Staff, Delete Staff .</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_update_staff or 0}}" type="checkbox" id="access_update_staff" name="access_update_staff" class="js-switch" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" @if(isset($permissionList->can_access_update_staff) && $permissionList->can_access_update_staff == 1) {{'checked="checked"'}} @endif onchange="changePermission('update_staff');"/>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>IMPORT AGREEMENT</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Import Software Agreement.</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_import_Agreement or 0}}" type="checkbox" id="access_import_Agreement" name="access_import_Agreement" class="js-switch" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" @if(isset($permissionList->can_access_import_Agreement) && $permissionList->can_access_import_Agreement == 1) {{'checked="checked"'}} @endif onchange="changePermission('import_Agreement');"/>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>ASSIGN CONSULTANT</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Assign Consultant to any prospect.</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_assign_consultant or 0}}" type="checkbox" id="access_assign_consultant" name="access_assign_consultant" class="js-switch" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" @if(isset($permissionList->can_access_assign_consultant) && $permissionList->can_access_assign_consultant == 1) {{'checked="checked"'}} @endif onchange="changePermission('assign_consultant');"/>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>DEPARTMENTS &amp; ROLES</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Add Departments and Roles.</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_department_and_roles or 0}}" type="checkbox" id="access_department_and_roles" name="access_department_and_roles" class="js-switch" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" @if(isset($permissionList->can_access_department_and_roles) && $permissionList->can_access_department_and_roles == 1) {{'checked="checked"'}} @endif onchange="changePermission('department_and_roles');"/>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>ADD FEEDS</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Add Feeds that contains information.</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_add_feeds or 0}}" type="checkbox" id="access_add_feeds" name="access_add_feeds" class="js-switch" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" @if(isset($permissionList->can_access_add_feeds) && $permissionList->can_access_add_feeds == 1) {{'checked="checked"'}} @endif onchange="changePermission('add_feeds');"/>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>ADD STAFF</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Add Staff ability.</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_add_staff or 0}}" type="checkbox" id="access_add_staff" name="access_add_staff" class="js-switch" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" @if(isset($permissionList->can_access_add_staff) && $permissionList->can_access_add_staff == 1) {{'checked="checked"'}} @endif onchange="changePermission('add_staff');"/>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label"><b>REMOVE FEEDS</b></label>
														<div class="m-b-10">
															<div class="row">
																<div class="col-md-6">
																	<span>Remove feeds that contains information.</span>
																</div>
																<div class="col-md-6">
																	<input value="{{$permissionList->can_access_remove_feeds or 0}}" type="checkbox" id="access_remove_feeds" name="access_remove_feeds" class="js-switch" data-color="#63a9f7" data-secondary-color="#e3e3e3" data-size="small" @if(isset($permissionList->can_access_remove_feeds) && $permissionList->can_access_remove_feeds == 1) {{'checked="checked"'}} @endif onchange="changePermission('remove_feeds');"/>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<!--/.permissions-->
										<div class="form-group text-left p-t-md">
											<button type="submit" class="btn btn-info">SAVE</button>&nbsp;&nbsp;&nbsp;
											<button id="resetPermission" type="button" class="btn btn-danger">CANCEL</button>
										</div>
									</form>
								</div>
								@endif --}}
								<!--/.tab3-->
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
		if(typeof($('#staffFirstName').val()) != "undefined" && $('#staffFirstName').val() !== null)
			$('#staffFirstName').val($('#staffFirstName').val().toUpperCase());
		if(typeof($('#staffLastName').val()) != "undefined" && $('#staffLastName').val() !== null)
			$('#staffLastName').val($('#staffLastName').val().toUpperCase());
		if(typeof($('#staffEmail').val()) != "undefined" && $('#staffEmail').val() !== null)
			$('#staffEmail').val($('#staffEmail').val().toLowerCase());

		$("#staffDepartment").change(function(){
			$("#staffRoles").select2("val", "");
			var departmentId = $(this).val();
			$.ajax({
				url: '{{ route('getdepartmentroles') }}',
				data: {departmentId:departmentId},
				type: 'post',
				dataType: 'json',
				success:function(response){
					var len = response.roles_data.length;
					$("#staffRoles").empty();
					$("#staffRoles").html("<option value=''>-- Select Role --</option>");
					for( var i = 0; i<len; i++){
						var id = response.roles_data[i]['role_id'];
						var name = response.roles_data[i]['role_name'];
						$("#staffRoles").append("<option value='"+id+"'>"+name+"</option>");
					}
				}
			});
		});

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

	$('#formAddStaff').on('success.form.bv', function(e) {
		e.preventDefault();
		$('#loader').show();
		var hidden_staff_id = $('#hiddenStaffId').val();
		var staff_first_name = $('#staffFirstName').val();
		var staff_last_name = $('#staffLastName').val();
		var staff_email = $('#staffEmail').val();
		var staff_contactNo = $('#staffContactNo').val();
		var staff_department_id = $('#staffDepartment').val();
		var staff_roles = $('#staffRoles').val();
		var start_date = $('#startDate').val();
		$.ajax({
			url:'{{ url('storestaff') }}',
			data:{
				hidden_staff_id:hidden_staff_id,
				staff_first_name:staff_first_name,
				staff_last_name:staff_last_name,
				staff_email:staff_email,
				staff_contactNo:staff_contactNo,
				staff_department_id:staff_department_id,
				staff_roles:staff_roles,
				start_date:start_date,
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data == 1)
				{
					/*$('#loader').hide();*/
					location.href = '{{ route('currentstaff') }}';
				}
				else if(data.key == 2)
				{
					if(typeof(data.name) != "undefined" && data.name !== null)
					{
						$('#sessionName').html(data.name);
					}
					$('#loader').hide();
					notify('Account has been updated successfully','blackgloss');
				}
				else if(data == 3)
				{
					$('#loader').hide();
					notify('Entered email address already exists.','blackgloss');
				}
			}
		});
	});

	$('#formAccountSetting').on('success.form.bv', function(e) {
		e.preventDefault();
		$('#loader').show();
		var current_password = $('#currentPassword').val();
		var new_password = $('#newPassword').val();
		var retype_password = $('#retypePassword').val();
		var hidden_email = $('#hiddenMail').val();
		if(new_password != retype_password)
		{
			$('#loader').hide();
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

	/*$('#formStaffPermission').on('submit', function(e) {
		e.preventDefault();
		// $('#loader').show();
		var hidden_staff_id = $('#hiddenStaffID').val();
		var access_export = $('#access_export').val();
		var access_import_Agreement = $('#access_import_Agreement').val();
		var access_department_and_roles = $('#access_department_and_roles').val();
		var access_add_staff = $('#access_add_staff').val();
		var access_update_staff = $('#access_update_staff').val();
		var access_assign_consultant = $('#access_assign_consultant').val();
		var access_add_feeds = $('#access_add_feeds').val();
		var access_remove_feeds = $('#access_remove_feeds').val();
		$.ajax({
			url:'{{ route('storepermission') }}',
			data:{
				hidden_staff_id:hidden_staff_id,
				access_export:access_export,
				access_import_Agreement:access_import_Agreement,
				access_department_and_roles:access_department_and_roles,
				access_add_staff:access_add_staff,
				access_update_staff:access_update_staff,
				access_assign_consultant:access_assign_consultant,
				access_add_feeds:access_add_feeds,
				access_remove_feeds:access_remove_feeds,
			},
			type:'post',
			success: function(data)
			{
				if(data == 1)
				{
					$('#loader').hide();
					notify('Permissions has been updated successfully','blackgloss');
				}
			}
		});
	});*/

	function deactivateAccount(account_id)
	{
		if(confirm(' You cant reactivate prospect. Are you sure you want to remove this prospect?')){
			$('#loader').show();
			$.ajax({
				url:'{{ url('accountstatus') }}',
				data:{
					account_id:account_id,
				},
				type:'post',
				success: function(data)
				{
					if(data == 'deactivated')
					{
						/*$('#loader').hide();*/
						location.href = '{{ route('activeaccount') }}';
					}
					else
					{
						/*$('#loader').hide();*/
						location.href = '{{ route('deactivatedaccount') }}';
					}
				}
			});
		}
	}

	$('#staffFirstName,#staffLastName').keyup(function() {
		this.value = this.value.toUpperCase();
	});

	$('#staffEmail').keyup(function() {
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
	$("#staffContactNo").mask("(999) 999 - 9999");

	/*Date picker*/
	jQuery('#startDate').datepicker({
		autoclose: true,
		todayHighlight: true,
		/*startDate: new Date()*/
	});

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