@extends('layouts.kanbanL')


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
                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-primary">
                                    <i class="fe-tag font-22 avatar-title text-white"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">3,947</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Total number of Rolls</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-warning">
                                    <i class="fe-clock font-22 avatar-title text-white"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">{{ $npo }}</span></h3>
                                    <p class="text-muted mb-1">Total No. of Purchase Orders</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-success">
                                    <i class="fe-check-circle font-22 avatar-title text-white"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">3195</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Closed Tickets</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->

                <div class="col-md-6 col-xl-3">
                    <div class="widget-rounded-circle card-box">
                        <div class="row">
                            <div class="col-6">
                                <div class="avatar-lg rounded-circle bg-danger">
                                    <i class="fe-trash-2 font-22 avatar-title text-white"></i>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-right">
                                    <h3 class="text-dark mt-1"><span data-plugin="counterup">128</span></h3>
                                    <p class="text-muted mb-1 text-truncate">Deleted Tickets</p>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    </div> <!-- end widget-rounded-circle-->
                </div> <!-- end col-->
            </div>
            <!-- end row -->

            <div class="row">
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title mb-4">Purchase Orders</h4>

                        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                            id="tickets-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Style</th>
                                    <th>Quantity</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Due Date</th>
                                    <th class="hidden-sm">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($PO as $item)

                                <tr>
                                    <td><b>{{ $item['id'] }}</b></td>
                                    <td>
                                        {{ $item['cus'] }}
                                    </td>

                                    <td>
                                        {{ $item['style'] }}
                                    </td>

                                    <td>
                                        {{ $item['qty'] }}
                                    </td>

                                    <td>
                                        <span
                                            class="badge bg-soft-secondary text-secondary">{{ $item['product'] }}</span>
                                    </td>

                                    <td>
                                        <span class="badge badge-success">Waiting for Insspections</span>
                                    </td>

                                    <td>
                                        {{ $item['date'] }}
                                    </td>

                                    <td>
                                        {{ $item['matdt'] }}
                                    </td>

                                    <td>
                                        <div class="btn-group dropdown">
                                            <a href="javascript: void(0);"
                                                class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                data-toggle="dropdown" aria-expanded="false"><i
                                                    class="mdi mdi-dots-horizontal"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <form action="/inMat" method="post">
                                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                                    <button class="dropdown-item" type="submit"><i
                                                            class="mdi mdi-shopping mr-2 text-muted font-18 vertical-middle"></i>
                                                        Inspect matirials
                                                    </button>
                                                </form>
                                                <form action="{{ $action }}" method="{{ $method }}" class='pt-2'>

                                                    <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                                    <input type="hidden" name='id' value="{{ $item['id'] }}">
                                                    <button class="dropdown-item" type="submit"><i
                                                            class="mdi mdi-star-box mr-2 font-18 text-muted vertical-middle"></i>
                                                        Mark as Passed or Rejected
                                                    </button>
                                                </form>
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
            <!-- end row -->
        </div> <!-- container -->

    </div> <!-- content -->

</div>

@endsection