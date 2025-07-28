<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsumerApplicationController extends Controller
{
    public function index(Request $request)
    {
        return view('consumer.application_list');
    }
    public function create(Request $request)
    {
        return view('consumer.application_create');
    }
    public function MydocumentsList(Request $request)
    {
        return view('consumer.documents_list');
    }
}
