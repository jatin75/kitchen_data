@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
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
                        <div class="nav_toggle"><i style="border:  1px solid #d1d1d1; padding:  6px; border-radius: 3px;cursor: pointer;" class="ti-menu"></i></div>
                        <ul class="nav nav-pills m-b-30" id="nav_menu">
                            <li data-id="0" class="nav-item active"> <a href="javascript:void(0)" onclick="getJobDetailsList(0)" class="nav-link" data-toggle="tab" aria-expanded="true">All</a> </li>
                            @foreach($jobTypeDetails as $jobType)
                            <li data-id="{{ $jobType->job_status_id }}" class="nav-item"> <a href="javascript:void(0)" onclick="getJobDetailsList({{ $jobType->job_status_id }})" class="nav-link" data-toggle="tab" aria-expanded="true">{{ strtoupper($jobType->job_status_name) }}</a> </li>
                            @endforeach
                        </ul>
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
                        extend: 'csv',
                        title: value,
                        exportOptions: {columns: [ 1,2,3,4 ]},
                    },
                    {
                        extend: 'excel',
                        title: value,
                        exportOptions: {columns: [ 1,2,3,4 ]},
                    },
                    {
                        extend: 'pdf',
                        pageSize: 'LEGAL',
                        title: value,
                        exportOptions: {columns: [ 1,2,3,4]},
                    },
                    {
                        extend: 'print',
                        title: value,
                        exportOptions: {columns: [ 1,2,3,4 ]},
                    },
                    ],
                });
               }
           }
       });
    }

    /*job status menu*/
    $(".nav_toggle").click(function(){
        $("#nav_menu").toggle();
    });
    
</script>
@stop
