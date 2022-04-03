<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Mail\SendNewMail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('updated_at', 'DESC')->paginate(10);


        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Post();
        $tags = Tag::all();
        $categories = Category::all();
        return view('admin.posts.create', compact('post', 'categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /* dd($request->all()); */
        $request->validate([
            'title' => 'required|string|unique:posts|min:5|max:50',
            'content' => 'required|string',
            'image' => 'nullable|image',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id'
        ], [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.min' => 'La lunghezza minima del titolo è di 5 caratteri.',
            'title.max' => 'La lunghezza massima del titolo è di 50 caratteri.',
            'title.unique' => "Esiste già un post: $request->title.",
            'tags.exists' => 'Un tag appena selezionato non è valido.'
        ]);

        $data = $request->all();
        $post = new Post();

        if (array_key_exists('image', $data)) {
            $url_img = Storage::put('post_images', $data['image']);
            $data['image'] = $url_img;
        }

        $post->fill($data);
        $post->slug = Str::slug($post->title, '-');
        if (array_key_exists('is_published', $data)) {
            $post->is_published = 1;
        }

        $post->save();


        if (array_key_exists('tags', $data)) $post->tags()->attach($data['tags']);

        $mail = new SendNewMail();
        Mail::to('nicolas.maranzano@libero.it')->send($mail);
        return redirect()->route('admin.posts.index')->with('message', 'Post creato con successo')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::all();
        $post_tags_ids = $post->tags->pluck('id')->toArray();
        $categories = Category::all();

        return view('admin.posts.edit', compact('tags', 'post', 'categories', 'post_tags_ids'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => ['required', 'string', Rule::unique('posts')->ignore($post->id), 'min:5', 'max:50'],
            'content' => 'required|string',
            'image' => 'nullable|image',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|exists:tags,id'
        ], [
            'title.required' => 'Il titolo è obbligatorio.',
            'title.min' => 'La lunghezza minima del titolo è di 5 caratteri.',
            'title.max' => 'La lunghezza massima del titolo è di 50 caratteri.',
            'title.unique' => "Esiste già un post: $request->title.",
            'tags.exists' => 'Un tag appena selezionato non è valido.'
        ]);



        $data = $request->all();

        $data['is_published'] = array_key_exists('is_published', $data) ? 1 : 0;

        $data['slug'] = Str::slug($request->title, '-');
        $post->update($data);

        if (!array_key_exists('tags', $data)) $post->tags()->detach();
        else $post->tags()->sync($data['tags']);

        return redirect()->route('admin.posts.show', $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (count($post->tags)) $post->tags()->detach();
        if ($post->image) Storage::delete($post->image);
        $post->delete();
        return redirect()->route('admin.posts.index')->with('message', "$post->title eliminato con successo.")->with('type', 'success');
    }

    public function toggle(Post $post)
    {
        $post->is_published = !$post->is_published;
        $published = $post->is_published ? 'pubblicato' : 'rimosso';

        $post->save();

        return redirect()->route('admin.posts.index')->with('message', "$post->title $published con successo.")->with('type', 'success');
    }
}
