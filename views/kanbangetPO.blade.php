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
                        <h4 class="page-title">Details related to PO No.: {{ $title }}</h4>
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
                                    @foreach (json_decode($info,true) as $key => $value)
                                    <tr>
                                        <td style="width: 35%;">{{ $key }}</td>
                                        <td>{{ $value }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td style="width: 35%;">Initial Qty</td>
                                        <td>{{ $intitQty }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- end .table-responsive -->
                    </div> <!-- end card-box -->
                </div><!-- end col -->
            </div>
            <!-- end row -->

        </div> <!-- container -->

    </div> <!-- content -->

</div>

@endsection