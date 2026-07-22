<li class="@if(request()->is('admin/usuarios/*')) active open @endif">
    <a href="">
        <i class="fa fa-users"></i>
        Usuários
        <i class="fa arrow"></i>
    </a>

    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('user.index') }}">
                <i class="fa fa-user-cog"></i>
                Gerenciar
            </a>
        </li>

        <li>
            <a href="{{ route('lista.pedidos') }}">
                <i class="fa fa-check-square"></i>
                Validar
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
            <a href="{{ route('user.mecenas') }}">
                <i class="fa fa-star"></i>
                Mecenas
            </a>
        </li>
    </ul>
</li>


<li class="@if(request()->is('admin/habitacao/*')) active open @endif">
    <a href="">
        <i class="fa fa-home"></i>
        Habitação
        <i class="fa arrow"></i>
    </a>

    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('habitacao.index') }}">
                <i class="fa fa-building"></i>
                Tipo UH
            </a>
        </li>

        <li>
            <a href="{{ route('classehabitacao.index') }}">
                <i class="fa fa-tags"></i>
                Classe Habitacional
            </a>
        </li>

        <li>
            <a href="{{ route('grupo_destinacao.index') }}">
                <i class="fa fa-sitemap"></i>
                Grupo de Destinação
            </a>
        </li>

        <li>
            <a href="{{ route('uh.index') }}">
                <i class="fa fa-bed"></i>
                Unidade Habitacional
            </a>
        </li>

        <li>
            <a href="{{ route('grupo_tarifa.index') }}">
                <i class="fa fa-money"></i>
                Grupo Tarifa
            </a>
        </li>

        <li>
            <a href="{{ route('tarifas.index') }}">
                <i class="fa fa-calculator"></i>
                Tarifas
            </a>
        </li>

        <li>
            <a href="{{ route('tarifaextra.index') }}">
                <i class="fa fa-plus-circle"></i>
                Tarifa Extra
            </a>
        </li>
    </ul>
</li>


<li class="@if(request()->is('admin/administracao/*')) active open @endif">
    <a href="">
        <i class="fa fa-cogs"></i>
        Administração
        <i class="fa arrow"></i>
    </a>

    <ul class="sidebar-nav">
        <li>
            <a href="{{ route('indexuf') }}">
                <i class="fa fa-map-marker"></i>
                UF
            </a>
        </li>

        {{--
        <li>
            <a href="{{ route('guarnicao.index') }}">
                <i class="fa fa-map"></i>
                Guarnição
            </a>
        </li>

        <li>
            <a href="{{ route('forca.index') }}">
                <i class="fa fa-shield"></i>
                Força
            </a>
        </li>
        --}}

        <li>
            <a href="{{ route('postograduacao.list') }}">
                <i class="fa fa-chevron-up"></i>
                Posto / Graduação
            </a>
        </li>

        <li>
            <a href="{{ route('gerenciarom.listOMs') }}">
                <i class="fa fa-university"></i>
                Gerenciar OMs
            </a>
        </li>

        <li>
            <a href="{{ route('dadosgerais.index') }}">
                <i class="fa fa-database"></i>
                Dados Gerais
            </a>
        </li>

        <li>
            <a href="{{ route('configurarhospedagem.index') }}">
                <i class="fa fa-sliders"></i>
                Configurar Hospedagem
            </a>
        </li>

        <li>
            <a href="{{ route('pagamento.index') }}">
                <i class="fa fa-credit-card"></i>
                PagTesouro Configuração
            </a>
        </li>

        <li>
            <a href="{{ route('email.index') }}">
                <i class="fa fa-envelope"></i>
                Gerenciar E-mails
            </a>
        </li>
    </ul>
</li>


<li class="@if(request()->is('admin/hospedagem/*')) active open @endif">
    <a href="">
        <i class="fa fa-h-square"></i>
        Hospedagem
        <i class="fa arrow"></i>
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
                    <a href="{{ route('hospedagem.distribuicao.gen') }}">
                        <i class="fa fa-star"></i>
                        Oficiais Generais
                    </a>
                </li>

                <li>
                    <a href="{{ route('hospedagem.distribuicao.ofsup') }}">
                        <i class="fa fa-star-half-o"></i>
                        Oficiais Superiores
                    </a>
                </li>

                <li>
                    <a href="{{ route('hospedagem.distribuicao.capten') }}">
                        <i class="fa fa-user"></i>
                        Oficiais Intermediários / Subalternos / SC NS
                    </a>
                </li>

                <li>
                    <a href="{{ route('hospedagem.distribuicao.subten') }}">
                        <i class="fa fa-users"></i>
                        Subtenentes / Sargentos / SC NM
                    </a>
                </li>

                <li>
                    <a href="{{ route('hospedagem.distribuicao.motorhome') }}">
                        <i class="fa fa-truck"></i>
                        Motor-Home
                    </a>
                </li>

                <li>
                    <a href="{{ route('hospedagem.distribuicao.camping') }}">
                        <i class="fa fa-tree"></i>
                        Camping
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>


<li class="@if(request()->is('admin/relatorio/*')) active open @endif">
    <a href="">
        <i class="fa fa-pie-chart"></i>
        Relatórios
        <i class="fa arrow"></i>
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
