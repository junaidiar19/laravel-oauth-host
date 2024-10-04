<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected TokenRepository $tokenRepository, protected RefreshTokenRepository $refreshTokenRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // method to revoke the user's access token & refresh token
        $this->revokeToken($request);

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * Revoke the user's access token & refresh token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function revokeToken($request)
    {
        $tokens = $request->user()->tokens;

        foreach ($tokens as $token) {
            // Revoke an access token...
            $this->tokenRepository->revokeAccessToken($token->id);

            // Revoke all of the token's refresh tokens...
            $this->refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
        }
    }
}
