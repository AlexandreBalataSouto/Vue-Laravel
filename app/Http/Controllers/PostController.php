<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::orderBy('updated_at', 'DESC')->paginate(3);
    }

    public function show($slug)
    {
        return Post::where('slug', $slug)->first();
    }

    public function store(Request $request)
    {

        $post = $request->all();

        if (strlen($post['body']) >= 300) {
            $exc = substr($post['body'], 0, - (strlen($post['body']) / 2));
            $exc .= '...';
        } else {
            $exc = $post['body'];
        }
        $post['exc'] = $exc;

        Post::create([
            'title' => $post['title'],
            'slug'  => Str::slug($post['title']),
            'exc'   => $post['exc'],
            'body'  => $post['body']
        ]);

        return $post;
    }

    public function update(Request $request, $id)
    {

        $post = Post::find($id);
        $data = $request->all();

        if (strlen($data['body']) >= 300) {
            $exc = substr($data['body'], 0, - (strlen($data['body']) / 2));
            $exc .= '...';
        } else {
            $exc = $data['body'];
        }
        $data['exc'] = $exc;

        $post->update([
            'title' => $data['title'],
            'slug'  => Str::slug($data['title']),
            'exc'   => $data['exc'],
            'body'  => $data['body']
        ]);
        return $post;
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
    }
}
