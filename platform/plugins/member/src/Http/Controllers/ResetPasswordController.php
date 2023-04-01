<?php

namespace Botble\Member\Http\Controllers;

use App\Http\Controllers\Controller;
use Botble\ACL\Traits\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use SeoHelper;
use Theme;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public string $redirectTo = '/';

    public function __construct()
    {
        $this->redirectTo = route('public.member.dashboard');
    }

    public function showResetForm(Request $request, $token = null)
    {
        SeoHelper::setTitle(__('Reset Password'));

        if (view()->exists(Theme::getThemeNamespace() . '::views.member.auth.passwords.reset')) {
            return Theme::scope('member.auth.passwords.reset', ['token' => $token, 'email' => $request->email])->render();
        }

        return view('plugins/member::auth.passwords.reset', ['token' => $token, 'email' => $request->email]);
    }

    public function broker()
    {
        return Password::broker('members');
    }

    protected function guard()
    {
        return auth('member');
    }
}
