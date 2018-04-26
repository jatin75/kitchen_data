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
                            <th>Client Name</th>
                            <th>Start Date</th>
                            <th>Expected Completion Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobDetails as $job)
                        <tr>
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
                            <td>{{strtoupper($job->client_name)}}</td>
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
        var value = 'Kitchen_employee' + date;
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
    });

    @if(Session::has('successMessage'))
    notify('{{  Session::get('successMessage') }}','blackgloss');
    {{ Session::forget('successMessage') }}
    @endif
</script>
@stop