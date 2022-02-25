@extends('layouts.base')
@section('additional_css')
<!-- fullCalendar -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fullcalendar/main.css') }}">
@endsection
@section('title')
My Upcoming Tutoring
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

<!-- THE CALENDAR -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Nearest Upcoming Tutoring</h3>
            </div>
            <div class="card-body">
                <div class="row" id="goToEvents">

                </div>

            </div>
        </div>

    </div>

    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-body p-0">
                <div id="calendar"></div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('additional_script')
<!-- jQuery UI -->
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/fullcalendar/main.js') }}"></script>

<script>
    let calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      themeSystem: 'bootstrap',
      weekNumbers:true,
      //Random default events
      events:{!! json_encode($events) !!}

    });
    calendar.render();

    const container = document.querySelector("#goToEvents")

    // for go to nearest upcoming tutoring
    let goToEvents = {!! json_encode($events) !!}
    if(goToEvents.length === 0) {
        container.className = "text-center"
        container.innerHTML = "<p>Ooops! You don't have an upcoming tutoring! <p>";
    }
    for (let i = 0; i < goToEvents.length; i++) {
        let divCard = document.createElement('div');
        divCard.className = `card w-100 ${i != 0 ? "collapsed-card" : ""}`;
        divCard.innerHTML = `
        <div class="card-header" style="background-color: ${goToEvents[i].backgroundColor};">
                <h3 class="card-title text-white">
                    ${goToEvents[i].title}
                </h3>
                <div class="card-tools">
                  <a href="/home/tutor/edit-tutoring/${goToEvents[i].id}" class="btn btn-tool" title="edit this tutoring session">
                    <i class="far fa-edit"></i>
                  </a>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
        </div>
        <div class="card-body">
            <dl class="row">

                <dt class="col-sm-4">Category:</dt>
                <dd class="col-sm-8">${goToEvents[i].category}</dd>

                <dt class="col-sm-4">Subject:</dt>
                <dd class="col-sm-8">${goToEvents[i].subject}</dd>

                <dt class="col-sm-4">Start Time:</dt>
                <dd class="col-sm-8">
                    ${new Date(goToEvents[i].start).toLocaleDateString()} at ${new Date(goToEvents[i].start).toLocaleTimeString()}
                    <i class="far fa-arrow-alt-circle-right"></i>
                    </dd>

                <dt class="col-sm-4">End Time:</dt>
                <dd class="col-sm-8">${new Date(goToEvents[i].end).toLocaleDateString()} at ${new Date(goToEvents[i].end).toLocaleTimeString()}</dd>

                <dt class="col-sm-4">Final Price:</dt>
                <dd class="col-sm-8">Rp${goToEvents[i].final_price}</dd>
            </dl>

        </div>
        `

        let gotoElement = divCard.querySelector(".col-sm-8 > i");
        gotoElement.style.cursor = 'pointer'
        gotoElement.title = "go to the date"
        gotoElement.addEventListener('click',function(){
           let date = new Date(goToEvents[i].start);
           calendar.gotoDate(date);
        })
        container.appendChild(divCard);

    }

</script>
@endsection
