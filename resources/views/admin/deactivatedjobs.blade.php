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
                            <th>Job Id</th>
                            <th>Job Status</th>
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
                exportOptions: {
                    columns: [ 1,2,3,4,5 ],
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
                    columns: [ 1,2,3,4,5 ],
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
                    columns: [ 1,2,3,4,5],
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
                    columns: [ 1,2,3,4,5 ],
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
            ],
        });

/*change job status*/
$(".jobType").change(function() {
    var jobStatusId = $(this).val();
    var jobId = $(this).attr('data-id');
    var checkJob;
    $("#loader").show();
    $.ajax({
        url:'{{ route('changejobstatus') }}',
        data:{jobStatusId:jobStatusId,jobId:jobId,checkJob:2},
        type: 'post',
        dataType: 'json',
        success:function(data){
            if(data.key == 1 ) {
                $("#loader").hide();
                notify('Job Status has been Changed Successfully.','blackgloss');
            }else {
                location.reload();
            }
        }
    });
});
});

/* For select 2*/
$(".select2").select2();

@if(Session::has('successMessage'))
notify('{{  Session::get('successMessage') }}','blackgloss');
{{ Session::forget('successMessage') }}
@endif
</script>
@stop