@extends('layouts.calendario_sem_menu2')

@section('content')
<div class="container-fluid">
    <div id="calendars" class="col-centered"></div>
</div>
@endsection

@push('javascript')
    @include('calendario.scriptsCalendario')
@endpush
