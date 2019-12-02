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
                        <h4 class="page-title">Create New Gate Pass</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <form action="/addGP" method="post">
                <div class="row">

                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="button" class="btn btn-sm btn-blue waves-effect waves-light float-right">
                                    <i class="mdi mdi-plus-circle"></i> Add Gatepass
                                </button>
                                <h4 class="header-title">Gate passes</h4>
                                <p class="text-muted font-13 mb-4">
                                </p>
                                <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                                    id="tickets-table">
                                    <thead>
                                        <tr>
                                            <th>Selection</th>
                                            <th>
                                                Waterfall ID
                                            </th>
                                            <th>Shrinkage</th>
                                            <th>No. Rolls</th>
                                            <th>Added Date</th>
                                            <th>Added by</th>
                                            <th class="hidden-sm">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="WFtable">
                                        <tr style="text-transform:capitalize">
                                            <td>
                                                <div class="center">
                                                    <input type="checkbox" class="" name="" id="">
                                                </div>
                                            </td>
                                            <td><b>Sample</b></td>

                                            <td>
                                                Sample
                                            </td>

                                            <td>
                                                Sample
                                            </td>

                                            <td>
                                                Sample
                                            </td>

                                            <td>
                                                Sample
                                            </td>

                                            <td>
                                                <div class="btn-group dropdown">
                                                    <a href="javascript: void(0);"
                                                        class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="mdi mdi-dots-horizontal"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @foreach ($wfs as $wf)
                                        <tr style="text-transform:capitalize">
                                            <td>
                                                <input type="checkbox" class="custom-control-input" id="checkmeout0">
                                            </td>
                                            <td><b>{{ $wf['id'] }}</b></td>

                                            <td>
                                                {{ $wf['shrk'] }}
                                            </td>

                                            <td>
                                                {{ $wf['nor'] }}
                                            </td>

                                            <td>
                                                {{ $wf['date'] }}
                                            </td>

                                            <td>
                                                {{ $wf['user'] }}
                                            </td>

                                            <td>
                                                <div class="btn-group dropdown">
                                                    <a href="javascript: void(0);"
                                                        class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="mdi mdi-dots-horizontal"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
            </form>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
</div>

@endsection