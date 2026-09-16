<?php

namespace App\Http\Controllers;

use App\User;
use Crypt;
use Illuminate\Support\Facades\Password;

class AdminPasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send($id)
    {
        $userId = Crypt::decrypt($id);
        $user = User::findOrFail($userId);

        if (empty($user->email)) {
            \Session::flash('message', [
                'msg' => 'Não foi possível enviar a redefinição: o usuário não possui e-mail cadastrado.',
                'class' => 'danger',
            ]);

            return redirect()->back();
        }

        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);

        \Session::flash('message', [
            'msg' => 'Link para criação de uma nova senha enviado para o e-mail do usuário.',
            'class' => 'success',
        ]);

        return redirect()->back();
    }
}
