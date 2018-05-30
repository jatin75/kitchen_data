<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
        <div class="top-left-part logo_wrapper">
           {{--  <a class="logo hidden-xs hidden-sm hidden-md" href="">
                <img src="{{asset('plugins/images/kitchen/A&S.jpg')}}" width="60" height="60" />
            </a>
            <a class="logo hidden-lg" href="" style="display: none !important;">
                <img src="{{asset('plugins/images/kitchen/A&S.jpg')}}" width="40"/>
            </a> --}}
        </div>
        <ul class="nav navbar-top-links navbar-left hidden-xs">
            <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
        </ul>
    </div>
    <div class="user_profile_wrapper">
        <a id="sessionName" style="text-transform: uppercase;" href="#" class="font-normal dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Session::get('name')}}<span class="caret"></span></a>
        <ul class="dropdown-menu animated flipInY">

            @if(Session::get('login_type_id') == 1 || Session::get('login_type_id') == 2)
            <li><a href="{{ route('employeeprofile',['email'=>Session::get('employee_id')]) }}"><i class="ti-user"></i> My Profile &amp; Setting</a></li>
            @endif
            @if(Session::get('login_type_id') == 9)
            <li><a href="{{ route('clientprofile',['email'=>Session::get('employee_id')]) }}"><i class="ti-user"></i> My Profile &amp; Setting</a></li>
            @endif
            <li role="separator" class="divider"></li>
            <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
        </ul>
    </div>
</nav>

<style>
.user_profile_wrapper{
    position: absolute;
    right: 20px;
    top: 20px;
    font-size: 16px;
    color: #fff !important;
    width: 12%;
    text-align: right;
}
.user_profile_wrapper a{
    color: #535354;
}
.dropdown-menu > li > a {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: 400;
    line-height: 1.42857143;
    color: #333;
    white-space: nowrap;
    font-size: 13px;
    padding: 4px 8px !important;
}
.dropdown-toggle::after {
    display: inline-block !important;
    width: 0;
    height: 0;
    margin-left: .3em;
    vertical-align: middle;
    content: "";
    border-top: .3em solid;
    border-right: .3em solid transparent;
    border-left: .3em solid transparent;
}
</style>