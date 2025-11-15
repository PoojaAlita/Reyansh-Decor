@extends('layouts.master')
@section('title', content: 'Dashboard')
@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial bg-label-primary rounded-circle"><i
                                            class="bx bx-user fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    {{-- <h5 class="card-title mb-0 me-2">{{ $data['user'] }}</h5> --}}
                                    <small class="text-muted">User</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial bg-label-warning rounded-circle"><i
                                            class="bx bx-comment-detail fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    {{-- <h5 class="card-title mb-0 me-2">{{ $data['feedback'] }}</h5> --}}
                                    <small class="text-muted">Feedback</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial bg-label-primary rounded-circle"><i
                                            class="bx bx-slider-alt fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    {{-- <h5 class="card-title mb-0 me-2">{{ $data['slider'] }}</h5> --}}
                                    <small class="text-muted">Slider</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar">
                                    <span class="avatar-initial bg-label-primary rounded-circle"><i
                                            class="bx bx-chalkboard fs-4"></i></span>
                                </div>
                                <div class="card-info">
                                    {{-- <h5 class="card-title mb-0 me-2">{{ $data['facility'] }}</h5> --}}
                                    <small class="text-muted">Facility</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
