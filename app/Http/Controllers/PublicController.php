<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Show the posts page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('public/index');
    }

    /**
     * Registration page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function register()
    {
        return view('public/register');
    }

    /**
     * Show the single post
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function post()
    {
        return view('public/post');
    }

    /**
     * Create a post page
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('public/create');
    }

}
