<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class BookingsController extends Controller
{

    /**
     * @var BookingService
     */
    private $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = $this->bookingService->getAllBookings();
        return response($bookings, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $booking = $this->bookingService->persistBookingData($request);
        return response()->json([
            'success' => true,
            'data' => $booking
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try{
            $booking=Booking::find($id);
            if($booking){
                $booking->delete();
                return Response::json(['success'=>'booking removed successfully !'], 200);
            }else{
                return Response::json(['error'=>'booking not found!'], 404);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'booking belongs to an article.So you can\'t delete this category!']);
        }
    }


    /***
     * @param Request $request
     * @return mixed
     *
     */
    public function searchBooking(Request $request) {
        $categories=Booking::where('title','LIKE','%'.$request->keyword.'%')
            ->orWhere('slug','LIKE','%'.$request->keyword.'%')
            ->get();
        if(count($categories)==0){
            return Response::json(['message'=>'No booking match found !']);
        }else{
            return Response::json($categories);
        }
    }
}
