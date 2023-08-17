<?php

namespace App\Utils;

use Illuminate\Support\Facades\Vite;

class Utils
{
    /**
     * @param $value
     * @return string
     */
    public static function getStorageImage($value): string
    {
        try {
            $storageImg = 'app/public/img/';
            if (file_exists(storage_path($storageImg . $value))) {
                return Vite::asset('storage/' . $storageImg . $value);
            } else {
                return Vite::asset('resources/img/icons8-male-user.png');
            }
        } catch (\Exception $e) {
            return Vite::asset('resources/img/icons8-male-user.png');
        }
    }
}
