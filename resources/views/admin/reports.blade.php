@extends('layouts/main')
@section('pageSpecificCss')
<link type="text/css" rel="stylesheet" href="{{asset('plugins/bower_components/custom-select/custom-select.min.css')}}" />
@stop
@section('content')
<style type="text/css">
.p-t-27 {
    padding-top: 27px!important;
}
</style>
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Reports</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form id="formReports" method="post" action="{{ route('downloadjobexcel') }}">
                    {{ csrf_field() }}
                    <!--header-->
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <a class="btn btn-default btn-circle" href="{{URL::previous()}}" title="Previous"><i class="ti-arrow-left"></i> </a>
                        </div>
                    </div>
                    <!--/.header-->
                    <div class="row m-t-10">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"><b>JOB STATUS</b></label>
                                <select id="jobStatus" name="jobStatus" class="form-control select2">
                                    <option value="0">All</option>
                                    @foreach($jobStatusList as $status)
                                    <option value="{{ $status->job_status_id }}">{{ $status->job_status_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-sx-3">
                                    <div class="form-group p-t-27">
                                        <button type="submit" class="btn btn-info">DOWNLOAD REPORT&nbsp;<i class="fa fa-download"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
@section('pageSpecificJs')
<script type="text/javascript" src="{{asset('plugins/bower_components/custom-select/custom-select.min.js')}}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*$('#formReports').on('submit', function(e) {
        e.preventDefault();
        $('#loader').show();
        var status_id = $('#jobStatus').val();
        $.ajax({
            url:'{{ route('downloadjobexcel') }}',
            data:{status_id:status_id},
            type:'post',
            dataType:'json',
            success: function(data)
            {
                if(data.key == 1)
                {
                    $('#loader').hide();
                    notify('Report has been downloaded successfully','blackgloss');
                }
            }
        });
    });*/

    /* For select 2*/
    $(".select2").select2();
</script>
@stop