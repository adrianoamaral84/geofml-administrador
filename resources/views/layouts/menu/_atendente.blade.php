<li class="@if(request()->is('atendente/*')) active open @endif">
    <a href="">
        <i class="fas fa-calendar-check"></i> Entrada / Saída  <i class="fa arrow"></i>
    </a>
    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('buscar') }}"> Buscar Hospedagem </a>
        </li>
        <li>
            <a href="{{ route('checkIn') }}"> Entrada Hospedagem </a>
        </li>
        <li>
            <a href="{{ route('checkOut') }}"> Saída Hospedagem </a>
        </li>

    </ul>
</li>

