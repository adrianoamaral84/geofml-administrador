
<li class="@if(request()->is('atendente/*')) active open @endif">
    <a href="">
        <i class="fa fa-calendar-check-o"></i>
        Entrada / Saída
        <i class="fa arrow"></i>
    </a>

    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('buscar') }}">
                <i class="fa fa-search"></i>
                Buscar Hospedagem
            </a>
        </li>

        <li>
            <a href="{{ route('checkIn') }}">
                <i class="fas fa-sign-in-alt"></i>
                Entrada Hospedagem
            </a>
        </li>

        <li>
            <a href="{{ route('checkOut') }}">
                <i class="fas fa-sign-out-alt"></i>
                Saída Hospedagem
            </a>
        </li>
    </ul>
</li>

