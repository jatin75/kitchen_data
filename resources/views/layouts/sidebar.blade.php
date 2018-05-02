<style type="text/css">
/*.p-t-27 {
    padding-top: 27px!important;
    }*/
    .modal-footer {
        padding-bottom: 0px !important;
        margin-bottom: 0px !important;
    }
</style>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
        <div class="user-profile">
            <div class="dropdown user-pro-body">
                <div data-toggle="tooltip" data-placement="top" title="Edit Image">
                    <img id="userImage" src="{{asset('plugins/images/kitchen/A&S.jpg')}}" alt="user-img" class="" data-toggle="modal" data-target="#imageModel" data-ui-toggle-class="fade-right">
                </div>
                {{-- <a id="sessionName" style="text-transform: uppercase;" href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Session::get('name')}}<span class="caret"></span></a>
                <ul class="dropdown-menu animated flipInY">
                    
                    @if(Session::get('login_type_id') == 1 || Session::get('login_type_id') == 2)
                    <li><a href="{{ route('employeeprofile',['email'=>Session::get('email')]) }}"><i class="ti-user"></i> My Profile &amp; Setting</a></li>
                    @endif
                    @if(Session::get('login_type_id') == 9)
                    <li><a href="{{ route('clientprofile',['email'=>Session::get('email')]) }}"><i class="ti-user"></i> My Profile &amp; Setting</a></li>
                    @endif
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>  --}}
            </div>
        </div>
        <ul class="nav" id="side-menu">
            <li class="nav-small-cap m-t-10">&nbsp;&nbsp;Main Menu</li>
            <li>
                <a href="{{route('dashboard')}}" class="waves-effect {!! (Request::is('/') ? 'active' : '') !!}">
                    <i class="ti-home fa-fw" data-icon="v"></i>
                    <span class="hide-menu"> Dashboard </span>
                </a>
                @if(Session::get('login_type_id') != 9)    
            </li>
            <li>
                <a id="staff" href="javascript:void(0);" class="waves-effect {!! (Request::is('jobs/*') ? 'active' : '') !!}"><i data-icon=")" class="ti-clipboard fa-fw"></i>
                    <span class="hide-menu">Jobs<span class="fa arrow"></span></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li> <a href="{{ route('activejobs') }}">Active</a></li>
                    <li> <a href="{{ route('deactivatedjobs') }}">Deactived</a></li>
                </ul>
            </li>
            <li>
                <a id="subscribers" href="{{ route('showemployees') }}" class="waves-effect {!! (Request::is('employees/*') ? 'active' : '') !!}">
                    <i class="icon-people fa-fw" data-icon="l"></i>
                    <span class="hide-menu"> Employees </span>
                </a>
            </li>
            <li>
                <a id="prospects" href="javascript:void(0);" class="waves-effect {!! (Request::is('administration/*')  ? 'active' : '') !!}">
                    <i class="ti-crown fa-fw" data-icon="l"></i>
                    <span class="hide-menu"> Administration <span class="fa arrow"></span></span>
                </a>
                <ul class="nav nav-second-level collapse">
                    <li> <a href="{{ route('showclientcompany') }}">Company</a></li>
                </ul>
            </li>
            <li>
                <a id="accounts" href="{{ route('showclients') }}" class="waves-effect {!! (Request::is('clients/*') ? 'active' : '') !!}"><i data-icon=")" class="icon-user fa-fw"></i>
                    <span class="hide-menu">Clients</span>
                </a>
            </li>
            <li>
                <a id="invoices" href="{{ route('showreports') }}" class="waves-effect {!! (Request::is('reports/*')? 'active' : '') !!}">
                    <i class="ti-notepad fa-fw" data-icon="l"></i>
                    <span class="hide-menu">Reports</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
<!--model-->
<div class="modal fade" id="imageModel" tabindex="-1" data-backdrop="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel1">Edit&nbsp;Image</h4>
            </div>
            <div class="modal-body">
                <div class="form-body">
                    <form method="POST" id="formAdmin" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" id="hiddenEmail" name="hiddenEmail" value="{{Session::get('email')}}">
                        <div class="row m-t-10">
                            <div class="row col-md-12">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="col-sm-12">Choose an image file..</label>
                                        <div class="col-sm-12">
                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput">
                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-addon btn btn-default btn-file">
                                                    <span class="fileinput-new"><i class="fa fa-upload"></i></span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" name="editProfilePic" id="editProfilePic">
                                                </span>
                                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               {{--  <div class="col-md-3">
                                    <div class="form-group p-t-27">
                                        <button type="submit" class="btn btn-success">Upload</button>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="modal-footer form-group">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <button id="profile_btn" type="submit" class="btn btn-success">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/.model-->
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*$(document).ready(function() {
        var hidden_email = $('#hiddenEmail').val();
        $.ajax({
            url:'',
            data:{hidden_email:hidden_email},
            type:'post',
            dataType:'json',
            success: function(data)
            {
                if(data.key == 1)
                {
                    $("#userImage").attr('src', data.thumb_file);
                    $('#loader').hide();
                }
                else
                {
                    $('#loader').hide();
                }
            },
        });

        $('#imageModel').on('hidden.bs.modal', function () {
            $('#formAdmin').bootstrapValidator('resetForm', true);
            $('.fileinput').fileinput('clear');
        });
    });*/

    $('#formAdmin').on('success.form.bv', function(e) {
        e.preventDefault();
        $('#loader').show();
        $('#imageModel').modal('hide');
        var formData = new FormData(this);
        $.ajax({
            url:'',
            data:formData,
            type:'post',
            dataType:'json',
            cache: false,
            contentType: false,
            processData: false,
            success: function(data)
            {
                if(data.key == 1)
                {
                    $("#userImage").attr('src', data.thumb_file + "?" + Math.random());
                    $('#loader').hide();
                    notify('Image edited successfully.','blackgloss');
                }
                else
                {
                    $('#loader').hide();
                    notify('Something went wrong. Please try again.','blackgloss');
                }
            },
        });
    });
</script>