<?php

namespace App\Http\Controllers\ConsultaUsuario;

use App\Http\Controllers\Controller;
use App\Hospede;
use App\User;
use Illuminate\Http\Request;

class ConsultaUsuarioController extends Controller
{
    /**
     * Exibe a página de consulta e pesquisa o usuário.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VARIÁVEIS INICIAIS
        |--------------------------------------------------------------------------
        */

        $usuario = null;

        $pedidos = collect();

        $cpfDigitado = $request->input('cpf', '');

        $pesquisou = false;

        $pesquisa = trim(
            $request->input('cpf', '')
        );


        /*
        |--------------------------------------------------------------------------
        | REALIZA A PESQUISA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('cpf')) {

            $pesquisou = true;


            /*
             * Remove tudo que não for número.
             *
             * Exemplo:
             * 123.456.789-00
             *
             * vira:
             *
             * 12345678900
             */
            $cpfNumerico = preg_replace(
                '/\D/',
                '',
                $pesquisa
            );


            /*
            |--------------------------------------------------------------------------
            | BUSCA O USUÁRIO
            |--------------------------------------------------------------------------
            |
            | Permite pesquisar por:
            |
            | - Nome
            | - E-mail
            | - Identidade militar
            | - CPF
            |
            |--------------------------------------------------------------------------
            */

            $usuario = User::with([
                    'posto',
                    'om',
                    'perfil',
                    'uf',
                    'cidade',
                ])
                ->where(function ($consulta) use (
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
                     * Pesquisa CPF somente se houver números.
                     */
                    if ($cpfNumerico !== '') {

                        $consulta->orWhere(
                            'cpf',
                            'LIKE',
                            '%' . $cpfNumerico . '%'
                        );

                    }

                })

                /*
                 * Retorna apenas o primeiro usuário encontrado.
                 *
                 * Sem o first(), $usuario seria apenas
                 * um Query Builder.
                 */
                ->first();


            /*
            |--------------------------------------------------------------------------
            | BUSCA OS PEDIDOS DO USUÁRIO
            |--------------------------------------------------------------------------
            */

            if ($usuario) {

                $pedidos = Hospede::with([
                        'tipouh',
                        'undHB',
                        'status_hospedagem',
                    ])

                    ->where(
                        'user_id',
                        $usuario->id
                    )

                    ->orderBy(
                        'data_inicio',
                        'desc'
                    )

                    ->orderBy(
                        'id',
                        'desc'
                    )

                    ->get();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | RETORNA A VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'usuario.consulta_cpf',
            compact(
                'usuario',
                'pedidos',
                'cpfDigitado',
                'pesquisou'
            )
        );
    }
}