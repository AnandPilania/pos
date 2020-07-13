<?php


namespace App\Http\Controllers\API;


use App\Http\Models\Client;
use App\Http\Utils\Utils;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController
{
    public function login()
    {
        $validation = Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validation->fails()) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        $credentials = request(['email', 'password']);

        $user = Client::where([
            ['email', $credentials['email']],
            ['active', 1],
        ])->first();

        if ($user == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return Utils::makeResponse([], config('constants.response-message.invalid-credentials'));
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return Utils::makeResponse([], config('constants.response-message.error-generate-api-token'));
        }

        $user_info = [
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'birthday' => $user->birthday,
            'startDate' => $user->start_date,
            'expireDate' => $user->expire_date,
            'price' => $user->price,
            'company' => $user->company,
            'street' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'zipCode' => $user->zipcode,
            'avatar' => $user->avatar,
            'phone' => $user->phonenumber,
            'companyLogo' => $user->company_logo
        ];

        $menu_app_settings = [
            'templateNo' => $user->template_no,
            'bannerColor' => $user->banner_color,
            'categoryBgColor' => $user->category_background_color,
            'productBgColor' => $user->product_background_color,
            'fontColor' => $user->font_color,
            'companyLogo' => $user->company_logo
        ];

        return Utils::makeResponse([
            'apiToken' => $token,
            'userInfo' => $user_info,
            'menuAppConfig' => $menu_app_settings
        ]);
    }

    public function getUserAuth()
    {
        $user = request('user');

        $client = Customers::select('id', 'first_name', 'last_name', 'email', 'template_no', 'category_background_color',
            'banner_color', 'font_color', 'product_background_color', 'company_logo')
            ->where('id', $user->id)->first();

        if ($client == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        return Utils::makeResponse([
            'user' => $client,
        ]);

    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function refresh() {
        return $this->respondWithToken(auth('api')->refresh());
    }

    public function logout()
    {
        auth('api')->logout();
        return Utils::makeResponse();
    }

    public function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

}
