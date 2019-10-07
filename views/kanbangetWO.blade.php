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
                                        <td style="width: 35%;">Order Qty</td>
                                        <td><span>Select the Color first</span> </td>
                                    </tr>
                                    <tr id="qtyr" class="textg" style="display: none">
                                        <td style="width: 35%;">Initial size Qty</td>
                                        <td><span>Select the Color first</span> </td>
                                    </tr>
                                    <tr>
                                        <td>Requied Qty</td>
                                        <td>
                                            <form action="/makeWO" method="post">
                                                <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                                <input type="hidden" name="poid" value="{{ $id }}">
                                                <input type="hidden" id='oqtyf' name='oqty' value="">
                                                <input type="hidden" id='iqtyf' name='iqty' value="">
                                                <input type="hidden" id='colorf' name='colorf' value="">
                                                <input type="hidden" id='sizef' name='sizef' value="">
                                                <div class="row">
                                                    <div class="form-group mx-sm-3">
                                                        <input type="text" class="form-control" id="rqty" name="rqty"
                                                            required>
                                                    </div>
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect waves-light mb-2">Create
                                                        WO</button>
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