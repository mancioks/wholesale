<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListViewController extends Controller
{
    public function set($type)
    {
        switch ($type) {
            case 'list':
                session(['list_view' => 'col-lg-12']);
                break;

            case 'grid':
                session(['list_view' => 'col-lg-6']);
                break;

            default:
                return abort(404);
        }

        return redirect()->back();
    }
}
