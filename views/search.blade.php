@extends('layouts.app')


@section('content')
<div class="account-pages mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">
                    <div class="card-body p-4">
                        <div id=card>
                            <form action="{{ $action }}" method="{{ $method }}" class='pt-2'>
                                <div class="form-group mb-3">
                                    <h2 class="text text-primary">{{ $title }}</h2>
                                </div>
                                <div class="form-group mb-3">
                                    <input id='poid' class="form-control" type="text" required=""
                                        placeholder="{{ $lable }}">
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block p-2 alertColors" type="submit"> Submit
                                    </button>
                                </div>

                            </form>
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