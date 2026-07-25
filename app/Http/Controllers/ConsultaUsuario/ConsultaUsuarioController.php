<?php

namespace App\Http\Controllers\ConsultaUsuario;

use App\Http\Controllers\Controller;
use App\Hospede;
use App\User;
use Illuminate\Http\Request;

class ConsultaUsuarioController extends Controller
{
    /**
     * Exibe a página de consulta e pesquisa o usuário pelo CPF.
     */
    public function index(Request $request)
    {
        /*
         * Variáveis iniciais.
         */
        $usuario = null;
        $pedidos = collect();
        $cpfDigitado = $request->input('cpf', '');
        $pesquisou = false;

        /*
         * Só realiza a consulta quando o CPF for informado.
         */
        if ($request->filled('cpf')) {
            $pesquisou = true;

            /*
             * Remove pontos, traços, espaços e qualquer caractere
             * que não seja número.
             *
             * Exemplo:
             * 123.456.789-00 vira 12345678900.
             */
            $cpf = preg_replace(
                '/[^0-9]/',
                '',
                $request->input('cpf')
            );

            /*
             * Valida se o CPF possui exatamente 11 números.
             */
            if (strlen($cpf) !== 11) {
                return redirect()
                    ->route('consulta.usuario.index')
                    ->withInput()
                    ->withErrors([
                        'Informe um CPF válido com 11 números.',
                    ]);
            }

            /*
             * Procura o usuário pelo CPF.
             */
            $usuario = User::with([
                'posto',
                'om',
                'perfil',
                'uf',
                'cidade',
            ])
                ->where('cpf', $cpf)
                ->first();

            /*
             * Caso o usuário exista, busca todos os pedidos dele.
             */
            if ($usuario) {
                $pedidos = Hospede::with([
                    'tipouh',
                    'undHB',
                    'status_hospedagem',
                ])
                    ->where('user_id', $usuario->id)
                    ->orderBy('data_inicio', 'desc')
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }

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