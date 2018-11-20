@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/jquery.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/datatables/buttons.dataTables.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
<link type="text/css" rel="stylesheet" href="{{asset('assets/css/pages/notes.css')}}" />
@stop
@section('content')
<div class="container-fluid">
    <input type="hidden" id="formatedDate" name="formatedDate" value="{{ date('Y_m_d') }}">
    <div class="row bg-title">
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
       <h4 class="page-title">Notes</h4>
   </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-20 pull-left">All Notes</h3>

            <div class="table-responsive">
                <table id="jobNoteList" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Job Note</th>
                            <th>Job Id</th>
                            <th>Job Name</th>
                            <th>Updated By</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobNotesList as $jobNotes)
                            <tr>
                                <td><div class="word-wrap"> {{ $jobNotes->job_note }} </div></td>
                                <td> {{ $jobNotes->job_id }}</td>
                                <td> {{ $jobNotes->job_title }}</td>
                                <td> {{ $jobNotes->name }}</td>
                                <td>{{ date('m/d/Y',strtotime($jobNotes->created_at))}}</td>
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
        var value = 'Kitchen_jobnotes_' + date;
        $('#jobNoteList').DataTable({
            dom: 'Bfrtip',
            order: [[ 4, "desc" ]],
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
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: value,
                exportOptions: {columns: [ 0,1,2,3,4 ]},
            },
            {
                extend: 'print',
                title: value,
                exportOptions: {columns: [ 0,1,2,3,4 ]},
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