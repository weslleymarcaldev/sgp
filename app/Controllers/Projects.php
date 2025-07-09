<?php

namespace App\Controllers;

class Projects extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Projetos - Project Manager',
            'page'  => 'projects'
        ];

        return view('layout/main', $data);
    }
}
