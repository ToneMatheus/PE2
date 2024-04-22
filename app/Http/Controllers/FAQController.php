<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class FAQController extends Controller
{
    public function showFAQ(): View
    {
        $title = 'FAQ';
        return view('Support_Pages/faq', ['title' => $title]);
    }
}
