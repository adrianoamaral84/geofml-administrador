<li class="{{ $menuAtivo == 'comissoes' ? 'active' : '' }}">
    <a href="{{ route('comissoes') }}">
        <i class="fa fa-th-large"></i> Comissões de Seleção
    </a>
</li>
<li class="{{ $menuAtivo == 'inscricoes' ? 'active' : '' }}">
    <a href="{{ route('inscricoes') }}">
        <i class="fa fa-pencil-square-o"></i> Totais de Inscritos  </a>
</li>
<li class="{{ $menuAtivo == 'candidato' ? 'active' : '' }}">
    <a href="{{ route('candidato') }}">
        <i class="fa fa-pencil-square-o"></i> Consulta Candidato  </a>
</li>
<li class="{{ $menuAtivo == 'relatorios' ? 'active open' : '' }}">
    <a href="">
        <i class="fa fa-table"></i> Relatórios <i class="fa arrow"></i>
    </a>
    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('relClassificadosArea') }}"> Classificados Por Área </a>
        </li>
        <li>
            <a href="{{ route('relClassificadosGuarnicao') }}"> Classificados Por Guarnição </a>
        </li>
        <li>
            <a href="{{ route('relArea') }}"> Por Área </a>
        </li>
        <li>
            <a href="{{ route('relGuarnicao') }}"> Por Guarnição </a>
        </li>
        <li>
            <a href="{{ route('relLog') }}"> Log por Avaliador </a>
        </li>
    </ul>
</li>