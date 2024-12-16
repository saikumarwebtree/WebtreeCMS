<?php

namespace WebtreeCms\LaravelCms\Core\Admin\Controllers;

use Illuminate\Http\Request;
use Webtree\WebtreeCms\Core\Models\Post;

class PostController extends BaseAdminController
{
    public function index()
    {
        $posts = Post::latest()->paginate(20);
        return view('cms::admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('cms::admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date'
        ]);

        Post::create($validated);
        return redirect()->route('cms.admin.posts.index')
            ->with('success', 'Post created successfully');
    }

    public function edit(Post $post)
    {
        return view('cms::admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date'
        ]);

        $post->update($validated);
        return redirect()->route('cms.admin.posts.index')
            ->with('success', 'Post updated successfully');
    }
}