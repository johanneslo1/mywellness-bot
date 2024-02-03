<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\Rule;

class ValidHCaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(config('services.hcaptcha.enabled') === false) {
            return true;
        }

        // convert curl to http fascade of laravel
        $res = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => config('services.hcaptcha.secret'),
            'sitekey' => config('services.hcaptcha.sitekey'),
            'remoteip' => request()->ip(),
            'response' => $value,
        ]);

        if($res->status() === 200) {
            $body = $res->json();

            if($body['success'] === true) {
                return true;
            }
        }


        return false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Das Captcha konnte nicht verifiziert werden. Bitte versuchen Sie es erneut.';
    }
}
