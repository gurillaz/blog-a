<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Requests\ToggleBookmarkRequest;
use App\Http\Requests\UpdateUserRequest;
use Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    //use SendsPasswordResetEmails;
    //use ResetsPasswords;
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }
    /*use ResetsPasswords, SendsPasswordResetEmails {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }*/

    // Registration process
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->role = "client";
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    // Login process
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Email or password incorrect!'
            ], 400);
        }


        activity()->log('logged_in');

        return response([
            'status' => 'success'
        ])->header('Authorization', $token);
    }

    public function logout()
    {
        $this->guard()->logout();

        activity()->log('logged_out');

        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    // Fetch a specific user's informations
    public function user(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user['bookmarks'] = $user->bookmarks()->pluck('id');
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        if ($token = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    private function guard()
    {
        return Auth::guard();
    }

    public function sendPasswordResetLink(request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Reset password link sent.',
            'data' => $response
        ]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Email could not be sent to the email address'
        ]);
    }

    /**
     * Handle reset password 
     */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully.']);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Failed, Invalid Token.']);
    }

    /**
     * Change user data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        $user->name = $validated['name'];


        if (isset($validated['email']) && $validated['email'] != $user->email) {
            $user->email = $validated['email'];
            $user->email_verified_at = NULL;
            event(new VerifyEmail());
        }

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();




        return response()->json(
            [
                'status' => 'success',
                'user' => $user
            ],
            200
        );
    }
    public function toggle_bookmark(ToggleBookmarkRequest $request)
    {
        $validated = $request->validated();


        Auth::user()->bookmarks()->toggle($validated['article_id']);
        activity()->log('toggled_bookmark');
        return response()->json(
            [
                'status' => 'success',
            ],
            200
        );
    }
}
