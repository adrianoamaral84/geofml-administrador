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
        $pesquisa = trim($request->input('pesquisa', ''));

        $query = User::query()
            ->with([
                'posto',
                'om',
                'perfil',
            ])
            ->where('status', 1)
            ->where('pttc', 1);

        /*
         * Pesquisa opcional por nome, nome de guerra,
         * CPF, identidade militar ou e-mail.
         */
        if ($pesquisa !== '') {
            $cpfNumerico = preg_replace('/\D/', '', $pesquisa);

            $query->where(function ($consulta) use (
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

                if ($cpfNumerico !== '') {
                    $consulta->orWhere(
                        'cpf',
                        'LIKE',
                        '%' . $cpfNumerico . '%'
                    );
                }
            });
        }

                // Captura os parâmetros enviados pelo clique na coluna (Blade Etapa 1)
        $ordem = $request->input('ordem', 'name');
        $direcao = $request->input('direcao', 'asc');

        // Lista de colunas permitidas para evitar injeção SQL maliciosa
        $colunasPermitidas = ['id', 'id_posto', 'name', 'cpf', 'email', 'id_om'];
        if (!in_array($ordem, $colunasPermitidas)) {
            $ordem = 'name';
        }

        // Garante que a direção seja apenas 'asc' ou 'desc'
        $direcao = ($direcao === 'desc') ? 'desc' : 'asc';

        // Executa a ordenação global no MariaDB e faz a paginação de 20 em 20
        $usuariosPttc = $query
            ->orderBy($ordem, $direcao)
            ->paginate(20);


        /*
         * Mantém o filtro ao trocar de página.
         */
        $usuariosPttc->appends([
            'pesquisa' => $pesquisa,
        ]);

        return view(
            'usuario.consulta_pttc',
            compact(
                'usuariosPttc',
                'pesquisa'
            )
        );
    }
}
