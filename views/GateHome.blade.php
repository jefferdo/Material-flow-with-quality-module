@extends('layouts.DashL')


@section('content')

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div id="errorbox">
                @if ($error != null)
                <div class="pt-1">
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                        role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ $error }}
                    </div>
                </div>
                @endif
            </div>
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">{{ $title }}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">

            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <a href="/NewGP" class="btn btn-sm btn-blue waves-effect waves-light float-right">
                                <i class="mdi mdi-plus-circle"></i> Add Gatepass
                            </a>
                            <h4 class="header-title">Gate passes</h4>
                            <p class="text-muted font-13 mb-4">
                            </p>
                            <table id="basic-datatable" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>GP ID</th>
                                        <th>State</th>
                                        <th>Date</th>
                                        <th>Added By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><b>GP20191230001</b></td>
                                        <td><span class="badge badge-success">Open</span></td>
                                        <td>2019-10-12</td>
                                        <td>Jeewaka</td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);"
                                                    class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                    data-toggle="dropdown" aria-expanded="false"><i
                                                        class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#"><i
                                                            class="mdi mdi-star mr-2 font-18 text-muted vertical-middle"></i>View</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="mdi mdi-check-all mr-2 text-muted font-18 vertical-middle"></i>Close</a>
                                                    <a class="dropdown-item" href="#"><i
                                                            class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
</div>

@endsection