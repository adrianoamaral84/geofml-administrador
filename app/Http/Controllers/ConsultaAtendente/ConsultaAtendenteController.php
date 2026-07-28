<?php

namespace App\Http\Controllers\ConsultaAtendente;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ConsultaAtendenteController extends Controller
{
    /**
     * Lista os atendentes ativos do sistema.
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
            ->where('perfil_id', 3);

        /*
         * Pesquisa opcional por nome, nome de guerra,
         * CPF ou e-mail.
         */
        if ($pesquisa !== '') {
            $cpfNumerico = preg_replace('/\D/', '', $pesquisa);

            $query->where(function ($consulta) use (
                $pesquisa,
                $cpfNumerico
            ) {
                $consulta
                    ->where('name', 'LIKE', '%' . $pesquisa . '%')
                    
                    ->orWhere(
                        'email',
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

        $atendentes = $query
            ->orderBy('name')
            ->paginate(20);

        /*
         * Mantém o termo pesquisado na paginação.
         */
        $atendentes->appends([
            'pesquisa' => $pesquisa,
        ]);

        return view(
            'usuario.consulta_atendentes',
            compact('atendentes', 'pesquisa')
        );
    }
}