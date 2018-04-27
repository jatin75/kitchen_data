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
			<h4 class="page-title">Company</h4>
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
							<li class="active nav-item"> <a href="#tab1" class="nav-link" data-toggle="tab" aria-expanded="true">COMPANY PROFILE</a> </li>
						</ul>
						<div class="form-body form-material">
							<div class="tab-content br-n pn">
								<!--tab1-->
								<div id="tab1" class="tab-pane active">
									<form id="formAddClientCompany" method="post">
										{{ csrf_field() }}
										{{-- <input type="hidden" name="hiddenMail" id="hiddenMail" value="{{$accountDetail->email or ''}}"> --}}
										{{-- <input type="show" name="hiddenStatus" id="hiddenStatus" value="{{$new_account or ''}}"> --}}
										<input type="hidden" name="hiddenCompanyId" id="hiddenCompanyId" value="{{$companyDetail->id or ''}}">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>NAME</b></label>
													<input style="text-transform:uppercase;" type="text" name="companyName" id="companyName" value="{{$companyDetail->name or ''}}" class="form-control" placeholder="COMPANY NAME">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>EMAIL ADDRESS</b></label>
													<input style="text-transform: lowercase;" type="email" name="companyEmail" id="companyEmail" value="{{$companyDetail->email or ''}}" class="form-control"  placeholder="Enter your email">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>COMPANY ID</b></label><br>
													<span class="disabled-color" id="companyId">{{$companyDetail->company_id or '' }}</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>PHONE NUMBER</b></label>
													<input type="text" placeholder="(xxx) xxx-xxxx" name="companyPhoneNo" id="companyPhoneNo" value="{{$companyDetail->phone_number or ''}}" class="form-control">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>ADDRESS 1</b></label><br>
													<input type="text" name="locationAddress" id="locationAddress" value="{{$companyDetail->address_1 or ''}}" class="form-control" placeholder="Address line 1">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>ADDRESS 2</b></label><br>
													<input type="text" name="subAddress" id="subAddress" value="{{$companyDetail->address_2 or ''}}" class="form-control" placeholder="Address line 2">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>CITY</b></label>
													<input type="text" name="city" id="city" value="{{$companyDetail->city or ''}}" class="form-control" placeholder="Enter city">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>STATE</b></label>
													<input type="text" name="state" id="state" value="{{$companyDetail->state or ''}}" class="form-control"  placeholder="Enter state">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label"><b>ZIPCODE</b></label>
													<input type="text" placeholder="Enter zipcode" name="zipcode" id="zipcode" value="{{$companyDetail->zipcode or ''}}" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group text-left p-t-md">
											@if(!isset($companyDetail->id))
											<button type="submit" class="btn btn-success">Add</button>
											@endif
											@if(isset($companyDetail->id))
											<button type="submit" class="btn btn-info">UPDATE</button>
											@endif
											&nbsp;
											&nbsp;
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
<script src="{{ asset('scripts/company-location.js') }}"></script>
<script type="text/javascript">
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(document).ready(function() {
		if(typeof($('#companyName').val()) != "undefined" && $('#companyName').val() !== null)
			$('#companyName').val($('#companyName').val().toUpperCase());
		if(typeof($('#companyEmail').val()) != "undefined" && $('#companyEmail').val() !== null)
			$('#companyEmail').val($('#companyEmail').val().toLowerCase());

		$('#resetPermission').click(function(){
			location.reload();
		});
	});

	/* For select 2*/
	$(".select2").select2();

	$('#formAddClientCompany').on('success.form.bv', function(e) {
		e.preventDefault();
		$('#loader').show();
		var hidden_companyId = $('#hiddenCompanyId').val();
		var company_name = $('#companyName').val();
		var company_contactNo = $('#companyPhoneNo').val();
		var company_email = $('#companyEmail').val();
		var company_address_1 = $('#locationAddress').val();
		var company_address_2 = $('#subAddress').val();
		var city = $('#city').val();
		var state = $('#state').val();
		var zipcode = $('#zipcode').val();
		$.ajax({
			url:'{{ route('storeclientcompany') }}',
			data:{
				hidden_companyId:hidden_companyId,
				company_name:company_name,
				company_contactNo:company_contactNo,
				company_email:company_email,
				company_address_1:company_address_1,
				company_address_2:company_address_2,
				city:city,
				state:state,
				zipcode:zipcode
			},
			type:'post',
			dataType:'json',
			success: function(data)
			{
				if(data.key == 1)
				{
					location.href = '{{ route('showclientcompany') }}';
				}
				if(data.key == 2)
				{
					$('#loader').hide();
					notify('Company detail has been updated successfully.','blackgloss');
				}
			}
		});
	});

	$('#companyName').keyup(function() {
		this.value = this.value.toUpperCase();
	});

	$('#companyEmail').keyup(function() {
		this.value = this.value.toLowerCase();
	});

	/*prevent form to submit on enter*/
	$(document).on("keypress", ":input:not(textarea)", function(event) {
		return event.keyCode != 13;
	});

	/*Mask phone Number Digits*/
	/*$("#leagueContactNo").mask("999-999-999-9?999999");*/
	$("#companyPhoneNo").mask("(999) 999 - 9999");

	@if(Session::has('successMessage'))
    notify('{{  Session::get('successMessage') }}','blackgloss');
    {{ Session::forget('successMessage') }}
    @endif
</script>
@stop