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

        return redirect()->route('comment', ['directory' => $random_directory]);
    }

    public function comment($directory, Page $page)
    {
        // リダイレクト先のpagesテーブルのid取得
        $page_id = $page->where('url', $directory)->first()->id;
        $image_url = "/storage/{$directory}/example01.png";
        return view('comment', compact('image_url', 'directory', 'page_id'));
    }

    public function commentCreate(Request $request, Comment $comment)
    {
        $comment->updateOrInsert(
            [
                'page_id' => $request->page_id,
                'index' => $request->index
            ],
            [
                'status' => $request->status,
                'form_status' => $request->formStatus,
                'done' => $request->done,
                'is_readonly' => $request->is_readonly,
                'message' => $request->message,
                'index' => $request->index,
                'position_x' => $request->positionX,
                'position_y' => $request->positionY,
                'window_y' => $request->windowY,
                'position_form_x' => $request->positionFormX,
                'position_form_y' => $request->positionFormY,
                'page_id' => $request->page_id,
            ]
        );
    }
}
