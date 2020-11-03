<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nesk\Puphpeteer\Puppeteer;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $puppeteer = new Puppeteer;
        // $browser = $puppeteer->launch();
        $browser = $puppeteer->launch([
            'executablePath' => env('CHROMIUM_PATH'),
            'args' => ['--no-sandbox', '--disable-setuid-sandbox']
            // 'executablePath' => '/path/to/Chrome'
        ]);

        $page = $browser->newPage();
        $page->goto('https://www.s-oj.com/ec-business/system/php-puppeteer-puphpeteer/');
        $page->screenshot(['path' => 'example04.png']);

        $browser->close();
        return view('welcome');
    }
}
