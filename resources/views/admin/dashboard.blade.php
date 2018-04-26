@extends('layouts/main')
@section('content')
<!-- Page Content -->

    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Kitchen Dashboard</h4>
            </div>
            <!--<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="https://themeforest.net/item/elite-admin-responsive-dashboard-web-app-kit-/16750820" target="_blank" class="btn btn-danger pull-right m-l-20 btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">Buy Now</a>
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">Kitchen Dashboard</li>
                </ol>
            </div>-->
            <!-- /.col-lg-12 -->
        </div>
        <!--row -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-user bg-danger"></i>
                        <div class="bodystate">
                            <h4>3564</h4>
                            <span class="text-muted">New Customers</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-shopping-cart bg-info"></i>
                        <div class="bodystate">
                            <h4>342</h4>
                            <span class="text-muted">New Products</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-wallet bg-success"></i>
                        <div class="bodystate">
                            <h4>56%</h4>
                            <span class="text-muted">Today's Profit</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="white-box">
                    <div class="r-icon-stats">
                        <i class="ti-star bg-inverse"></i>
                        <div class="bodystate">
                            <h4>56%</h4>
                            <span class="text-muted">New Leads</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/row -->
        <!--row -->
        <div class="row">
            <div class="col-md-5 col-lg-4 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">Leads by Source</h3>
                    <div id="morris-donut-chart" class="ecomm-donute" style="height: 317px;"></div>
                    <ul class="list-inline m-t-30 text-center">
                        <li class="p-r-20">
                            <h5 class="text-muted"><i class="fa fa-circle" style="color: #fb9678;"></i> Ads</h5>
                            <h4 class="m-b-0">8500</h4>
                        </li>
                        <li class="p-r-20">
                            <h5 class="text-muted"><i class="fa fa-circle" style="color: #01c0c8;"></i> Tredshow</h5>
                            <h4 class="m-b-0">3630</h4>
                        </li>
                        <li>
                            <h5 class="text-muted"> <i class="fa fa-circle" style="color: #4F5467;"></i> Web</h5>
                            <h4 class="m-b-0">4870</h4>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-7 col-lg-8 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title">Top Products sales</h3>
                    <ul class="list-inline text-center">
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #00bfc7;"></i>iMac</h5>
                        </li>
                        <li>
                            <h5><i class="fa fa-circle m-r-5" style="color: #b4becb;"></i>iPhone</h5>
                        </li>
                    </ul>
                    <div id="morris-area-chart2" style="height: 370px;"></div>
                </div>
            </div>
        </div>
        <!-- row -->
        <!-- .row -->
        <div class="row">
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title"><small class="pull-right m-t-10 text-success"><i class="fa fa-sort-asc"></i> 18% High then last month</small> Total Leads</h3>
                    <div class="stats-row">
                        <div class="stat-item">
                            <h6>Overall Growth</h6>
                            <b>80.40%</b></div>
                        <div class="stat-item">
                            <h6>Montly</h6>
                            <b>15.40%</b></div>
                        <div class="stat-item">
                            <h6>Day</h6>
                            <b>5.50%</b></div>
                    </div>
                    <div id="sparkline8" class="minus-mar"></div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title"><small class="pull-right m-t-10 text-danger"><i class="fa fa-sort-desc"></i> 18% High then last month</small>Total Vendor</h3>
                    <div class="stats-row">
                        <div class="stat-item">
                            <h6>Overall Growth</h6>
                            <b>80.40%</b></div>
                        <div class="stat-item">
                            <h6>Montly</h6>
                            <b>15.40%</b></div>
                        <div class="stat-item">
                            <h6>Day</h6>
                            <b>5.50%</b></div>
                    </div>
                    <div id="sparkline9" class="minus-mar"></div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="white-box">
                    <h3 class="box-title"><small class="pull-right m-t-10 text-success"><i class="fa fa-sort-asc"></i> 18% High then last month</small>Invoice Generate</h3>
                    <div class="stats-row">
                        <div class="stat-item">
                            <h6>Overall Growth</h6>
                            <b>80.40%</b></div>
                        <div class="stat-item">
                            <h6>Montly</h6>
                            <b>15.40%</b></div>
                        <div class="stat-item">
                            <h6>Day</h6>
                            <b>5.50%</b></div>
                    </div>
                    <div id="sparkline10" class="minus-mar"></div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>    
        @stop
        