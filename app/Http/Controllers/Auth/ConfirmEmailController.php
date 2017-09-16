<?php

namespace ManyLinks\Http\Controllers\Auth;

use Illuminate\Http\Request;
use ManyLinks\Http\Controllers\Controller;
use ManyLinks\Models\User;

class ConfirmEmailController extends Controller
{
    public function __invoke($code, Request $request)
    {
        $updated = User::where([['confirmation_code', '=', $code], ['email', '=', $request->get('email')]])
            ->update([
                'confirmed' => true,
                'confirmation_code' => null
            ]);

        return $updated
            ? redirect('/')
            : redirect()->route('auth.email-verification.error');
    }
}
