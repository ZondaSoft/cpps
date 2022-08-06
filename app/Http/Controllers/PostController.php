<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Facades\App\Repository\Posts;
use App\Models\Cpps01;  // Professionals
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


    public function getobras(Request $request)
    {
        $posts = Cpps07::select('id', 'cod_os', 'desc_os')->orderBy('desc_os')->get();

        return response()->json($posts);
    }


    public function getProfessionals(Request $request)
    {
        $posts = Cpps01::select('id', 'mat_prov_cole', 'nom_ape')->orderBy('nom_ape')->get();

        return response()->json($posts);
    }
}
