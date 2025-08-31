<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Menu Principal',
            'appName' => ConfigHelper::appName(),
            'empresa' => ConfigHelper::empresa(),
            'logo'    => ConfigHelper::get('c3_logo_path') ?? IMG_PATH . 'logo.png',
        ];

        return view('home', $data);
    }
}
