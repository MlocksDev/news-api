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

use App\Models\Authors;
use Illuminate\Http\Request;

class AuthorsController extends Controller
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
        return response()->json(['authors' => Authors::all()]);
    }

    public function listByUser($user_id)
    {
        $authorsByUser = Authors::where('user_id', $user_id)->get();

        return response()->json(['authors' => $authorsByUser]);
    }

    public function get($id)
    {
        try {

            $author = Authors::findOrFail($id);

            return response()->json(['author' => $author], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Author not found!'], 404);
        }
    }

    public function create(Request $request)
    {
        $this->validate_request($request);

        $author = Authors::create($request->all());

        return response()->json(['author' => $author, 'message' => 'CREATED'], 201);
    }

    public function put($id, Request $request)
    {

        $this->validate_request($request);

        try {

            $author = Authors::findOrFail($id);
            $author->update($request->all());

            return response()->json(['author' => $author, 'message' => 'UPDATED'], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Author not found!'], 404);
        }
    }

    public function delete($id)
    {

        try {

            $author = Authors::findOrFail($id);
            $author->delete();

            return response(['message' => 'DELETED'], 200);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Author not found!'], 404);
        }
    }

    private function validate_request(Request $request)
    {

        $this->validate(
            $request,
            [
                'users_id' => 'required|integer',
                'name' => 'required|string',
                'lastname' => 'required|string',
                'gender' => 'required|string',
                'active' => 'required|boolean',
            ]
        );
    }
}
