@extends('layouts.adminL')


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
                <div class="col-lg-4">
                    <div class="card-box">
                        <div class="dropdown float-right">
                            <a href="#" class="dropdown-toggle arrow-none" data-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical m-0 text-muted h3"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Refresh</a>
                                <hr>
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Week</a>
                                <a class="dropdown-item" href="#">Month</a>
                                <a class="dropdown-item" href="#">All</a>
                            </div>
                        </div> <!-- end dropdown -->

                        <h4 class="header-title">{{ $body['B1']['title'] }}</h4>
                        <p class="sub-header">
                            {{ $body['B1']['subtitle'] }}
                        </p>

                        <ul class="sortable-list taskList list-unstyled" id="upcoming">
                            @foreach ($B1 as $item)
                            <li id="{{ $item['id'] }}">
                                <h3 class="mt-0">{{ array_keys($body["B1"]["C1"]["info"])[0] }}: {{$item['id'] }}</h3>
                                @foreach ($body["B1"]["C1"]["info"] as $key => $itemx)
                                @if (!strpos($key,"time") and !strpos($key,"ID"))
                                <h4 class="mt-0">{{ $key }}.: {{ $item["$itemx"] }}
                                </h4>
                                @endif
                                @endforeach
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col">
                                        <p class="font-13 mt-2 mb-0"><i class="mdi mdi-calendar"></i>
                                            <b>{{ end(array_keys($body["B1"]["C1"]["info"])) }}:</b>
                                            {{ $item['date'] }}</p>
                                    </div>
                                    <form action="/{{ $body['B1']['C1']['target'] }}" method="post">
                                        <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                        <input type="hidden" name="id" name="id" value="{{ $item['id'] }}">
                                        <div class="col-auto">
                                            <div class="text-right">
                                                <button type="submit"
                                                    class="btn btn-primary btn-block mt-3 waves-effect waves-light">
                                                    <i class="mdi mdi-plus-circle"></i> Select</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            @endforeach
                        </ul>

                    </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4">
                    <div class="card-box">
                        <div class="dropdown float-right">
                            <a href="#" class="dropdown-toggle arrow-none" data-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical m-0 text-muted h3"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Refresh</a>
                                <hr>
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Week</a>
                                <a class="dropdown-item" href="#">Month</a>
                                <a class="dropdown-item" href="#">All</a>
                            </div>
                        </div> <!-- end dropdown -->

                        <h4 class="header-title">{{ $body['B2']['title'] }}</h4>
                        <p class="sub-header">
                            {{ $body['B2']['subtitle'] }}
                        </p>

                        <ul class="sortable-list taskList list-unstyled" id="upcoming">
                            @foreach ($B2 as $item)

                            <li id="{{ $item['id'] }}">
                                <h3 class="mt-0">WO No.: {{ $item['id'] }}</h3>
                                <h4 class="mt-0">Qty.: {{ $item['pqty'] }}</h4>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col">
                                        <p class="font-13 mt-2 mb-0"><i class="mdi mdi-calendar"></i>
                                            <b>{{ array_keys($body["B2"]["C1"]["info"])[2] }}</b>
                                            {{ $item['date'] }}</p>
                                    </div>
                                </div>
                            </li>

                            @endforeach
                        </ul>

                    </div>
                </div>
                <!-- end col -->

                <div class="col-lg-4">
                    <div class="card-box">
                        <div class="dropdown float-right">
                            <a href="#" class="dropdown-toggle arrow-none" data-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical m-0 text-muted h3"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#">Refresh</a>
                                <hr>
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Week</a>
                                <a class="dropdown-item" href="#">Month</a>
                                <a class="dropdown-item" href="#">All</a>
                            </div>
                        </div> <!-- end dropdown -->

                        <h4 class="header-title">{{ $body['B3']['title'] }}</h4>
                        <p class="sub-header">
                            {{ $body['B3']['subtitle'] }}
                        </p>

                        <ul class="sortable-list taskList list-unstyled" id="upcoming">
                            @foreach ($B3 as $item)

                            <li id="{{ $item['id'] }}">
                                <h3 class="mt-0">WO No.: {{ $item['id'] }}</h3>
                                <h4 class="mt-0">Qty.: {{ $item['pqty'] }}</h4>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <div class="col">
                                        <p class="font-13 mt-2 mb-0"><i class="mdi mdi-calendar"></i>
                                            <b>{{ array_keys($body["B3"]["C1"]["info"])[2] }}:</b>
                                            {{ $item['date'] }}</p>
                                    </div>
                                </div>
                            </li>

                            @endforeach
                        </ul>

                    </div>
                </div>
                <!-- end col -->

            </div>
            <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

</div>

@endsection