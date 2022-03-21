<?php

namespace App\Http\Controllers\Api;

use App\Helper\Formatter;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::latest('id')->get();
        $comments = Formatter::formatter($comments, 'comments');
        return $comments;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        $data = $request->validate([
            'content' => 'required',
        ]);
        // get user id
        $data['user_id'] = $request->user()->id;

        // get post by post id param
        $post = app('db')
            ->table('posts')
            ->where('id','=',$post_id)
            ->first();
        // check post id and post object
        if (is_null($post)) {
            return response()->json(['status' => false]);
        }
        $data['post_id'] = $post_id;
        // insert comments for user and post
        $comment = DB::table('comments')
            ->insertGetId($data);
        $comment = Formatter::formatter($comment, 'Comments');
        return $comment;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::with(['post', 'user'])->find($id);
        $comment = Formatter::formatter($comment, 'comment details');

        return response()->json($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'content' => 'required'
        ]);

        $user_id = $request->user()->id;
        $comment = DB::table('comments as c')
            ->join('posts as p','p.id','=','c.post_id')
            ->where('c.user_id','=',$user_id)
            ->where('c.id','=',$id)
            ->first();
        if (is_null($comment)) {
            return response()->json(['status'=>false]);
        }

        $comment = DB::table('comments')
            ->where('id','=',$id)
            ->update($data);
        $comment = Formatter::formatter($comment,'comments');
        return $comment;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
