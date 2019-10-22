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
                        <h4 class="page-title">{{ $title }} Send to: {{ $id }}</h4>
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
                                    <form action="/addDateWo" method="post">
                                        <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <tr class="text">
                                            <td style="width: 35%;">Type</td>
                                            <td>
                                                <select name="type" class="custom-select " required>
                                                    <option value="" hidden selected>Pick Type</option>
                                                    <option value="0">Printing</option>
                                                    <option value="1">Sublimation</option>
                                                    <option value="2">Embroidary</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="text">
                                            <td style="width: 35%;">Date</td>
                                            <td>
                                                <input type="text" name="date" id="humanfd-datepicker" class="form-control"
                                                    placeholder="Pick Date" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>

                                                <div class="row">
                                                    <button type="submit"
                                                        class="btn btn-primary waves-effect waves-light mb-2">Add</button>
                                                    <div class="p-1"></div>
                                                    <a href="/" class="btn btn-danger waves-effect waves-light mb-2">Go
                                                        to
                                                        home</a>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>
                                </tbody>

                            </table>
                        </div> <!-- end .table-responsive -->
                    </div> <!-- end card-box -->
                </div><!-- end col -->
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card-box">

                        <div class="table-responsive">
                            <table class="table table-centered table-borderless table-striped mb-0">
                                <tbody>
                                    <tr class="text">
                                        <td style="width: 35%;">Printing</td>
                                        <td><span>{{ $dates[0] }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Sublimation</td>
                                        <td><span>{{ $dates[1] }}</span> </td>
                                    </tr>
                                    <tr class="text">
                                        <td style="width: 35%;">Embroidary</td>
                                        <td><span>{{ $dates[2] }}</span> </td>
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