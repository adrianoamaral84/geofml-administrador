<li class="@if(request()->is('admin/usuarios/*')) active open @endif">
    <a href="">
        <i class="fa fa-users"></i> Usuários <i class="fa arrow"></i>
    </a>
    <ul class="sidebar-nav">
        
        <li>
             <a href="{{ route('user.index') }}">
                <i class="fa fa-user-cog"></i>
                Gerenciar
            </a>
        </li>
          
        
        <li>
            <a href="{{ route('user.precadastro.lista') }}">
                <i class="fa fa-user-plus"></i>
                Pré-Cadastro
            </a>
        </li>

        <li>
            <a href="{{ route('documentos.vencidos') }}">
                <i class="fa fa-file-text"></i>
                Documentos Vencidos
            </a>
        </li> 

        <li>
            <a href="{{ route('consulta.usuario.index') }}">
                <i class="fa fa-search"></i>
                Consulta Usuário
            </a>
        </li>

    </ul>
</li>





<li class="@if(request()->is('admin/hospedagem/*')) active open @endif">
    <a href="">
        <i class="fa fa-h-square"></i> Hospedagem <i class="fa arrow"></i>
    </a>
    <ul class="sidebar-nav">  
        <li>
             <a href="{{ route('hospedagem.aguardando_liberacao') }}">
                <i class="fa fa-clock-o"></i>
                Aguardando Liberação
            </a>
        </li>

    <li>
     <a href="">
                <i class="fa fa-random"></i>
                Distribuição
                <i class="fa arrow"></i>
            </a>
    <ul class="sidebar-nav">
        <li>
            <a href="{{route('hospedagem.distribuicao.gen')}}"> Oficiais Generais </a>
        </li>
        <li>
            <a href="{{route('hospedagem.distribuicao.ofsup')}}"> Oficiais Superiores </a>
        </li>
        <li>
            <a href="{{route('hospedagem.distribuicao.capten')}}"> Oficiais Intermediário / Subalternos / SC NS</a>
        </li>
        <li>
            <a href="{{route('hospedagem.distribuicao.subten')}}"> Subtenentes / Sargentos / SC NM</a>
        </li>
        <li>
            <a href="{{route('hospedagem.distribuicao.motorhome')}}"> Motor-Home </a>
        </li>
        <li>
            <a href="{{route('hospedagem.distribuicao.camping')}}"> Camping</a>
        </li>
    </ul>
</li>            
    </ul>
</li>



<li class="@if(request()->is('admin/relatorio/*')) active open @endif">
    <a href="">
        <i class="fa fa-pie-chart" aria-hidden="true"></i> Relatórios <i class="fa arrow"></i>
    </a>
    <ul class="sidebar-nav">
         <li>
            <a href="{{ route('relatorio.index') }}">
                <i class="fa fa-calendar"></i>
                Mensal
            </a>
        </li>
        <li>
            <a href="{{ route('relatorio.arrecadacao') }}">
                <i class="fa fa-check-circle"></i>
                Hospedados Pagos
            </a>
        </li>
        <li>
            <a href="{{ route('relatorio.cancelados') }}">
                <i class="fa fa-times-circle"></i>
                Cancelados Pagos
            </a>
        </li>
        <li>
            <a href="{{ route('arrecadacaoTotlal.index') }}">
                <i class="fa fa-line-chart"></i>
                Arrecadação Total
            </a>
        </li>
    </ul>
</li>