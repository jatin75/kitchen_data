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
       <h4 class="page-title">Clients</h4>
   </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0 pull-left">All CLIENTS</h3>

            <a href="{{route('addclient')}}" class="btn btn-success btn-rounded waves-effect waves-light pull-right m-b-15 m-r-15"><span>Add Client</span> <i class="fa fa-plus m-l-5"></i></a>
            <div class="table-responsive">
                <table id="jobList" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Actions</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Client Id</th>
                            <th>Company Name</th>
                            <th>Email Address</th>
                            <th>Phone Number</th>
                            <th>Contact Preference</th>
                            <th>Street Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Zipcode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientDetails as $client)
                        <tr>
                            <td class="text-center">
                                <a data-toggle="tooltip" data-placement="top" title="Edit Job" class="btn btn-info btn-circle" href="{{route('editclient',['client_id' => $client->client_id])}}">
                                    <i class="ti-pencil-alt"></i>
                                </a>
                                <a class="btn btn-danger btn-circle" onclick="return confirm('You can\'t reactivate client. Are you sure you want to remove this client?');" href="{{route('deleteclient',['client_id' => $client->client_id])}}" data-toggle="tooltip" data-placement="top" title="Remove Client"><i class="ti-trash"></i> </a>
                            </td>
                            <td>{{strtoupper($client->first_name)}}</td>
                            <td>{{strtoupper($client->last_name)}}</td>
                            <td>{{$client->client_id}}</td>
                            <td>{{$client->comapny_name}}</td>
                            <td>{{$client->email}}</td>
                            @if(empty($client->phone_number) || $client->phone_number == "")
                            <td>{{'--'}}</td>
                            @else
                            <td>{{substr_replace(substr_replace(substr_replace($client->phone_number, '(', 0,0), ') ', 4,0), ' - ', 9,0) }}</td>
                            @endif
                            @if($client->contact_preference == 1)
                            <td><span class="label label-info">{{'Email'}}</span></td>
                            @elseif($client->contact_preference == 2)
                            <td><span class="label label-success">{{'App'}}</span></td>
                            @elseif($client->contact_preference == 3)
                            <td><span class="label label-danger">{{'Phone'}}</span></td>
                            @else
                            <td>{{'--'}}</td>
                            @endif
                            <td>{{$client->address_1}}</td>
                            <td>{{$client->city}}</td>
                            <td>{{$client->state}}</td>
                            <td>{{$client->zipcode}}</td>
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
                exportOptions: {columns: [ 1,2,3,4,5,6,7,8,9,10,11 ]},
            },
            {
                extend: 'excel',
                title: value,
                exportOptions: {columns: [ 1,2,3,4,5,6,7,8,9,10,11 ]},
            },
            {
                extend: 'pdf',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: value,
                exportOptions: {columns: [ 1,2,3,4,5,6,7,8,9,10,11 ]},
            },
            {
                extend: 'print',
                title: value,
                exportOptions: {columns: [ 1,2,3,4,5,6,7,8,9,10,11 ]},
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