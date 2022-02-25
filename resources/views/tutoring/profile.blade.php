@extends('layouts.base')

@section('title')
Profile
@endsection

@section('breadcrumb_link')
@endsection

@section('main-content')

@if (session('message'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <p>{{ session('message') }}</p>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<form action="{{ route('web-profile.store') }}" method="post" enctype="multipart/form-data">

    @csrf
    <div class="row">
        <div class="col-lg-6">
            <div class="card card-primary">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        {{-- <h3 class="card-title">picture</h3> --}}
                        {{-- <a href="javascript:void(0);">View Report</a> --}}
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="d-flex justify-content-center align-items-center">

                        <img id="preview_picture" src="
                            @if ($user->picture)
                                {{ asset('storage/'.$user->picture) }}
                            @else
                            https://i.pinimg.com/236x/7d/1a/3f/7d1a3f77eee9f34782c6f88e97a6c888--no-face-facebook-profile.jpg
                            @endif
                            " class="img-circle elevation-2" width='350px' height="350px" alt="Cinque Terre">

                    </div>
                    @error('profile_picture')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @enderror
                    <div class="input-group mt-4">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profile_picture" name="profile_picture"
                                accept="image/png, image/jpg, image/jpeg">
                            <label class="custom-file-label" for="exampleInputFile">Choose picture</label>
                        </div>

                    </div>

                    <!-- /.d-flex -->

                </div>
            </div>
            <!-- /.card -->

        </div>
        <!-- /.col-md-6 -->
        <div class="col-lg-6">
            <div class="card card-primary">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        {{-- <h3 class="card-title">picture</h3> --}}
                        {{-- <a href="javascript:void(0);">View Report</a> --}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                    </div>
                    <div class=" form-group">
                        <label>Joined on</label>
                        <input type="email" class="form-control" value="{{ $user->created_at->format('M d Y') }}"
                            disabled>
                    </div>
                    <div class=" form-group">
                        <label>Phone Number</label>
                        <input type="tel" class="form-control" name="phone" placeholder="e.g 0895123123" pattern="^08[0-9]{9,}$" value="{{ $user->phone_number }}" required>

                    </div>

                </div>
            </div>
            <!-- /.card -->

            {{-- <div class="card">
                <div class="card-header border-0">
                    <h3 class="card-title">Online Store Overview</h3>
                    <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-tool">
                            <i class="fas fa-download"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-tool">
                            <i class="fas fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-success text-xl">
                            <i class="ion ion-ios-refresh-empty"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <i class="ion ion-android-arrow-up text-success"></i> 12%
                            </span>
                            <span class="text-muted">CONVERSION RATE</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                        <p class="text-warning text-xl">
                            <i class="ion ion-ios-cart-outline"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <i class="ion ion-android-arrow-up text-warning"></i> 0.8%
                            </span>
                            <span class="text-muted">SALES RATE</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <p class="text-danger text-xl">
                            <i class="ion ion-ios-people-outline"></i>
                        </p>
                        <p class="d-flex flex-column text-right">
                            <span class="font-weight-bold">
                                <i class="ion ion-android-arrow-down text-danger"></i> 1%
                            </span>
                            <span class="text-muted">REGISTRATION RATE</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->
                </div>
            </div> --}}
        </div>
        <!-- /.col-md-6 -->
        <div class="col">
            <div class="card card-primary">
                <div class="card-header border-0">

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="about">About Me</label>

                                @error('about')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $message }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @enderror
                                <textarea class="form-control @error('about') is-invalid @enderror" id="about"
                                    name='about' rows="5">{{ trim($user->about) ?? "" }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for='education'>Education</label>

                                @error('education')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $message }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @enderror
                                <input id='education' type="text"
                                    class="form-control @error('about') is-invalid @enderror" name='education'
                                    value="{{ $user->education ?? "" }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="degree">Degree</label>

                                @error('degree')
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $message }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @enderror
                                <input id="degree" name="degree" type="text"
                                    class="form-control @error('about') is-invalid @enderror"
                                    value="{{ $user->degree ?? "" }}">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <p class="text-dark">
                                    If you turn it off, you will not be searchable
                                </p>
                                <div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-focused bootstrap-switch-animate bootstrap-switch-on"
                                    style="width: 86px;">
                                    <div class="bootstrap-switch-container" style="width: 126px; margin-left: 0px;">

                                        <input type="checkbox" name="status" {{ $user->active ? "checked" : "" }}
                                            data-bootstrap-switch="">
                                    </div>
                                </div>

                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-block btn-primary w-25">Save</button>
                            </div>

                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('additional_script')
<script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
@endsection

@section('custom_script')
<script>
    $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        })

        // image
        const image_input = document.querySelector("#profile_picture");
        let uploaded_image;

        image_input.addEventListener('change', function() {
        const reader = new FileReader();
        reader.addEventListener('load', () => {
            uploaded_image = reader.result;
            document.querySelector("#preview_picture").src = uploaded_image;
        });
        reader.readAsDataURL(this.files[0]);
        });

</script>
@endsection
