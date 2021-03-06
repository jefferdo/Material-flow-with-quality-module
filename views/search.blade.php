@extends('layouts.app')


@section('content')
<div class="account-pages mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">
                    <div class="card-body p-4">
                        <div class="form-group mb-3">
                            <h2 class="text text-primary">{{ $title }}</h2>
                        </div>
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
                        <div id=card>
                            <form action="{{ $action }}" method="{{ $method }}" class='pt-2'>

                                <input type="hidden" name='csrfk' value="{{ $csrfk }}">


                                <div class="form-group mb-3">
                                    <input id='id' name='id' class="form-control" type="text" required=""
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
                <div class="col-12 text-center">
                    <p class="text-white-50"> <a href="/logout" class="text-white ml-1"><b>Logout</b></a>
                    </p>
                </div>

            </div> <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>
<!-- end page -->
<script type="text/javascript">
    function myFunction() { 
        document.getElementById("poid").focus(); 
    } 
</script>
@endsection