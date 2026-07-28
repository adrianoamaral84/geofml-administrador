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

        $usuariosPttc = $query
            ->orderBy('name')
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