<?php

namespace App\Api\v01\Transformers\Auth;

use League\Fractal\TransformerAbstract;

class LoginTransformer extends TransformerAbstract
{
    public function transform(string $token)
    {
        return [
            'api_token' => $token
        ];
    }
}