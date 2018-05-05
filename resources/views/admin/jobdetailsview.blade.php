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
         <h4 class="page-title">Jobs</h4>
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
                            <th>Company Name</th>
                            <th>Job Id</th>
                            <th>Job Status</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobDetails as $job)
                        <tr>
                            <td class="text-center">
                                <span data-toggle="modal" data-target="#jobNotesModel">
                                    <a data-toggle="tooltip" data-placement="top" title="Add Job Notes" class="btn btn-warning btn-circle add-job-note jobnote{{ $job->job_id }}" data-id="{{ $job->job_id }}" data-note="{{ $job->job_notes }}">
                                        <i class="ti-plus"></i>
                                    </a>
                                </span>
                                <a data-toggle="tooltip" data-placement="top" title="View Pictures" class="btn btn-info btn-circle" href="javascript:void(0)">
                                    <i class="ti-gallery"></i>
                                </a>
                                <span data-toggle="modal" data-target="#Auditmodel">
                                    <a data-toggle="tooltip" data-placement="top" title="View Audit" class="btn btn-success btn-circle view-audit" data-id="{{ $job->job_id }}">
                                        <i class="ti-receipt"></i>
                                    </a>
                                </span>
                            </td>
                            <td>{{$job->name}}</td>
                            <td>{{$job->job_id}}</td>
                            <td>{{$job->job_status_name}}</td>
                            <td>{{ date('m/d/Y',strtotime($job->created_at))}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<!--Notes model-->
<div class="modal fade" id="jobNotesModel" tabindex="-1" data-backdrop="true" style="display: none;">
    <div class="modal-dialog model-lg">
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
<!--Audit model-->
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="Auditmodel" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Audit Job</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive" id="auditData">
                    {{-- <table id="auditList" class="display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Name Of Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                                <th>Date Of Edit</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>job_title</td>
                                <td>Momai Costruction</td>
                                <td>TATA Costruction</td>
                                <td>05/04/2018</td>
                                <th>Vivek Italiya</th>
                            </tr>
                        </tbody>
                    </table> --}}
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
        var value = 'Kitchen_job_' + date;
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

     /*set job id on models*/
     $(".add-job-note").click(function(){
            var jobId = $(this).attr('data-id');
            var jobNote = $(this).attr('data-note');
            if(jobNote == '') {
                $('#jobNoteSubmit').text('Add');
            }else {
                $('#jobNoteSubmit').text('Update');
            }
            $('#hiddenJobId').val(jobId);
            $('#jobNote').val(jobNote);
        });

        /*set audit*/
        $(".view-audit").click(function(){
            var jobId = $(this).attr('data-id');
                $.ajax({
                url:'{{ route('showaudittrail') }}',
                data:{
                    job_id:jobId,
                },
                type:'post',
                dataType:'json',
                success: function(data)
                {
                    if(data.key == 1)
                    {
                            $('#loader').hide();
                            console.log(data.audit_data);
                            $('#auditData').html(data.audit_data);
                            var date = $('#formatedDate').val();
                            var value = 'Audit_job_' + date;
                            $('#auditList').DataTable({
                            dom: 'Bfrtip',
                            buttons: [
                            {
                                extend: 'csv',
                                title: value,
                                exportOptions: {columns: [ 0,1,2,3,4 ]},
                            },
                            {
                                extend: 'excel',
                                title: value,
                                exportOptions: {columns: [ 0,1,2,3,4 ]},
                            },
                            {
                                extend: 'pdf',
                                pageSize: 'LEGAL',
                                title: value,
                                exportOptions: {columns: [ 0,1,2,3,4]},
                            },
                            {
                                extend: 'print',
                                title: value,
                                exportOptions: {columns: [ 0,1,2,3,4 ]},
                            },
                            ],
                        });
                    }
                }
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