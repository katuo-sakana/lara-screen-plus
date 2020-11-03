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
        return view('welcome');
    }

    public function capture(Request $request)
    {
        $capture_url = $request->url;

        $puppeteer = new Puppeteer;
        // $browser = $puppeteer->launch();
        $browser = $puppeteer->launch([
            'executablePath' => env('CHROMIUM_PATH'),
            'args' => ['--no-sandbox', '--disable-setuid-sandbox']
        ]);

        $page = $browser->newPage();
        $page->goto($capture_url);
        $page->screenshot(['path' => 'example05.png']);

        $browser->close();
        return view('comment');
    }

    public function comment()
    {
        return view('comment');
    }
}
