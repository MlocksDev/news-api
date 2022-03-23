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

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
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
        $news = new News();

        return response()->json(['news' => $news->with(['author'])->get()]);
    }

    public function listByAuthor($author_id)
    {
        $newsByAuthor = News::where('author_id', $author_id)->get();

        return response()->json(['news' => $newsByAuthor]);
    }

    public function get($id)
    {
        try {

            $news = News::findOrFail($id);

            return response()->json(['news' => $news], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'News not found!'], 404);
        }
    }

    public function create(Request $request)
    {
        $this->validate_request($request);

        $news = News::create($request->all());

        return response()->json(['news' => $news, 'message' => 'CREATED'], 201);
    }

    public function put($id, Request $request)
    {

        $this->validate_request($request);

        try {

            $news = News::findOrFail($id);
            $news->update($request->all());

            return response()->json(['news' => $news, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'News not found!'], 404);
        }
    }

    public function delete($id)
    {

        try {

            $news = News::findOrFail($id);
            $news->delete();

            return response(['message' => 'DELETED'], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'News not found!'], 404);
        }
    }

    private function validate_request(Request $request)
    {

        $this->validate(
            $request,
            [
                'author_id' => 'required|integer',
                'title' => 'required|string',
                'subtitle' => 'required|string',
                'description' => 'required|string',
                'slug' => 'required|string',
                'active' => 'required|boolean',
            ]
        );
    }
}
