@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css')}}" />
<style type="text/css">
.modal-footer {
    padding-bottom: 0px !important;
    margin-bottom: 0px !important;
}
tr th{
  padding-left: 10px !important;
}
.popover {
    z-index: 999999;
    /*display: block !important;*/
}
.bootstrap-select .dropdown-toggle:focus {
    outline: 0px auto -webkit-focus-ring-color!important;
}
.dropdown-toggle::after {
    display: inline-block;
    position: relative;
    right: 20px;
}
.bootstrap-select.btn-group .dropdown-toggle .filter-option {
    padding-right: 15px;
    text-overflow: ellipsis;
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
@stop
@section('content')
<div class="container-fluid">
    <input type="hidden" id="formatedDate" name="formatedDate" value="{{ date('Y_m_d') }}">
    <div class="row bg-title">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
         <h4 class="page-title">Jobs > Deactivated</h4>
     </div>
 </div>
 <div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0 pull-left">All JOBS</h3>
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
                        <tr class="changestatus_{{ $job->job_id }}">
                            <td class="text-center">
                                {{-- <a data-toggle="tooltip" data-placement="top" title="View Job" class="btn btn-success btn-circle" href="{{route('viewjob',['jobe_id' => $job->id])}}">
                                    <i class="ti-eye"></i>
                                </a> --}}
                                <a data-toggle="tooltip" data-placement="top" title="Edit Job" class="btn btn-info btn-circle" href="{{route('editjob',['job_id' => $job->job_id])}}">
                                    <i class="ti-pencil-alt"></i>
                                </a>
                                <a class="btn btn-danger btn-circle" onclick="return confirm(' Are you sure you want to remove this job?');" href="{{route('deletejob',['job_id' => $job->job_id])}}" data-toggle="tooltip" data-placement="top" title="Remove Job"><i class="ti-trash"></i> </a>
                            </td>
                            <td>{{$job->job_title}}</td>
                            <td>{{$job->job_id}}</td>
                            <td>
                                <select class="form-control select2 jobType" name="jobType" id="jobType_{{$job->job_id}}" placeholder="Select your job type" data-id="{{$job->job_id}}">
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
                        body: function( data, row, col, node ) {
                            if (col == 2) {
                                return $('#jobList').DataTable()
                                .cell( {row: row, column: 3} )
                                .nodes()
                                .to$()
                                .find(':selected')
                                .text().trim();
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
                                .text().trim();
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
                                .text().trim();
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
                                .text().trim();
                            } else {
                                return data;
                            }
                        }
                    },
                },
            },
            ],
        });
    });

    /*change job status*/
    /*$(".jobType").change(function() {
        var jobStatusId = $(this).val();
        var jobId = $(this).attr('data-id');
        $("#loader").show();
        $.ajax({
            url:'{{ route('changejobstatus') }}',
            data:{jobStatusId:jobStatusId,jobId:jobId},
            type: 'post',
            dataType: 'json',
            success:function(data){
                $("#loader").hide();
                if(data.key != 1 ) {
                    var table = $('#jobList').DataTable();
                    table.row('.changestatus_'+jobId).remove().draw(false);
                }
                notify('Job Status has been Changed Successfully.','blackgloss');
            }
        });
    });*/

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
            //$('#statusWiseJobModel').modal('show');
            
        }else if(jobStatusId == 6) {
            $('.addInstallingDateTime').show();
            $('.addDeliveryDateTime').hide();
            $('.addStoneInstallingDateTime').hide();
            //$('#statusWiseJobModel').modal('show');
            
        }else if(jobStatusId == 7) {
            $('.addStoneInstallingDateTime').show();
            $('.addDeliveryDateTime').hide();
            $('.addInstallingDateTime').hide();
            //$('#statusWiseJobModel').modal('show');
            
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
                if(data.key != 1 ) {
                    var table = $('#jobList').DataTable();
                    table.row('.changestatus_'+jobId).remove().draw(false);
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

    /* For select 2*/
    $(".select2").select2();
    $('.selectpicker').selectpicker();

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

    @if(Session::has('successMessage'))
    notify('{{  Session::get('successMessage') }}','blackgloss');
    {{ Session::forget('successMessage') }}
    @endif
</script>
@stop