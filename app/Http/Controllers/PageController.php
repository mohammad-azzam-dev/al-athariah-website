<?php

namespace App\Http\Controllers;

use App\Data\CollegeData;
use App\Data\LibraryData;
use App\Data\MaqraData;
use App\Data\MatunData;
use App\Data\PathData;
use App\Data\RegisterData;
use App\Data\SchoolData;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home');
    }

    public function college(): View
    {
        return view('pages.college', ['data' => CollegeData::get()]);
    }

    public function school(): View
    {
        return view('pages.school', ['data' => SchoolData::get()]);
    }

    public function maqra(): View
    {
        return view('pages.maqra', ['data' => MaqraData::get()]);
    }

    public function matun(): View
    {
        return view('pages.matun', ['data' => MatunData::get()]);
    }

    public function library(): View
    {
        return view('pages.library', ['data' => LibraryData::get()]);
    }

    public function path(): View
    {
        return view('pages.path', ['data' => PathData::get()]);
    }

    public function register(): View
    {
        return view('pages.register', ['data' => RegisterData::get()]);
    }
}
