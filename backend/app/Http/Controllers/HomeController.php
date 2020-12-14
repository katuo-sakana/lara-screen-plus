<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Nesk\Puphpeteer\Puppeteer;
use App\Page;
use App\Comment;

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
        $random_directory = Str::random(20);
        Storage::makeDirectory($random_directory);
        $directory_path = storage_path("app/public/{$random_directory}");

        $puppeteer = new Puppeteer;
        // $browser = $puppeteer->launch();
        $browser = $puppeteer->launch([
            'executablePath' => env('CHROMIUM_PATH'),
            'args' => ['--no-sandbox', '--disable-setuid-sandbox']
        ]);

        $page = $browser->newPage();
        $page->goto($capture_url);
        $page->screenshot([
            'path' => "{$directory_path}/example01.png",
            'fullPage' => true
        ]);
        $browser->close();

        $page = new Page;
        $page->url = $random_directory;
        $page->save();

        return redirect()->route('comment', ['id' => $random_directory]);
    }

    public function comment($id)
    {
        $url = "/storage/{$id}/example01.png";
        return view('comment', compact('url', 'id'));
    }

    public function commentCreate(Request $request, Comment $comment)
    {
        // return ['title' => request('title'), 'content' => request('content')];
        $page = new Page;
        $page->url = 'dummy2';
        $page->save();
        dd(request('title'));
        $comment->status = $request->status;
    }
}
