<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class DropzoneController extends Controller {
    public function upload(Request $request) {

        // TODO: validate the number of images
        $images = $request->images;
        $booking = Booking::where('id', $request->bookingId)->firstOrFail();
        $storedImages = [];

        // info($images);

        foreach ($images as $image) {
            $path = $image->store('images/activities', 'public');
            $storedImage = ['url' => $path];
            $storedImages[] = $storedImage;
        }


        $booking->image()->createMany($storedImages);

        return response()->json(['paths' => $storedImages]);
    }

    public function fetch(Request $request) {
        $booking = Booking::where('id', $request->bookingId)->firstOrFail();
        $images = $booking->image()->get();
        $div = '<div class="row justify-content-center"> %s </div>';
        $content = '';
        foreach ($images as $image) {
            $url = asset('storage/' . $image->url);
            $content .= "
            <div class='col-md-2 m-2 text-center'>
                <img src='{$url}' class='rounded img-thumbnail' style='height: 100px;width: 100px;' />
                <button type='button' class='btn btn-link remove-image'  id='{$image->id}'>
                <i class='fas fa-trash-alt text-danger'></i>
                </button>
            </div>
            ";
        }

        return response()->json(['data' => count($images) > 0 ? sprintf($div, $content) : 0]);
    }

    public function delete(Request $request) {
        $booking = Booking::where('id', $request->bookingId)->firstOrFail();
        $booking->image()->where('id', $request->imageId)->delete();
        return response()->json(['status' => 'success']);
    }
}
