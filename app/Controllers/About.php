<?php

namespace App\Controllers;

use Config\Developer;

class About extends BaseController
{
    public function index(): string
    {
        // This page is static: no database query is needed.
        return view('pages/about', [
            'pageTitle'  => 'About',
            'activePage' => 'about',
            'developer' => config(Developer::class),
        ]);
    }
}
