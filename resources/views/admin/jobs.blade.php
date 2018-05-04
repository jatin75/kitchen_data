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
         <h4 class="page-title">Jobs > Active</h4>
     </div>
 </div>
 <div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0 pull-left">All JOBS</h3>

            <a href="{{route('addjob')}}" class="btn btn-success btn-rounded waves-effect waves-light pull-right m-b-15 m-r-15"><span>Add Job</span> <i class="fa fa-plus m-l-5"></i></a>
            <a href="{{route('viewjobdetails')}}" class="btn btn-success btn-rounded waves-effect waves-light pull-right m-b-15 m-r-15"><span>View Jobs</span> <i class="ti-eye m-l-5"></i></a>
            <div class="table-responsive">
                <table id="jobList" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Actions</th>
                            <th>Job Name</th>
                            <th>Job Id</th>
                            <th>Start Date</th>
                            <th>Expected Completion Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobDetails as $job)
                        <tr>
                            <td class="text-center">
                               {{--  <a data-toggle="tooltip" data-placement="top" title="View Job" class="btn btn-success btn-circle" href="">
                                    <i class="ti-eye"></i>
                                </a>  --}}
                                <a data-toggle="tooltip" data-placement="top" title="Edit Job" class="btn btn-info btn-circle" href="{{route('editjob',['job_id' => $job->job_id])}}">
                                    <i class="ti-pencil-alt"></i>
                                </a>
                                <span data-toggle="modal" data-target="#jobNotesModel">
                                    <a data-toggle="tooltip" data-placement="top" title="Add Job Notes" class="btn btn-warning btn-circle add-job-note jobnote{{ $job->job_id }}" data-id="{{ $job->job_id }}" data-note="{{ $job->job_notes }}">
                                        <i class="ti-plus"></i>
                                    </a>
                                </span>
                                <a class="btn btn-danger btn-circle" onclick="return confirm('Are you sure you want to deactivate this job?');" href="{{route('deactivatejob',['job_id' => $job->job_id])}}" data-toggle="tooltip" data-placement="top" title="Deactivate Job"><i class="ti-lock"></i> </a>
                            </td>
                            <td>{{$job->job_title}}</td>
                            <td>{{$job->job_id}}</td>
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
<!--model-->
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
                                        <label class="col-md-12">Add job note</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control" rows="5" name="jobNote" id="jobNote" placeholder="Enter notes..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer form-group">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button type="submit" id="jobNoteSubmit" class="btn btn-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.model-->
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
        var value = 'Kitchen_employee_' + date;
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
                exportOptions: {columns: [ 1,2,3,4 ]},
            },
            {
                extend: 'print',
                title: value,
                exportOptions: {columns: [ 1,2,3,4 ]},
            },
            ],
        });

        /*set job id on models*/
        $(".add-job-note").click(function(){
            $jobId = $(this).attr('data-id');
            $jobNote = $(this).attr('data-note');
            if($jobNote == '') {
                $('#jobNoteSubmit').text('Add');
            }else {
                $('#jobNoteSubmit').text('Update');
            }
            $('#hiddenJobId').val($jobId);
            $('#jobNote').val($jobNote);
        });
    });

    $('#formAddNote').on('submit', function(e) {
        e.preventDefault();
        $('#loader').show();
        $('#jobNotesModel').modal('hide');
        var hidden_jobId = $('#hiddenJobId').val();
        var job_noteDesc = $('#jobNote').val();

        $.ajax({
            url:'{{ route('storejobnote') }}',
            data:{
                hidden_jobId:hidden_jobId,
                job_noteDesc:job_noteDesc,
            },
            type:'post',
            dataType:'json',
            success: function(data1)
            {
                if(data1.key == 1)
                {
                    $('#loader').hide();
                    $('.jobnote'+hidden_jobId).attr('data-note',job_noteDesc); //setter
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