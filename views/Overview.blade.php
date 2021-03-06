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
                        <h1 class="page-title">{{ $title }}</h1>
                    </div>
                </div>
            </div>

            <div class="anchor" id="MR"></div>
            
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Material Receive</h1>
                    <div class="row">
                        <div class="col-md-12 col-xl-12">
                            <div class="widget-rounded-circle card-box">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="avatar-lg rounded-circle bg-success">
                                            <i class="fe-check-circle font-22 avatar-title text-white"></i>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-right">
                                            <h1 class="text-dark mt-1"><span
                                                    data-plugin="counterup">{{ $posatMRc }}</span></h1>
                                            <h2 class="text-muted mb-1" style="text-transform:capitalize">Total fabric
                                                rolls available</h2>
                                        </div>
                                    </div>
                                </div> <!-- end row-->
                            </div> <!-- end widget-rounded-circle-->
                        </div> <!-- end col-->
                    </div>
                </div>
            </div>

            <div class="anchor" id="MI"></div>

            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Material Inspection</h1>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatable-buttonsMI" class="table table-striped dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Roll No</th>
                                            <th>PO ID</th>
                                            <th>Status</th>
                                            <th>Added date</th>
                                            <th>Added by</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($rollinfo as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td>{{ $item['poid'] }}</td>
                                            <td>
                                                @if ( $item['status'] == 1)
                                                <span class="badge badge-success badge-pill">Approved</span>
                                                @else
                                                <span class="badge badge-warning badge-pill">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $item['date'] }}</td>
                                            <td style="text-transform:capitalize">{{ $item['name'] }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
            </div>

            <div class="anchor" id="CT"></div>

            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Cutting</h1>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatable-buttonsCT" class="table table-striped dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Workorder ID</th>
                                            <th>PO ID</th>
                                            <th>Qty</th>
                                            <th>Added date</th>
                                            <th>Added by</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @foreach ($poctin as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td>{{ $item['poid'] }}</td>
                                            <td>{{ $item['pqty'] }}</td>
                                            <td>{{ $item['apdt'] }}</td>
                                            <td style="text-transform:capitalize">{{ $item['name'] }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
            </div>

            <div class="anchor" id="SW"></div>

            <div class="card" id="sewingcard">
                <div class="card-body">
                    <h1 class="card-title">Sewing <span
                            class="badge badge-danger badge-pill live LiveIcon">Live</span></h1>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="datatable-buttonsSW" class="table table-striped dt-responsive nowrap">
                                    <thead>
                                        <tr>
                                            <th>Workorder ID</th>
                                            <th>PO ID</th>
                                            <th>Qty</th>
                                            <th>Finished Qty</th>
                                            <th>Approved date</th>
                                            <th>Added by</th>
                                        </tr>
                                    </thead>


                                    <tbody id="sewinglive">
                                        @foreach ($poswin as $item)
                                        <tr>
                                            <td>{{ $item['id'] }}</td>
                                            <td>{{ $item['poid'] }}</td>
                                            <td>{{ $item['pqty'] }}</td>
                                            <td><span style="font-size:120%;"
                                                    class="badge badge-info badge-pill live">{{ $item['finQty'] }}</span>
                                            </td>
                                            <td>{{ $item['apdt'] }}</td>
                                            <td style="text-transform:capitalize">{{ $item['name'] }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>

                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
            </div>
        </div>
    </div>

</div> <!-- content -->

</div>

@endsection