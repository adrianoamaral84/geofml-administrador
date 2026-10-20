@extends('layouts.calendario_sem_menu2')

@section('content')
<div class="container-fluid">
    <div class="mb-2" aria-label="Legenda de ocupação">
        <span class="badge" style="background:#ba5b32;color:white">Ocupada</span>
    </div>
    <div id="calendars" class="col-centered"></div>
</div>
@endsection

@push('javascript')
    @include('calendario.scriptsCalendario')
@endpush
