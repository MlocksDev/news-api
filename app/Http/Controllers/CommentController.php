<?php
/*
 * The MIT License
 *
 * Copyright 2022 Martha Ribeiro Locks.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        return response()->json(['comment' => Comment::all()]);
    }

    public function listByUser($user_id)
    {
        $commentByUser = Comment::where('user_id', $user_id)->get();

        return response()->json(['comment' => $commentByUser]);
    }

    public function get($id)
    {
        try {

            $comment = Comment::findOrFail($id);

            return response()->json(['comment' => $comment], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Comment not found!'], 404);
        }
    }

    public function create(Request $request)
    {
        $this->validate_request($request);

        $comment = Comment::create($request->all());

        return response()->json(['comment' => $comment, 'message' => 'CREATED'], 201);
    }

    public function put($id, Request $request)
    {

        $this->validate_request($request);

        try {

            $comment = Comment::findOrFail($id);
            $comment->update($request->all());

            return response()->json(['comment' => $comment, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Comment not found!'], 404);
        }
    }

    public function delete($id)
    {

        try {

            $comment = Comment::findOrFail($id);
            $comment->delete();

            return response(['message' => 'DELETED'], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Comment not found!'], 404);
        }
    }

    private function validate_request(Request $request)
    {

        $this->validate(
            $request,
            [
                'news_id' => 'required',
                'title' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|boolean'
            ]
        );
    }
}
