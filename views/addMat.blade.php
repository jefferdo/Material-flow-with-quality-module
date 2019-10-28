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
                        <h4 style="text-transform:capitalize" class="page-title">Add Matirials related to PO No.:
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
                                        <td style="width: 35%;">Currently available matirial (Rolls)</td>
                                        <td><span>{{ $nor }}</span> </td>
                                    </tr>
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
                                            <form action="/qa" method="post">
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
                        <button style="margin:5px;" type="button" id='AddMatN'
                            class="btn btn-sm btn-blue waves-effect waves-light float-right">
                            <i class="mdi mdi-plus-circle"></i> Add Matirials
                        </button>
                        <button style="margin:5px;" type="button" id='AddMatNB'
                            class="btn btn-sm btn-warning waves-effect waves-light float-right">
                            <i class="mdi mdi-plus-circle"></i> Add Matirials Batch
                        </button>

                        <h4 style="text-transform:capitalize" class="header-title mb-4">Currently available matirial
                            Details</h4>
                        <h5 style="text-transform:capitalize" class="header-subtitle">Currently available matirial
                            (Rolls): <span>{{ $nor }}</span></h5>
                        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                            id="tickets-table">
                            <thead>
                                <tr>
                                    <th>Roll / Unit ID</th>
                                    <th>Added Date</th>
                                    <th>Added by</th>
                                    <th class="hidden-sm">Action</th>
                                </tr>
                            </thead>

                            <tbody id="rolltable">
                                @foreach ($rolls as $roll)
                                <tr>
                                    <td><b>{{ $roll['id'] }}</b></td>
                                    <td> {{ $roll['date'] }} </td>
                                    <td style="text-transform:capitalize"> {{ $roll['user'] }} </td>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <a href="javascript: void(0);"
                                                class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm"
                                                data-toggle="dropdown" aria-expanded="false"><i
                                                    class="mdi mdi-dots-horizontal"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button onclick="showRoll('{{ $roll['id'] }}')" class="dropdown-item"
                                                    href="#"><i
                                                        class="mdi mdi-clipboard mr-2 text-muted font-18 vertical-middle"></i>Show
                                                    Details</button>
                                                <button class="dropdown-item" href="#"><i
                                                        class="mdi mdi-delete mr-2 font-18 text-muted vertical-middle"></i>Remove</button>
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