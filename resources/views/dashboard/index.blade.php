@extends('layouts.base')

@section('title')
Dashboard
@endsection

@section('breadcrumb_link')
@endsection

@section('main-content')
@auth
<h4 class="text-left">
    Hello, {{ Auth::user()->name }}

</h4>
@endauth

<section class="content m-3">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row justify-content-center">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Rp{{ $totalEarnings }}</h3>

                        <p>Total Earnings</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalBookings }}</h3>

                        <p>Total Bookings</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="/home/tutor/booked-sessions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $totalTutorings }}</h3>

                        <p>Total Active Tutoring Sessions</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="/home/tutor/view-tutoring" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable ui-sortable">



        </div>
        <!--/.direct-chat -->

        <!-- TO DO List -->

        <!-- /.card -->
</section>
<!-- /.Left col -->
<!-- right col (We are only adding the ID to make the widgets sortable)-->
<section class="col-lg-5 connectedSortable ui-sortable">



    <!-- /.card -->
</section>
<!-- right col -->
</div>
<!-- /.row (main row) -->
</div><!-- /.container-fluid -->
</section>
@endsection
