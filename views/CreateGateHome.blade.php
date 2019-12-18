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
                        <h4 class="page-title">View / Edit Gate Pass: {{ $id }}</h4>
                        <input type="hidden" name="id" value="{{ $id }}">
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <input type="hidden" id='csrfk' name="csrfk" value="{{ $csrfk }}">
            <input type="hidden" id="gpid" name="id" value="{{ $id }}">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title">Basic Information for Gate Pass</h4>
                            <p class="sub-header">
                            </p>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="name">Name of receiver (Optional)</label>
                                        <input type="text" id="rname" name="name" class="form-control"
                                            value="{{ $rname }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="destination">Destination location (Optional)</label>
                                        <textarea id="destination" name="destination"
                                            class="form-control">{{ $destination }}</textarea>
                                    </div>
                                </div> <!-- end col -->
                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label for="name">Date</label>
                                        <input type="text" class="form-control" value="{{ $date }}" disabled>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="name">Added by</label>
                                        <input type="text" class="form-control text-capitalize" value="{{ $ab }}"
                                            disabled>
                                    </div>
                                    <div class="form-group mb-3">
                                        <h4>Status</h4>
                                        <div class="col-sm-6">
                                            <div class="radio radio-info mb-2">
                                                <input type="radio" name="status" id="radio8" value="0" @if ($status==0)
                                                    checked @endif>
                                                <label for="radio8">
                                                    <span class="badge badge-info" style="font-size:150%">New</span>
                                                </label>
                                            </div>
                                            <div class="radio radio-success mb-2">
                                                <input type="radio" name="status" id="radio4" value="1" @if ($status==1)
                                                    checked @endif>
                                                <label for="radio4">
                                                    <span class="badge badge-success" style="font-size:150%">Open</span>
                                                </label>
                                            </div>
                                            <div class="radio mb-2">
                                                <input type="radio" name="status" id="radio1" value="2" @if ($status==2)
                                                    checked @endif>
                                                <label for="radio1">
                                                    <span class="badge badge-secondary"
                                                        style="font-size:150%">Closed</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div><!-- end col -->
                                </div> <!-- end row-->
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row-->
                    <div class="row">
                        <div class="col-lg-9">
                            <h4 class="text text-right">Finish the Gate pass</h4>
                        </div> <!-- end col -->
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <button id="confirmGP" class="btn btn-primary waves-effect waves-light">Confirm</button>
                                <button type="button" id="cancelGP"
                                    class="btn btn-warning waves-effect waves-light">Delete</button>
                                <a href="/printPDF/{{ base64_encode($id) }}" target="_blank" type="button"
                                    class="btn btn-blue waves-effect waves-light">Export Print
                                    Copy</a>
                            </div>
                        </div> <!-- end col -->
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Added Workorders</h4>
                    <p class="text-muted font-13 mb-4">
                    </p>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" id="tickets-table2">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>PO ID</th>
                                <th>Quantity</th>
                                <th>Created Date</th>
                                <th class="hidden-sm">Action</th>
                            </tr>
                        </thead>

                        <tbody id="ADWtable" style="text-transform:capitalize">
                            @foreach ($AW as $item)
                            <tr id="RW_{{ $item['id'] }}">
                                <td><b>{{ $item['id'] }}</b></td>
                                <td>
                                    {{ $item['poid'] }}
                                </td>

                                <td>
                                    {{ $item['pqty'] }}
                                </td>

                                <td>
                                    {{ $item['initdt'] }}
                                </td>

                                <td>
                                    <div class="btn-group dropdown">
                                        <a href="javascript: void(0);"
                                            class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                            data-toggle="dropdown" aria-expanded="false"><i
                                                class="mdi mdi-dots-horizontal"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button class="dropdown-item" onclick="removefromGP('{{ $item['id'] }}')"><i
                                                    class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Remove</button>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Available Workorders</h4>
                    <p class="text-muted font-13 mb-4">
                    </p>
                    <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100" id="tickets-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>PO ID</th>
                                <th>Quantity</th>
                                <th>Created Date</th>
                                <th class="hidden-sm">Action</th>
                            </tr>
                        </thead>

                        <tbody id="AWtable" style="text-transform:capitalize">
                            @foreach ($wo as $item)
                            <tr id="RW_{{ $item['id'] }}">
                                <td><b>{{ $item['id'] }}</b></td>
                                <td>
                                    {{ $item['poid'] }}
                                </td>

                                <td>
                                    {{ $item['pqty'] }}
                                </td>

                                <td>
                                    {{ $item['initdt'] }}
                                </td>

                                <td>
                                    <div class="btn-group dropdown">
                                        <a href="javascript: void(0);"
                                            class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                            data-toggle="dropdown" aria-expanded="false"><i
                                                class="mdi mdi-dots-horizontal"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <button id="RW_{{ $item['id'] }}btn" class="dropdown-item"
                                                onclick="addtoGP('{{ $item['id'] }}')"><i
                                                    class="mdi mdi-plus-circle-outline mr-2 text-muted font-18 vertical-middle"></i>Add</button>
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div>
    <!-- end row -->
</div> <!-- container -->
</div> <!-- content -->
</div>

@endsection