<?php
/**
 * Created by PhpStorm.
 * User: chipkA
 * Date: 3/2/17
 * Time: 8:42 PM
 */

namespace App\Api\Transformers\Auth;

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