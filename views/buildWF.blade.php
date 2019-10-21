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
                        <h4 style="text-transform:capitalize" class="page-title">Build Waterfall No.:
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
                                        <td style="width: 35%;">Purchase Order No:</td>
                                        <td><span>{{ $poid }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Initiated Date</td>
                                        <td><span>{{ $date }}</span> </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <div class="row">
                                                <div class="p-1"></div>
                                                <a href="/" class="btn btn-danger waves-effect waves-light mb-2">Go
                                                    to
                                                    home</a>
                                            </div>
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
                        <input type="hidden" name="wfid" id="wfid" value="{{ $id }}">
                        <button type="button" id='addRollWF'
                            class="btn btn-sm btn-blue waves-effect waves-light float-right">
                            <i class="mdi mdi-plus-circle"></i> Build Sequence
                        </button>
                        <h4 style="text-transform:capitalize" class="header-title mb-4">Waterfall
                            Details</h4>
                        <h5 style="text-transform:capitalize" class="header-subtitle">Currently available Waterfalls
                            : <span>{{ $nos }}</span></h5>
                        <table class="table table-hover m-0 table-centered dt-responsive nowrap w-100"
                            id="tickets-table">
                            <thead>
                                <tr>
                                    <th>
                                        Roll / Unit ID
                                    </th>
                                    <th>Related PO No</th>
                                    <th>Suppllier</th>
                                    <th>Length</th>
                                    <th>Height</th>
                                    <th>Width</th>
                                    <th>Status</th>
                                    <th>Added Date</th>
                                    <th>Added by</th>
                                    <th class="hidden-sm">Action</th>
                                </tr>
                            </thead>

                            <tbody id="WFtable">
                                @foreach ($sqn as $roll)
                                <tr>
                                    <td><b>{{ $roll['roid'] }}</b></td>
                                    <td>
                                        <span class="ml-2">{{ $roll['poid'] }}</span>
                                    </td>

                                    <td>
                                        {{ $roll['supid'] }}
                                    </td>

                                    <td>
                                        {{ $roll['lgth'] }}
                                    </td>

                                    <td>
                                        {{ $roll['hgt'] }}
                                    </td>

                                    <td>
                                        {{ $roll['wdth'] }}
                                    </td>

                                    <td>
                                        @if ($roll == 1)
                                        <span class="badge badge-success">Approved</span>
                                        @else
                                        <span class="badge badge-warning">Pending</span>
                                        @endif

                                    </td>

                                    <td>
                                        {{ $roll['date'] }}
                                    </td>

                                    <td style="text-transform:capitalize">
                                        {{ $roll['user'] }}
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
                    </div>
                </div><!-- end col -->
            </div>

            <div id="errorbox"></div>

        </div> <!-- container -->

    </div> <!-- content -->

</div>

@endsection