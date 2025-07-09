<?php

namespace App\Controllers;

class Settings extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Configurações - Project Manager',
            'page'  => 'settings'
        ];

        return view('layout/main', $data);
    }
}
