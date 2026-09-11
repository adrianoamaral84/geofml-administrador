<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'cpf';
    }

    protected function credentials(Request $request)
    {
        if (isset($request['cpf'])) {
            $request['cpf'] = str_replace([".", "-"], "", $request['cpf']);
        }

        $messages = [
            'cpf.required' => 'Campo CPF Obrigatório!',
            'password.required' => 'Campo Senha Obrigatório!',
        ];

        $validatedData = $request->validate([
            'cpf' => 'required',
            'password' => 'required|string',
        ], $messages);

        return [
            'cpf' => $validatedData['cpf'],
            'password' => $validatedData['password'],
            'status' => [1, 3, 5, 6],
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        // Contas antigas que ainda usam o CPF como senha precisam criar uma nova senha.
        if ($user->cpf && $this->passwordMatches($user->cpf, $user->password)) {
            if ($user->email) {
                try {
                    Password::broker()->sendResetLink(['email' => $user->email]);
                } catch (\Throwable $e) {
                    \Log::error('Erro ao enviar redefinição para usuário com senha legada.', [
                        'user_id' => $user->id,
                        'erro' => $e->getMessage(),
                    ]);
                }
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            \Session::flash('message', [
                'msg' => $user->email
                    ? 'Por segurança, sua senha antiga precisa ser substituída. Enviamos um link para o seu e-mail para criar uma nova senha.'
                    : 'Por segurança, sua senha antiga precisa ser substituída. Procure o administrador para redefinir seu acesso.',
                'class' => 'warning',
            ]);

            return redirect('/login');
        }

        $rolesComExpiracao = [
            'administrador_especial',
            'administrador_geral',
            'auxiliar_administrador_geral',
            'administrador',
            'atendente',
        ];

        $contaAdministrativa = false;
        foreach ($rolesComExpiracao as $role) {
            if ($user->hasRole($role)) {
                $contaAdministrativa = true;
                break;
            }
        }

        if ($contaAdministrativa && $user->last_login_at) {
            $ultimoAcesso = Carbon::parse($user->last_login_at);

            if ($ultimoAcesso->lt(now()->subDays(90))) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                \Session::flash('message', [
                    'msg' => 'Conta bloqueada por mais de 90 dias sem acesso. Solicite ao administrador um reset de senha para reativar a conta.',
                    'class' => 'warning',
                ]);

                return redirect('/login');
            }
        }

        $user->last_login_at = now();
        $user->save();

        $hoje = date('Y-m-d');
        if ($user->indeterminado != 1) {
            if (strtotime($user->validade) < strtotime($hoje)) {
                \Session::flash('message', [
                    'msg' => 'Seu documento de identidade está com a data de validade vencida! Favor atualizar o documento para prosseguir',
                    'class' => 'danger',
                ]);
            }
        }

        if ($user->hasRole('administrador_especial')) {
            return redirect('/');
        }
        if ($user->hasRole('administrador_geral')) {
            return redirect('/');
        }
        if ($user->hasRole('auxiliar_administrador_geral')) {
            return redirect('/');
        }
        if ($user->hasRole('atendente')) {
            return redirect('/atendente');
        }
        if ($user->hasRole('hospede')) {
            return redirect('/hospede');
        }
        if ($user->hasRole('precadastro')) {
            return redirect('/precadastro');
        }
        if ($user->hasRole('administrador')) {
            return redirect('/administrador');
        }

        return redirect('/login');
    }

    protected function passwordMatches($plain, $hash)
    {
        try {
            return Hash::check((string) $plain, (string) $hash);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
