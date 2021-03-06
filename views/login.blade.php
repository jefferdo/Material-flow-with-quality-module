@extends('layouts.app')

@section('content')
<div class="account-pages mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">
                    <div class="card-body py-2 px-5">
                        <div class="text-center w-75 m-auto">
                            <a href="/">
                                <span><img src="/assets/images/logo-dark-flow.png" alt="" height="100"></span>
                            </a>
                        </div>
                        @if (@$_GET['error'] != null)
                        <div class="pt-1">
                            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ @$_GET['error'] }}
                            </div>
                        </div>
                        @endif
                        <div id='manBox'>
                            <form action="/login" method="post" class='pt-1'>

                                <input type="hidden" name='csrfk' value="{{ $csrfk }}">

                                <div class="form-group mb-3">
                                    <input id='uid' name='uid' class="form-control" type="text" required=""
                                        placeholder="User ID">
                                </div>

                                <div class="form-group mb-3">
                                    <input id='password' name='passwd' class="form-control" type="password" required=""
                                        placeholder="Passkey">
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block p-2 alertColors" type="submit"> Log In
                                    </button>
                                </div>

                            </form>
                        </div>

                        <div id='barcodeBox'>
                            <form action="/" method="post" class='pt-2'>

                                <input type="hidden" name='csrfk' value="{{ $csrfk }}">

                                <div class="form-group mb-3">
                                    <input id='barV' name='barV' class="form-control" type="password" required=""
                                        placeholder="Ready for Barcode">
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block p-2 py-4" type="submit"> Reading
                                        Barcode
                                    </button>
                                </div>

                            </form>
                        </div>

                        <div class="text-center">
                            <h5 class="mt-3 text-muted">Sign in with</h5>
                            <ul class="social-list list-inline mt-3 mb-0">
                                <li class="list-inline-item">
                                    <a id='bCodeS' class="btn btn-primary text-light px-4 py-1">
                                        <i id='bIconS' class="mdi mdi-barcode"></i></a>
                                </li>
                            </ul>
                        </div>

                    </div> <!-- end card-body -->

                </div>
                <!-- end card -->

                <!-- end row -->

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->
@endsection