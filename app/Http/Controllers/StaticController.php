<?php

namespace App\Http\Controllers;

class StaticController extends Controller
{
    public function about()        { return view('pages.about'); }
    public function contact()      { return view('pages.contact'); }
    public function faq()          { return view('pages.faq'); }
    public function safetyTips()   { return view('pages.safety'); }
    public function privacy()      { return view('pages.privacy'); }
    public function terms()        { return view('pages.terms'); }
    public function billing()      { return view('pages.billing'); }
    public function notifications() { return view('pages.notifications'); }
}
