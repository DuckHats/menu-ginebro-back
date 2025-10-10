<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        $config = config('welcome');

        return view('welcome', [
            'appName' => $config['name'],
            'description' => $config['description'],
            'focusUrl' => $config['focus_url'],
            'documentation_url' => $config['documentation_url'],
            'primaryColor' => $config['primary_color'],
            'accentColor' => $config['accent_color'],
            'footerText' => $config['footer_text'],
        ]);
    }
}
