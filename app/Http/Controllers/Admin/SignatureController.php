<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignRequest;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class SignatureController extends Controller
{
    public function sign(SignRequest $request, Order $order)
    {
        $image = Image::make($request->get('signature'))->trim('transparent')->encode('png');
        $path = sprintf('signatures/order-%d-signature-%d.png', $order->id, time());
        $name = Setting::get('signatures.path').$path;

        Storage::disk('uploads')->put($path, $image->stream());

        $order->update([
            'signature' => $name,
        ]);

        return redirect()->back()->with('status', 'Order signed');
    }
}
