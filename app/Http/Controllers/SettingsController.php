<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::query()->where('edit', true)->get();
        return view('settings', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request)
    {
        foreach (array_combine($request->post('name'), $request->post('value')) as $key => $value) {
            Setting::query()->where('name', $key)->update([
                'value' => $value,
            ]);
        }

        return redirect()->back()->with('status', 'Updated');
    }
}
