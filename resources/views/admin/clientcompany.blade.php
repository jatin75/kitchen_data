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
       <h4 class="page-title">Administration > Company</h4>
   </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="white-box">
            <h3 class="box-title m-b-0 pull-left">All COMPANY</h3>

            <a href="{{route('addclientcompany')}}" class="btn btn-success btn-rounded waves-effect waves-light pull-right m-b-15 m-r-15"><span>Add Company</span> <i class="fa fa-plus m-l-5"></i></a>
            <div class="table-responsive">
                <table id="clientCompanyList" class="display nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Actions</th>
                            <th>Name</th>
                            <th>Company Id</th>
                            <th>Phone Number</th>
                            <th>Address</th>
                            <th>Email Address</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientCompanyList as $comapnyList)
                        <tr>
                            <td class="text-center">
                                <a data-toggle="tooltip" data-placement="top" title="Edit Company" class="btn btn-info btn-circle" href="{{route('editclientcompany',['company_id' => $comapnyList->company_id])}}">
                                    <i class="ti-pencil-alt"></i>
                                </a>
                                <a class="btn btn-danger btn-circle" onclick="return confirm(' Are you sure you want to delete this company?');" href="{{route('deleteclientcompany',['company_id' => $comapnyList->company_id])}}" data-toggle="tooltip" data-placement="top" title="Delete Company"><i class="ti-trash"></i> </a>
                            </td>
                            <td>{{strtoupper($comapnyList->name)}}</td>
                            <td>{{$comapnyList->company_id}}</td>
                            @if(empty($comapnyList->phone_number) || $comapnyList->phone_number == "")
                            <td>{{'--'}}</td>
                            @else
                            <td>{{substr_replace(substr_replace(substr_replace($comapnyList->phone_number, '(', 0,0), ') ', 4,0), ' - ', 9,0) }}</td>
                            @endif
                            <td>{{$comapnyList->address_1}}</td>
                            <td>{{$comapnyList->email}}</td>
                            <td>{{ date('m/d/Y',strtotime($comapnyList->created_at))}}</td>
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
        var value = 'Kitchen_Client_Company_' + date;
        $('#clientCompanyList').DataTable({
            dom: 'Bfrtip',
            buttons: [
            {
                extend: 'csv',
                title: value,
                exportOptions: {columns: [ 1,2,3,4,5,6 ]},
            },
            {
                extend: 'excel',
                title: value,
                exportOptions: {columns: [ 1,2,3,4,5,6 ]},
            },
            {
                extend: 'pdf',
                pageSize: 'LEGAL',
                title: value,
                exportOptions: {columns: [ 1,2,3,4,5,6]},
            },
            {
                extend: 'print',
                title: value,
                exportOptions: {columns: [ 1,2,3,4,5,6 ]},
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