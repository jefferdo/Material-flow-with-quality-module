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
                        <h4 class="page-title">Details related to WO No.: {{ $title }}</h4>
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
                                        <td style="width: 35%;">Size</td>
                                        <td><span>{{ $size }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Color</td>
                                        <td><span>{{ $color }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Quantity</td>
                                        <td><span>{{ $qty }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Customer</td>
                                        <td><span>{{ $cus }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Style</td>
                                        <td><span>{{ $style }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Initiated Date</td>
                                        <td><span>{{ $initdt }}</span> </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <form action="/readyWO" method="post">
                                                <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <div class="row">
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect waves-light mb-2">Ready</button>
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

            <div id="errorbox"></div>

        </div> <!-- container -->

    </div> <!-- content -->

</div>

@endsection