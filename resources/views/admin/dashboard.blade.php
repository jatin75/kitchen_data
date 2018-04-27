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
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Kitchen Dashboard</h4>
        </div>
            <!--<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="https://themeforest.net/item/elite-admin-responsive-dashboard-web-app-kit-/16750820" target="_blank" class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Buy Now</a>
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">Kitchen Dashboard</li>
                </ol>
            </div>-->
            <!-- /.col-lg-12 -->
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-bag bg-danger"></i>
                        <div class="bodystate">
                            <h4>200</h4>
                            <span class="text-muted">All Jobs</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-star bg-info"></i>
                        <div class="bodystate">
                            <h4>100</h4>
                            <span class="text-muted">New Jobs</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-blackboard bg-success"></i>
                        <div class="bodystate">
                            <h4>50</h4>
                            <span class="text-muted">Actived Jobs</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-na bg-inverse"></i>
                        <div class="bodystate">
                            <h4>50</h4>
                            <span class="text-muted">Deactived Jobs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/row -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-wrapper collapse in" aria-expanded="true">
                        <div class="panel-body">
                            <ul class="nav nav-pills m-b-30">
                                <li data-id="0" class="nav-item active"> <a href="javascript:void(0)" onclick="getJobDetailsList(0)" class="nav-link" data-toggle="tab" aria-expanded="true">All</a> </li>
                                @foreach($jobTypeDetails as $jobType)
                                <li data-id="{{ $jobType->job_status_id }}" class="nav-item"> <a href="javascript:void(0)" onclick="getJobDetailsList({{ $jobType->job_status_id }})" class="nav-link" data-toggle="tab" aria-expanded="true">{{ $jobType->job_status_name }}</a> </li>
                                @endforeach
                            </ul>
                            <div class="table-responsive jobDetailList">
                                {{-- <table id="jobList" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Job Name</th>
                                            <th>Client Name</th>
                                            <th>Job Super Name</th>
                                            <th>Start Date</th>
                                            <th>Expected Completion Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table> --}}
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
            var date = $('#formatedDate').val();
            var value = 'Kitchen_job' + date;
            $('#jobList').DataTable({
                dom: 'Bfrtip',
                buttons: [
                {
                    extend: 'csv',
                    title: value,
                    exportOptions: {columns: [ 1,2,3,4,5 ]},
                },
                {
                    extend: 'excel',
                    title: value,
                    exportOptions: {columns: [ 1,2,3,4,5 ]},
                },
                {
                    extend: 'pdf',
                    pageSize: 'LEGAL',
                    title: value,
                    exportOptions: {columns: [ 1,2,3,4,5]},
                },
                {
                    extend: 'print',
                    title: value,
                    exportOptions: {columns: [ 1,2,3,4,5 ]},
                },
                ],
            });

            /*get job detail list*/
            getJobDetailsList(0);

        });

        /*get job detail list*/
        function getJobDetailsList(jobStatusId){
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
                       $('#jobList').DataTable();
                   }
               }
           });
        }
    </script>
    @stop
