<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Traits\SlugTrait;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Traits\TranslationTrait;
use App\Http\Requests\PostRequest;
//use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    use ApiResponser;
    use SlugTrait;
    use TranslationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  DataTables::of(Post::query()->orderBy('created_at', 'DESC'))
            ->addColumn('user', function ($post) {
                return $post->user->name ?? 'None';
            })
            ->addColumn('title', function ($title) {
                return $title->title_ar ?? 'None';
            })
            ->editColumn('id', '{{$id}}')
            ->rawColumns(['created_at'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $validated = $request->validated();
        //dd($validated);

        $fileName = null;
        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/posts/', $fileName);
        }

        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'user_id' => Auth::user()->id,
            'image' => $fileName,
        ]);
        $post['user_id'] = Auth::user()->id ?? "";
        $validated['slug'] = $this->createSlug('Post', $post->id, $post->title, 'title_post');
        $post->save();

        $this->translate($request, 'Post', $post->id);
        return $this->success(['post' => $post], trans('main.post_create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->error(__('main.not_found'), 404);
        }
        return $this->success(['Post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        return $this->success(['Post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, $id)
    {

        $input = $request->all();

        $post = Post::find($id);
        if (!$post) {
            return $this->error(__('main.not_found'), 404);
        }
        $this->editSlug('Post', $post->id, $post->title, 'posts');

        $fileName = null;
        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('./uploads/posts/', $fileName);
        }
        $post->update($input);
        //$post->save();
        $this->editTranslation($request, 'Post', $post->id);

        return $this->success(['post' => $post], __('main.post_update_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->error(__('main.not_found'), 404);
        }
        if ($post->image) {
            File::delete(public_path() . "/uploads/posts/" . $post->image);
        }
        $post->delete();
        return $this->success('', trans('main.category_delete_success'));
    }
}
