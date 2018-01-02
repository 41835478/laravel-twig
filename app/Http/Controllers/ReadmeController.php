<?php
namespace App\Http\Controllers;

use \Michelf\MarkdownExtra;

class ReadmeController extends Controller
{
    public function index() 
    {
        $text = file_get_contents(base_path() . '/api.md');
        $html = MarkdownExtra::defaultTransform($text);
        return view('markdown')->with('html', $html);
    }


    function buckydrop(){
        $text = file_get_contents(base_path() . '/buckydrop.md');
        $html = MarkdownExtra::defaultTransform($text);
        return view('markdown')->with('html', $html);
    }
}
