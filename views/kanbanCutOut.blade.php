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
                <div class="col-12">
                    <div class="card-box">
                        <h4 class="header-title mb-4">Work Orders to be Approved</h4>

                        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                            id="tickets-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Po ID</th>
                                    <th>Style</th>
                                    <th>Quantity</th>
                                    <th>Product</th>
                                    <th>Created Date</th>
                                    <th class="hidden-sm">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($WO as $item)

                                <tr>
                                    <td><b>{{ $item['id'] }}</b></td>
                                    <td>
                                        {{ $item['poid'] }}
                                    </td>

                                    <td>
                                        {{ $item['style'] }}
                                    </td>

                                    <td>
                                        {{ $item['aqty'] }}
                                    </td>

                                    <td>
                                        {{ $item['product'] }}
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
                                                <form action="/sendForWo" method="post">
                                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                                    <button class="dropdown-item" type="submit"><i
                                                            class="mdi mdi-shopping mr-2 text-muted font-18 vertical-middle"></i>
                                                        Send Outside
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