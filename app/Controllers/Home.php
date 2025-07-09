<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Dashboard - Project Manager',
            'page'  => 'dashboard'
        ];

        return view('layout/main', $data);
    }
}
