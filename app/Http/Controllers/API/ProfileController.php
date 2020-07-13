<?php


namespace App\Http\Controllers\API;


use App\Http\Models\Client;
use App\Http\Utils\Utils;

class ProfileController
{
    public function updateMenuAppLogo()
    {
        $user = request('user');
        if (isset(request()->file)) {
            $imageName = time() . '.' . request()->file->getClientOriginalExtension();

            $original_image_path = public_path('media/company_logos');
            if (!file_exists($original_image_path)) {
                mkdir($original_image_path);
            }

            request()->file->move($original_image_path, $imageName);

            Client::where('id', $user->id)->update(['company_logo' => $imageName]);

            $client = Client::select('template_no as templateNo', 'category_background_color as categoryBgColor',
                'banner_color as bannerColor', 'font_color as fontColor', 'product_background_color as productBgColor',
                'company_logo as companyLogo')
                ->where('id', $user->id)->first();

            if ($client == null) {
                return Utils::makeResponse([], config('constants.response-message.invalid-params'));
            }

            return Utils::makeResponse(
                $client
            );
        }

    }

    public function updateMenuAppColors()
    {
        $user = request('user');
        $template_no = request('template-no');
        $category_background_color = request('category-background-color');
        $product_background_color = request('product-background-color');
        $banner_color = request('banner-color');
        $font_color = request('font-color');

        $update_array = array();

        if (isset($category_background_color)) {
            $update_array['category_background_color'] = $category_background_color;
        }

        if (isset($product_background_color)) {
            $update_array['product_background_color'] = $product_background_color;
        }

        if (isset($banner_color)) {
            $update_array['banner_color'] = $banner_color;
        }

        if (isset($font_color)) {
            $update_array['font_color'] = $font_color;
        }

        if (isset($template_no)) {
            $update_array['template_no'] = $template_no;
        }

        Client::where('id', $user->id)->update($update_array);

        $client = Client::select('template_no as templateNo', 'category_background_color as categoryBgColor',
            'banner_color as bannerColor', 'font_color as fontColor', 'product_background_color as productBgColor',
            'company_logo as companyLogo')
            ->where('id', $user->id)->first();

        if ($client == null) {
            return Utils::makeResponse([], config('constants.response-message.invalid-params'));
        }

        return Utils::makeResponse(
            $client
        );

    }
}
