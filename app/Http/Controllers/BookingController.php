<?php

namespace App\Http\Controllers;

use Exception;
use Midtrans\Snap;
use  Midtrans\Config;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    public function index(Request $request)
    {


        $tutorings = Auth::user()->tutorings()->select('id')->get();

        $tutorTutoringsId = $tutorings->toArray();


        $bookingsQuery = Booking::with(['user:id,name,phone_number', 'tutoring.subject'])->whereIn('tutoring_id', $tutorTutoringsId);
        $bookings = $bookingsQuery->get();

        foreach ($bookings as $booking) {
            if ($booking->status == "ACCEPTED" && Carbon::now()->greaterThan($booking->tutoring->end_time)) {
                $booking->status = 'FINISHED';
                $booking->save();
            }
        }

        // dd($bookingsQuery);
        if ($request->ajax()) {
            return DataTables::of($bookingsQuery)
                ->addColumn('payment', function ($booking) {
                    return "<a class='btn btn-primary btn-sm' href='{$booking->payment_url}' target='_blank'> <i class='fas fa-file-invoice'></i>{$booking->payment}</a>";
                })
                ->editColumn('status', function ($booking) {
                    // if (Carbon::now()->greaterThan($booking->tutoring->end_time)) {
                    //     return "FINISHED";
                    // }
                    return $booking->status;
                })
                ->addColumn('action', function ($booking) {
                    // ACTION =
                    // 1. 'CONFIRM'|status REQUESTED TO ACCEPTED | (SEND THE MEETING LINK)
                    // 2. 'REJECT'| status REQUESTED TO DECLINED |
                    // 3. AUTOMATICALLY SHOW THE STATUS from ACCEPTED TO FINISHED WHEN the date is over
                    // 4. UPLOAD ACTIVITY | statUS FROM FINISHED can upload  with photos
                    // 5. AUTOMATICLY SHOW THET STATUS IGNORED if it's not responded

                    $btnTemplate = "
                    <form method='POST' action=%s>
                    " . csrf_field() . "
                    <button class='btn btn-%s m-1 btn-sm'>%s</button>
                    </form>";

                    $buttons = "";

                    $route = route('booked-session.action', $booking->id);

                    if ($booking->status == "REQUESTED") {
                        $buttons .= sprintf($btnTemplate, $route . '?action=confirm', 'primary',  'CONFIRM');
                        $buttons .= sprintf($btnTemplate, $route . '?action=reject', 'danger',  'REJECT');
                    } else if ($booking->status == "FINISHED") {
                        $buttons .= "<button class='btn btn-primary' data-activity-description='{$booking->activity_description}'  data-toggle='modal' data-target='#uploadActivityModal' data-id='{$booking->id}' target='_blank'><i class='fas fa-upload'></i></button>";

                        // $buttons .= sprintf($btnTemplate, $route . '?action=upload_activity', 'info',  'UPLOAD ACTIVITY');
                    } else if ($booking->status == "ACCEPTED") {
                        // if (Carbon::now()->greaterThan($booking->tutoring->end_time)) {
                        //     $buttons .= sprintf($btnTemplate, $route . '?action=upload_activity', 'info',  'UPLOAD ACTIVITY');
                        // }

                        if ($booking->meeting_link) {
                            $buttons .= "<button class='btn btn-primary' data-meeting-link='{$booking->meeting_link}'  data-toggle='modal' data-target='#addMeetingLinkModal' data-id='{$booking->id}' target='_blank'><i class='fas fa-link'></i></button>";
                        } else {
                            $buttons .= "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#addMeetingLinkModal' data-id='{$booking->id}'>
                            Add link
                          </button>";
                        }
                    }
                    return $buttons;
                })

                ->editColumn('id', function ($q) {
                    return '#' . $q->id;
                })
                ->rawColumns(['payment', 'action'])
                ->toJson();
        }

        return view('booking.index');
    }

    public function action(Request $request, Booking $booking)
    {

        switch ($request->action) {
            case 'confirm':
                $booking->status = 'ACCEPTED';
                break;
            case 'reject':
                $booking->status = 'DECLINED';
                break;
            case 'upload_activity':
                // upload photos handled by dropzone controller
                $booking->activity_description = $request->activity_description;
                break;
        }
        $booking->save();
        if ($request->ajax()) {
            return '/home/tutor/booked-sessions';
        }
        return redirect()->route('booked-session.index')->with('message', 'The booked session has been updated');
    }

    public function createMeetingLink(Request $request, Booking $booking)
    {
        $request->validate([
            'meeting_link' => 'url'
        ]);
        if ($request->meeting_link) {
            $booking->meeting_link = $request->meeting_link;
            $booking->save();
            Session::flash('message', 'meeting link has been saved');
        }
        return '/home/tutor/booked-sessions';
    }


    public function bookWithPaymentGateway(Request $request)
    {

        //tutoring_id
        // user_id
        // payment_url
        // status = REQUESTED

        // $request->validate(
        //     [
        //         'tutoring_id' => 'required',
        //         'user_id' => 'required',
        //         'payment_url' => 'required',
        //     ]
        // );


        //create booking

        // $booking = Booking::create([
        //     'tutoring_id' => $request->tutoring_id,
        //     'total_price' => $request->total_price,
        //     'status' => 'REQUESTED',
        //     'user_id' => $request->user_id,
        // ]);

        //config midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // setup variable midtrans
        $midtrans = [
            'transaction_details' => [
                'order_id' => 'TR-' . '22', // LUX-100
                'gross_amount' => (int) 223
            ],
            'customer_details' => [
                'first_name' => 'ilham',
                'email' => 'ilham.mmr@gmail.com',
            ],
            'enabled_payments' => ['gopay', 'bank_transfer'],
            'vtweb' => []
        ];

        // payment process
        try {
            // Get Snap Payment Page URL
            $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;
            //  $transaction->payment_url = $paymentUrl;
            //  $transaction->save();
            return redirect($paymentUrl);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


}
