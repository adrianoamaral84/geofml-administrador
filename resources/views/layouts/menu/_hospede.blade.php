
<li class="@if(request()->is('hospede/*')) active open @endif">
    <a href="">
        <i class="fa fa-bed"></i>
        Reserva
        <i class="fa arrow"></i>
    </a>

    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('hospede.solicitarinscricao') }}">
                <i class="fa fa-pencil-square-o"></i>
                Solicitar Inscrição
            </a>
        </li>

        <li>
            <a href="{{ route('hospede.meuspedidos') }}">
                <i class="fa fa-list-alt"></i>
                Minhas Solicitações
            </a>
        </li>
    </ul>
</li>

