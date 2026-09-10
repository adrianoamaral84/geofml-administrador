<?php

namespace App\Http\Controllers\ConsultaPttc;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ConsultaPttcController extends Controller
{
    /**
     * Lista os usuários ativos identificados como PTTC.
     */
    public function index(Request $request)
    {
        /*
         * Pesquisa digitada pelo usuário.
         */
        $pesquisa = trim(
            $request->input('pesquisa', '')
        );


        /*
         * Consulta base.
         */
        $query = User::query()
            ->with([
                'posto',
                'om',
                'perfil',
            ])
            ->where('status', 1)
            ->where('pttc', 1);


        /*
         * Pesquisa opcional por:
         *
         * - Nome
         * - CPF
         * - Identidade militar
         * - E-mail
         */
        if ($pesquisa !== '') {

            /*
             * Remove pontos, traços e outros caracteres
             * caso o usuário pesquise um CPF formatado.
             */
            $cpfNumerico = preg_replace(
                '/\D/',
                '',
                $pesquisa
            );


            $query->where(
                function ($consulta) use (
                    $pesquisa,
                    $cpfNumerico
                ) {

                    $consulta
                        ->where(
                            'name',
                            'LIKE',
                            '%' . $pesquisa . '%'
                        )

                        ->orWhere(
                            'email',
                            'LIKE',
                            '%' . $pesquisa . '%'
                        )

                        ->orWhere(
                            'idtMil',
                            'LIKE',
                            '%' . $pesquisa . '%'
                        );


                    /*
                     * Pesquisa CPF somente se houver
                     * algum número digitado.
                     */
                    if ($cpfNumerico !== '') {

                        $consulta->orWhere(
                            'cpf',
                            'LIKE',
                            '%' . $cpfNumerico . '%'
                        );

                    }

                }
            );

        }


        /*
         * Ordenação.
         *
         * Padrão:
         * Nome crescente.
         */
        $ordem = $request->input(
            'ordem',
            'name'
        );

        $direcao = $request->input(
            'direcao',
            'asc'
        );


        /*
         * Apenas estas colunas podem ser
         * utilizadas no ORDER BY.
         *
         * Isso também evita manipulação
         * indevida do parâmetro da URL.
         */
        $colunasPermitidas = [
            'id',
            'id_posto',
            'name',
            'cpf',
            'email',
            'id_om',
        ];


        /*
         * Se receber uma coluna inválida,
         * volta para ordenação por nome.
         */
        if (!in_array(
            $ordem,
            $colunasPermitidas,
            true
        )) {

            $ordem = 'name';

        }


        /*
         * Permite somente ASC ou DESC.
         */
        $direcao =
            strtolower($direcao) === 'desc'
                ? 'desc'
                : 'asc';


        /*
         * A ordenação acontece no banco ANTES
         * da paginação.
         *
         * Portanto:
         *
         * ORDER BY id ASC
         * LIMIT 20
         *
         * ou
         *
         * ORDER BY id DESC
         * LIMIT 20
         */
        $usuariosPttc = $query
            ->orderBy(
                $ordem,
                $direcao
            )
            ->paginate(20);


        /*
         * Mantém:
         *
         * pesquisa
         * ordem
         * direcao
         *
         * ao mudar de página.
         *
         * Exemplo:
         *
         * ?pesquisa=joao&ordem=id&direcao=desc&page=2
         */
        $usuariosPttc->appends(
            $request->except('page')
        );


        /*
         * Retorna a view.
         */
        return view(
            'usuario.consulta_pttc',
            compact(
                'usuariosPttc',
                'pesquisa'
            )
        );
    }
}