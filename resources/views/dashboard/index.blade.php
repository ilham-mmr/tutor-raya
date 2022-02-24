@extends('layouts.base')

@section('title')
    hello bro
@endsection

@section('breadcrumb_link')
@endsection

@section('main-content')
    @auth
        hello bro loggedin

    @endauth
@endsection
