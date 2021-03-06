@extends('layouts.kanbanL')


@section('content')

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 style="text-transform:capitalize" class="page-title">Create New Waterfall for PO No.:
                            {{ $id }}</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <div class="table-responsive">
                            <table class="table table-centered table-borderless table-striped mb-0">
                                <tbody>
                                    <tr class="text">
                                        <td style="width: 35%;">Customer</td>
                                        <td><span>{{ $cus }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Initiated Date</td>
                                        <td><span>{{ $cdt }}</span> </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <form action="/addWF" method="post">
                                                <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <div class="row">
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect waves-light mb-2">Mark as
                                                        Passed or Rejected</button>
                                                    <div class="p-1"></div>
                                                    <a href="/" class="btn btn-danger waves-effect waves-light mb-2">Go
                                                        to
                                                        home</a>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- end .table-responsive -->
                    </div> <!-- end card-box -->
                </div><!-- end col -->
            </div>
            <!-- end row -->
            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <input type="hidden" name="poid" id="poid" value="{{ $id }}">
                        <button type="button" id='addWF'
                            class="btn btn-sm btn-blue waves-effect waves-light float-right">
                            <i class="mdi mdi-plus-circle"></i> Add a Waterfall
                        </button>
                        <h4 style="text-transform:capitalize" class="header-title mb-4">Waterfall
                            Details</h4>
                        <h5 style="text-transform:capitalize" class="header-subtitle">Currently available Waterfalls
                            : <span>{{ $now }}</span></h5>
                        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                            id="tickets-table">
                            <thead>
                                <tr>
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
                                @foreach ($wfs as $wf)
                                <tr style="text-transform:capitalize">
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
                                            <form action="/buildWF" method="post">
                                                <input type="hidden" name="id" value="{{ $wf['id'] }}">
                                                <button class="dropdown-item" type="submit">
                                                    <i class="mdi mdi-shopping mr-2 text-muted font-18 vertical-middle"></i>
                                                    Modify Sequence
                                                </button>
                                            </form>
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
                    </div>
                </div><!-- end col -->
            </div>

            <div id="errorbox"></div>

        </div> <!-- container -->

    </div> <!-- content -->

</div>

@endsection