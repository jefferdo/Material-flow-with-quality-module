@extends('layouts.app')


@section('content')
<div class="account-pages mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">
                    <div class="card-body pt-2">
                        <div id=card>
                            <form action="{{ $action }}" method="{{ $method }}" class='pt-2'>

                                <input type="hidden" id="csrfk" name='csrfk' value="{{ $csrfk }}">
                                <input type="hidden" id="id" name='id' value="{{ $id }}">
                                <input type="hidden" id="stage" name='stage' value="{{ $stage }}">
                                <div class="form-group mb-3">
                                    <h2 class="text text-primary">{{ $title }} - QA Pieces</h2>
                                </div>
                                <div class="form-group mb-3">
                                    <h4 class="text text-primary" style="text-transform: uppercase">|
                                        @foreach ($info as $key => $item)
                                        {{ $key }}: {{ $item }} |
                                        @endforeach
                                    </h4>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="row pb-1">
                                        <div class="col-6">
                                            <button id='btnqaif_accept' type="button"
                                                class="btn btn-block btn-success waves-effect waves-light btn-lg p-3">Accept</button>
                                        </div>
                                        <div class="col-6">
                                            <button id='btnqai_reject' type="button"
                                                class="btn btn-block btn-danger waves-effect waves-light btn-lg p-3">Reject</button>
                                        </div>
                                        <div class="pt-2"></div>
                                    </div>

                                    <button id='btnqa_acceptf' type="button"
                                        class="btn btn-block btn-primary waves-effect waves-light btn-lg pt-1">Finish and End</button>
                                    <button id='btnqa_cancel' type="button"
                                        class="btn btn-block btn-warning waves-effect waves-light btn-lg pt-1">Cancel
                                        and go back</button>
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
<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="assets/js/alert.js"></script>
<!-- Sweet alert init js-->
@endsection