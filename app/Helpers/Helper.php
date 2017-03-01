<?php

namespace App\Helpers;

class Helper
{
    public static function dd($data, $isDie=false)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        if($isDie) {
            die();
        }
    }
}