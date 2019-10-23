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
                                    <form action="/addDateWa" method="post">
                                        <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <tr class="text">
                                            <td style="width: 35%;">Date</td>
                                            <td>
                                                <input type="text" name="date" id="humanfd-datepicker"
                                                    class="form-control" placeholder="Pick Date" required>
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
                                        <td style="width: 35%;">Washing</td>
                                        <td><span>{{ $dates[3] }}</span> </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            @if ($dates[3])
                                            <div class="row">
                                                <form action="{{ $action }}" method="{{ $method }}" class='pt-2'>
                                                    <input type="hidden" name='csrfk' value="{{ $csrfk }}">
                                                    <input type="hidden" name='id' value="{{ $id }}">
                                                    <button class="btn btn-success" type="submit"><i
                                                            class="mdi mdi-star-box mr-2 font-18 text-light vertical-middle"></i>
                                                        Mark as Passed or Rejected
                                                    </button>
                                                </form>
                                            </div>
                                            @else
                                            <div class="row">
                                                <button class="btn btn-success" type="button" disabled><i
                                                        class="mdi mdi-star-box mr-2 font-18 text-light vertical-middle"></i>
                                                    Mark as Passed or Rejected
                                                </button>
                                                <button class="btn btn-light text text-danger" type="button" disabled>
                                                    Set Date First
                                                </button>
                                            </div>
                                            @endif
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