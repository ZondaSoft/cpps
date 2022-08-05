<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Facades\App\Repository\Posts;
use App\Models\Cpps07;  // Obras sociales

class PostController extends Controller
{
    public function index()
    {   
        return view('search');
    }


    public function search(Request $request)
    {
        $posts = Cpps07::where('cod_os',$request->keywords)->get();
        
        return response()->json($posts);
    }

    public function get(Request $request)
    {
        $posts = Cpps07::get();

        return response()->json($posts);
    }
}
