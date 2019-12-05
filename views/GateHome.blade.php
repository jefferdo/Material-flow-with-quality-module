@extends('layouts.KanbanL')


@section('content')

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div id="errorbox">
                @if (@$_GET['error'] != null)
                <div class="pt-1">
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                        role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ @$_GET['error'] }}
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
                            <form action="/NewGP" method="post">
                                <input type="hidden" name="csrfk" value="{{ $csrfk }}">
                                <button type="submit" class="btn btn-sm btn-blue waves-effect waves-light float-right">
                                    <i class="mdi mdi-plus-circle"></i> Add Gatepass
                                </button>
                            </form>
                            <h4 class="header-title">Gate passes</h4>
                            <p class="text-muted font-13 mb-4">
                            </p>
                            <table id="tickets-table" class="table dt-responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>GP ID</th>
                                        <th>State</th>
                                        <th>Date</th>
                                        <th>Destination Address</th>
                                        <th>Added By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($GP as $item)
                                    <tr>
                                        <td><b>{{ $item['id'] }}</b></td>
                                        @if ($item['status'] == 0)
                                        <td><span class="badge badge-info">New</span></td>
                                        @elseif ($item['status'] == 1)
                                        <td><span class="badge badge-success">Open</span></td>
                                        @else
                                        <td><span class="badge badge-secondary">Closed</span></td>
                                        @endif
                                        <td>{{ $item['date'] }}</td>
                                        <td class="text">
                                            <address>{{ $item['destination'] }}</address>
                                        </td>
                                        <td class="text text-capitalize">{{ $item['uname'] }}</td>
                                        <td>
                                            <div class="btn-group dropdown">
                                                <a href="javascript: void(0);"
                                                    class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                    data-toggle="dropdown" aria-expanded="false"><i
                                                        class="mdi mdi-dots-horizontal"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="/editGP/{{ base64_encode($item['id']) }}"><i
                                                            class="mdi mdi-star mr-2 font-18 text-muted vertical-middle"></i>View and Edit</a>
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
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
</div>

@endsection