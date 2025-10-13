<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $post = Post::all();
        return view('post.index', compact('post'));
    }

    public function create()
        {
            return view('post.create');

        }

        public function store(Request $request)
        {
            $post = new Post;
            $post->title = $request->title;
            $post->content = $request->content;
            $post->save();
            return redirect()->route('post.index');
        }

        public function edit($id)
        {
            $post = Post::findOrFail($id);
            return view('post.edit', compact('post'));
        }
       
        public function update(Request $request, $id)
        {
             $post =Post::findOrFail($id);
            $post->title = $request->title;
            $post->content = $request->content;
            $post->save();
            return redirect()->route('post.index');
        }
    

        public function destory($id)
        {
            $post = Post::findOrFail($id);
            $post->delete();
            return redirect()->route('post.index');
        }
    
}
