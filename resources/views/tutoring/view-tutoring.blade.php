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
    console.log(goToEvents);
    for (let i = 0; i < goToEvents.length; i++) {
        let btn = document.createElement('button');
        btn.innerHTML = `${goToEvents[i].title}`;
        btn.className = "btn m-1 w-100"
        btn.style.background = `${goToEvents[i].backgroundColor}`;
        btn.addEventListener('click',function(){
           let date = new Date(goToEvents[i].start);
           calendar.gotoDate(date);
        })
        container.appendChild(btn);

    }

    // $('#test').on('click',function(){
    //     // let the =
    //     console.log(the);

    // })

</script>
@endsection
