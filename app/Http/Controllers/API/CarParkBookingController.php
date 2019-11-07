<?php

namespace App\Http\Controllers\API;

use Exception;
use App\CarPark;
use App\CarParkBooking;
use App\CarParkHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CarParkBookingController extends Controller
{
    /**
     * Gets authenticated user's data
     *
     * @return App\User
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Method to schedule a booking
     *
     * @param $id - The car park identifier
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, Request $request)
    {
    	// Get the parking space
		$parking_space = CarPark::find($id);

		// Check if such a parking space exist
		if (!$parking_space) {
			return response()->json(['message' => 'Parking Space Not Found!'], 404);
		}

		// Validate posted request
		$this->validate($request, [
            'check_in'	  => ['required', 'date'],
            'check_out'	  => ['required', 'date'],
            'vehicle_no'  => ['required', 'string'],
            'amount'	  => ['required', 'integer'],
        ]);

        DB::beginTransaction();

        try {
			// Save the record of the booking against the user
        	$booking = new CarParkBooking;

        	$booking->car_park_id 	= $id;
        	$booking->user_id 		= $this->user->id;
        	$booking->check_in 		= $request->check_in;
        	$booking->check_out 	= $request->check_out;
        	$booking->vehicle_no 	= $request->vehicle_no;
        	$booking->amount 		= $parking_space->fee;
        	$booking->status 		= 1;

        	if (!$booking->save()) {
        		throw new Exception;
        	}

	        // Write the booking transaction to history
        	$history = new CarParkHistory;

        	$history->car_park_booking_id	= $booking->id;
        	$history->history_count			= 1;
        	$history->user_id 				= $booking->user_id;
        	$history->vehicle_no 			= $booking->vehicle_no;
        	$history->amount 				= $booking->amount;

        	if(!$history->save()) {
        		throw new Exception;
        	}
        	else {
	            // Transaction was successful
	            DB::commit();

	            // Send response
	            return response()->json([
	                'status'  => true,
	                'message' => 'Car Park has been booked successfully',
	                'result'  => $booking
	            ], 200);
	        }
        } catch(Exception $e) {
            // Transaction was not successful
            DB::rollBack();

            return response()->json([
                'status'  => false,
	            'message' => 'Unable to book parking space',
                'hint'    => $e->getMessage()
            ], 501);
		}
    }

    /**
     * Update a booking
     *
     * @param $id - The car park identifier
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
    	// Verify that such a booking exists
    	$booking = CarParkBooking::find($id);

    	if($booking) {
    		// Ensure that only the right user can update the booking
    		if($this->user->id != $booking->user_id) {
	            return response()->json([
	                'status'  => false,
		            'message' => 'Unauthorized!'
	            ], 501);
    		}
    		else {
				// Validate posted request
				$this->validate($request, [
		            'check_in'	  => ['date'],
		            'check_out'	  => ['date'],
		            'vehicle_no'  => ['string'],
		            'amount'	  => ['integer'],
		            'status'	  => ['integer'],
		        ]);

		        DB::beginTransaction();

		        try {
		        	$booking->check_in 		= $request->check_in ?? $booking->check_in;
		        	$booking->check_out 	= $request->check_out ?? $booking->check_out;
		        	$booking->vehicle_no 	= $request->vehicle_no ?? $booking->vehicle_no;
		        	$booking->amount 		= CarPark::find($booking->car_park_id)->pluck('fee')->first();
		        	$booking->status 		= $request->status ?? $booking->status;

		        	if (!$booking->save()) {
		        		throw new Exception;
		        	}
		        	else {
		        		// Update history record for the transaction
		        		$history = CarParkHistory::whereCarParkBookingId($id)->first();

		        		if ($history) {

				        	$history->vehicle_no 	= $booking->vehicle_no;
				        	$history->amount 		= $booking->amount;

		        			$history->history_count = ++$history->history_count;

				        	if(!$history->save()) {
				        		throw new Exception;
				        	}

				            // transaction was successful
				            DB::commit();

				            // send response
				            return response()->json([
				                'status'  => true,
				                'message' => 'Booking has been successfully updated',
				                'result'  => $booking
				            ], 200);
		        		}
		        		else {
		        			throw new Exception("Inconsistent data found. No history of such a booking exists.", 101);
		        		}
		        	}
		        } catch(Exception $e) {
		            // transaction was not successful
		            DB::rollBack();

		            return response()->json([
		                'status'  => false,
			            'message' => 'Unable to update booking',
		                'hint'    => $e->getMessage()
		            ], 501);
		        }
    		}
    	}

    }
}
