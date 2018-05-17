@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css')}}" />
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
<!--Job status change event model-->
<div class="modal fade" id="statusWiseJobModel" tabindex="-1" data-backdrop="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel1">Add&nbsp;Delivery Date and Time</h4>
            </div>
            <div class="modal-body">
                <div class="form-body form-material">
                    <input type="hidden" id="hiddenChangeJobId" name="hiddenChangeJobId">
                    <input type="hidden" id="hiddenChangeJobStatus" name="hiddenChangeJobStatus">
                    <input type="hidden" id="hiddenChangeJobActiveStatus" name="hiddenChangeJobActiveStatus">
                    <form method="POST" id="formAddDeliveryDateTime">
                        {{ csrf_field() }}
                        <div class="row m-t-10">
                            <div class="row col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-md-12">Select Date of Deliver and Time to delivery </label>
                                        <div class="">
                                            <div class="col-md-4">
                                                <input type="text" name="deliveryDate" id="deliveryDate" class="form-control complex-colorpicker" placeholder="mm/dd/yyyy"
                                                maxlength="10" value="{{ $jobDetails->delivery_date or '' }}">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="input-group clockpicker " data-placement="top">
                                                    <input type="text" id="deliveryTime" name="deliveryTime" class="form-control" placeholder="hh:mm" value="{{ $jobDetails->delivery_time or '' }}">
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
        var deliveryDate = ''; var deliveryTime = '';

        if (window.matchMedia('(max-width: 767px)').matches) {
            var activeJobStatus = $(".toolbarmenu_active").attr("data-id");
        } else {
            var activeJobStatus = $(".toolbaractive").attr("data-id");
        }
        if(jobStatusId == 5) {
            $('#statusWiseJobModel').modal('show');
            $("#hiddenChangeJobId").val(jobId);
            $("#hiddenChangeJobStatus").val(jobStatusId);
            $("#hiddenChangeJobActiveStatus").val(activeJobStatus);
        }else {
            changestatuswisejob(jobStatusId,jobId,activeJobStatus,deliveryDate,deliveryTime);
        }
    });

    function changestatuswisejob(jobStatusId,jobId,activeJobStatus,deliveryDate,deliveryTime) {
        $("#loader").show();
        $.ajax({
            url:'{{ route('changejobstatus') }}',
            data:{jobStatusId:jobStatusId,jobId:jobId,deliveryDate:deliveryDate,deliveryTime:deliveryTime},
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
        var deliveryDate = $("#deliveryDate").val();
        var deliveryTime = $("#deliveryTime").val();
        if(jobStatusId == 5 && deliveryDate != '' && deliveryTime != '') {
            changestatuswisejob(jobStatusId,jobId,activeJobStatus,deliveryDate,deliveryTime);
        }
    });

    /* For select 2*/
    $(".select2").select2();

    /*Date picker*/
    $('#deliveryDate').datepicker({
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