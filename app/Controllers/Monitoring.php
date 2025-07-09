<?php

namespace App\Controllers;

class Monitoring extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Monitoramento - Project Manager',
            'page'  => 'monitoring'
        ];

        return view('layout/main', $data);
    }
}
