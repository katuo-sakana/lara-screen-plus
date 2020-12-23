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
            // 'path' => "{$directory_path}/example01.png",
            'path' => "example01.png",
            'fullPage' => true
        ]);
        $browser->close();

        $page = new Page;
        $page->url = $random_directory;
        $page->save();

        return redirect()->route('comment', ['directory' => $random_directory]);
    }

    public function comment($directory, Page $page, Comment $comment)
    {
        // リダイレクト先のpagesテーブルのid取得
        $page_id = $page->where('url', $directory)->first()->id;
        $image_url = "/storage/{$directory}/example01.png";

        // 既存のコメント情報があった場合表示
        $comments = $comment->where('page_id', $page_id)->get();

        return view('comment', compact('image_url', 'directory', 'page_id', 'comments'));
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
                'form_status' => $request->form_status,
                'done' => $request->done,
                'is_readonly' => $request->is_readonly,
                'message' => $request->message,
                'index' => $request->index,
                'position_x' => $request->position_x,
                'position_y' => $request->position_y,
                'window_y' => $request->window_y,
                'position_form_x' => $request->position_form_x,
                'position_form_y' => $request->position_form_y,
                'page_id' => $request->page_id,
            ]
        );
    }
}
