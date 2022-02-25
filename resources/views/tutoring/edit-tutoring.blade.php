@extends('layouts.base')

@section('additional_css')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.css" />
@endsection

@section('title')
Edit Tutoring Session
@endsection

@section('breadcrumb_link')
@endsection

@section('main-content')

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- delete Modal -->
<div class="modal fade" id="deleteConformationModal" tabindex="-1" role="dialog"
    aria-labelledby="deleteConformationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConformationModalTitle">Are you sure you want to delete the tutoring
                    session?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Deleting the tutoring session will make the session not visible to the users
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="delete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('web-tutoring.update', $tutoring->id) }}" method="post" enctype="multipart/form-data">

    @csrf
    @method('PUT')
    <div class="row">


        <div class="col-lg">
            <div class="card card-primary">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-danger" data-toggle="modal"
                            data-target="#deleteConformationModal" title="Delete the session"><i
                                class="far fa-trash-alt"></i></button>
                    </div>
                </div>
                <div class="row p-3">
                    <div class="col-lg-6">

                        <div class="form-group">
                            <label for="categoryDropdown">Category</label>
                            <select class="form-control" id="categoryDropdown" name="category">
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $category->id == $selectedCategory ? "selected" : "" }}>{{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subjectsDropdown">Teaches</label>
                            <select class="form-control" id="subjectsDropdown" name="subject">
                                @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ $tutoring->subject->id == $subject->id ? "selected" : "" }}>{{ $subject->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="e.g learn maths with fun" value="{{ $tutoring->title }}">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="tell us what you're good at and teaching method">{{ $tutoring->description }}</textarea>

                        </div>
                        <div class="form-group">
                            <label for="hourlyPrice">Hourly Price</label>
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Rp</div>
                                </div>
                                <input type="number" min="0" class="form-control" id="hourlyPrice" name="hourly_price"
                                    placeholder="e.g 50000" value="{{ $tutoring->hourly_price }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div style="overflow:hidden;">
                            <div class="form-group">
                                <label for="datetimepicker">Starting date & time</label>
                                <div class="card">
                                    <div class="row justify-content-center">
                                        <div id="datetimepicker" class="m-3"></div>
                                        <input type="hidden" name="teachingDateTime" id="teachingDateTime"
                                            value="{{ $tutoring->start_time->toIsoString() }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Teaching Hours</label>
                            <select class="form-control" id="hours" name="hours">
                                <option value="#" style="display:none">Select teaching hours</option>
                                <option value="1" {{ $tutoring->hourly_duration == 1 ? "selected" : "" }}>1 hour
                                </option>
                                <option value="2" {{ $tutoring->hourly_duration == 2 ? "selected" : "" }}>2 hour
                                </option>
                            </select>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-text-width"></i>
                                    Detail
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>

                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-4">Selected Date:</dt>
                                    <dd class="col-sm-8"><span
                                            id="selected-date">{{ $tutoring->start_time->isoFormat('dddd, MMMM Do YYYY') }}</span>
                                    </dd>

                                    <dt class="col-sm-4">Start Time:</dt>
                                    <dd class="col-sm-8"><span
                                            id="selected-start-time">{{ $tutoring->start_time->isoFormat('h:mm a') }}</span>
                                    </dd>

                                    <dt class="col-sm-4">End Time:</dt>
                                    <dd class="col-sm-8"><span
                                            id="selected-end-time">{{ $tutoring->end_time->isoFormat('h:mm a') }}</dd>

                                    <dt class="col-sm-4">Final Price:</dt>
                                    <dd class="col-sm-8"><span id="finalPriceDisplay">
                                            {{ $tutoring->hourly_price * $tutoring->hourly_duration }}</span></dd>
                                </dl>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        {{-- <div class="m-3">
                            <br />Value of hidden field: <span id="hidden-val"></span>
                            <br />
                        </div> --}}
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-block btn-primary w-25">Save</button>
                        </div>


                    </div>



                </div>

            </div>
        </div>


    </div>




</form>
@endsection


@section('additional_script')

<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"> </script>
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"> </script>

<script type="text/javascript">
    // create sidebyside datetimepicker
 $('#datetimepicker').datetimepicker({
    inline: true,
    sideBySide: true,
    minDate: new Date(),
    defaultDate: new Date("{{ $tutoring->start_time }}")
  });

  $('#hourlyPrice').on('keyup',function(){
    let hourlyPrice = $('#hourlyPrice').val();
    let hours = $('#hours').val();


    $('#finalPriceDisplay').text(+hourlyPrice * hours);

  })

const teachingHoursEvent = function() {
    let hours = $('#hours').val();
    let hiddenInput = $('#teachingDateTime').val();
    console.log(moment(hiddenInput).format('h:mm a'));

    let endTime = moment(hiddenInput).add(hours,'hours');
    $('#selected-end-time').text(endTime.format('h:mm a'));

    let hourlyPrice = $('#hourlyPrice').val();
    let finalPriceDisplay = +hourlyPrice * hours;

    // console.log(finalPriceDisplay);

    $('#finalPriceDisplay').text(finalPriceDisplay);

}

$('#datetimepicker').on('change.datetimepicker', function(event) {
    // console.log(moment(event.date).format('MM/DD/YYYY h:mm a'));
    // console.log(event.date.format('dddd, MMMM Do YYYY, h:mm:ss a'));
    $('#selected-date').text(event.date.format('dddd, MMMM Do YYYY'));
    $('#selected-start-time').text(event.date.format('h:mm a'));
    var formatted_date = event.date.format('MM/DD/YYYY h:mm a');
    $('#teachingDateTime').val(event.date.toISOString());
    $('#hidden-val').text($('#teachingDateTime').val());
    $('#hours').prop('disabled', false)
    let hours = $('#hours').val();

    teachingHoursEvent();


  });

$('#hours').on('change', teachingHoursEvent);

$('#categoryDropdown').on('change', () =>{
    const id = {{ $tutoring->id }};
    window.location.replace(`http://localhost:8000/home/tutor/edit-tutoring/${id}?category=${$('#categoryDropdown').val()}`);
});

$('#delete').click(function(){
    $('#deleteConformationModal').modal('toggle');
    let csrf = "{{ csrf_token() }}"
    let formData = {_method:"DELETE", _token:csrf}; //Array
    $.ajax({
        url : "{{ route('web-tutoring.delete', $tutoring->id) }}", // Url of backend (can be python, php, etc..)
        type: "POST", // data type (can be get, post, put, delete)
        data : formData, // data in json format
        async : false, // enable or disable async (optional, but suggested as false if you need to populate data afterwards)
        success: function(response, textStatus, jqXHR) {
            window.location.replace(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
});


</script>
@endsection
