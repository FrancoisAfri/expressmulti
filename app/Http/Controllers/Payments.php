<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\custom\CustomHelper;
use Illuminate\Support\Facades\DB; 
use App\Payfast;
use App\CustomerPayment;
use Storage;
use App\Orders;
use App\ordersProduts;
use App\OrderCards;
/**
 * Description of Payments
 *
 * @author macuser
 */
class Payments extends Controller {

    //put your code here
	public function initCardPayment(Payfast $payfast, Request $request) {

		if ($request->has("last_4")) {
			$payfast->setAmount(0);
			$payfast->setItem('Initialise Payment Method', 'item-description');
			$payfast->setMpaymentId($request->get('contact_id'));
			$payfast->setPaymentMethod('cc');
			$payfast->setSubscriptionType(2);
			$payfast->setCustomInt1($request->get("last_4"));
		} else {
			return response()->json(["status" => "failed", "err_msg" => "required paramters missing or empty"]);
		}

		// Return the payment form.
		return $payfast->paymentForm();
	}
	// make payment, create form, save order in the databse and redirect to payfast
	public function realCardPayment(Payfast $payfast, Request $request) {
		
		$contact_id = !empty($request['contact_id']) ? $request->get("contact_id") : 0;
		$total_amount = 0;
		// make payment  /**/
		if (!empty($contact_id)) {
			// get last Order number
			$last = Orders::orderBy('id','desc')->first();
			if (!empty($last->order_no)) $orderNo = $last->order_no + 1;
			else $orderNo = 1;
			$item = "Order".$orderNo;
			// save order details
			$order = new Orders();
			$order->order_no = $orderNo;
			$order->status = 1;
			$order->contact_id = $contact_id;
			$order->save();
			// get products from cart
			$products = OrderCards::where('contact_id',$contact_id)->get();

			// save products linked to the order
			foreach ($products as $product) {
				$total_amount = $total_amount + $product->total_price;
				
				if (!empty($product['is_kit']))
				{
					// get products details
					$ordersProduts = new ordersProduts();
					$ordersProduts->order_id = $order->id;
					$ordersProduts->is_kit = 1;
					$ordersProduts->kit_id = $product['kit_id'];
					$ordersProduts->quantity = $product['quantity'];
					$ordersProduts->status = 1;
					$ordersProduts->price = $product['price'];
					$ordersProduts->save();
				}
				else
				{
					// get products details
					$ordersProduts = new ordersProduts();
					$ordersProduts->order_id = $order->id;
					$ordersProduts->product_id = $product['product_id'];
					$ordersProduts->quantity = $product['quantity'];
					$ordersProduts->status = 1;
					$ordersProduts->price = $product['price'];
					$ordersProduts->save();
				}
			}
			// update order table with total amount  total_amount
			$order->total_amount = $total_amount;
			$order->update();
			// make payment
			$payfast->setAmount($total_amount);
			$payfast->setItem('Order Details',$item);
			$payfast->setMpaymentId($order->id);
			$payfast->setPaymentMethod('cc');
			$payfast->setSubscriptionType(2);
			//$payfast->setCustomInt1($request->get("last_4"));
		} 
		else 
		{
			return response()->json(["status" => "failed", "err_msg" => "required paramters(total amount) missing or empty"]);
		}

		// Return the payment form.
		return $payfast->paymentForm();
	}
	
    public function itn(Request $request, Payfast $payfast) {
        // Verify the payment status.
		//get transaction total for verification
		$current = Orders::where('id',$request->get("m_payment_id"))->first();
		//$totalAmount = !empty($current->total_amount) ? $current->total_amount : 0;
        //$status = $payfast->verify($request, $totalAmount)->status();
        //Storage::disk('local')->put('payfast_itn.txt', json_encode([$request->all()]));
        $status = 'COMPLETE';
        $this->addNewCardTokenOrUpdateTransation($request->get("m_payment_id"), $status, $request->get('item_name'));
    }

    private function addNewCardTokenOrUpdateTransation($m_payment_id, $status, $itemName) {
        $order = Orders::find($m_payment_id);
		//  try {
        switch ($status) {
            case 'COMPLETE': // Things went as planned, update your order status and notify the customer/admins
                //if (substr($itemName, 0, 12) == "complete_job" || substr($itemName, 0, 14) == "complete_stage") {	
				$order->payment_status  = 'Payment Successful';
				$order->status  = 2;
				$order->update();
				// empty cart 
				$cards = OrderCards::where('contact_id',$order->contact_id)->get();
				foreach ($cards as $card) 
				{
					$line = OrderCards::find($card->id);
					$deleterow = $line->delete();
				}
                //}
                break;
            default: // We've got problems, notify admin to check logs.
					$order->payment_status  = 'Payment Failed';
					$order->update();
                break;
        }
		// get all user order
		$orders = Orders::getorders($order->contact_id);
		$ordersKits = Orders::getOrderstKit($order->contact_id);
		return response()->json([
				'success'=> true,
				'mssessage'=> "success",
				'orders'=> $orders,
				'ordersKits'=> $ordersKits,
			]);
    }

    public function showSuccessfullMessage() {
		return response()->json([
				'success'=> true,
				'message'=> 'Payment was Successful.',
			]);
    }

    public function showFailedMessage(Request $request) {
        return response()->json([
				'success'=> false,
				'message'=> 'Payment was cancelled.',
			]);
    }

    function getCustomerPaymentMethod(Request $request) {
        try {
            $payments = DB::select("SELECT id, payment_type, card_last_digits FROM customer_payment_method WHERE customer_id = ? AND status = ?", [$request->get("user_id"), "Active"]);

            return response()->json(["status" => "success", "payment_methods" => $payments]);
        } catch (\Illuminate\Database\QueryException $exc) {
            return response()->json(["status" => "failed", "err_msg" => $exc->errorInfo[2]]);
        }
    }

    function deltePaymentMethod(Request $request) {
        if ($request->has('payment_id')) {
            try {
                $res = DB::select("SELECT card_tok FROM customer_payment_method WHERE id = ? and customer_id = ?", [$request->get("payment_id"), $request->get("user_id")]);

                /*  $pf_res = $this->deletePaymentFromPayfast($res[0]->card_tok);
                  $x=$pf_res;
                  $pf_res = $pf_res != NULL ? json_decode($pf_res) : NULL;
                  if ($pf_res != NULL && $pf_res->code == 200) { */
                $delete = DB::update("UPDATE customer_payment_method SET status = ? WHERE id = ?", ["Deleted", $request->get("payment_id")]);
                return response()->json(["status" => "success"]);
                /*  } else {
                  return response()->json(["status" => "failed", "err_msg" => $x]);
                  } */
                //also delte in payfast
            } catch (\Illuminate\Database\QueryException $exc) {
                return response()->json(["status" => "failed", "err_msg" => $exc->errorInfo[2]]);
            }
        } else {
            return response()->json(["status" => "failed", "err_msg" => "required paramters missing or empty"]);
        }
    }

    function deletePaymentFromPayfast($token) {

        $ch = "";
        $pfParamString = "";
        $timeStamp = date('Y-m-d\TH:i:s');
        $merchantId = config('payfast.merchant');


        $pfData = ['version' => 'v1', 'merchant-id' => $merchantId['merchant_id'], 'timestamp' => $timeStamp];

        $data = [];
        // Construct variables 
        foreach ($pfData as $key => $val) {
            $data[$key] = stripslashes($val);
        }

        $passPhrase = 'payfasT_2018';

        $pfData['passphrase'] = $passPhrase;


// Sort the array by key, alphabetically
        ksort($pfData);

// Normalise the array into a parameter string
        foreach ($pfData as $key => $val) {
            if ($key != 'signature') {
                $pfParamString .= $key . '=' . urlencode(trim($val)) . '&';
            }
        }

// Remove the last '&amp;' from the parameter string
        $pfParamString = substr($pfParamString, 0, -1);
        $signature = md5($pfParamString);


        $ch = curl_init("https://api.payfast.co.za/subscriptions/" . $token . "/cancel");

        // var_dump(['version: v1', 'merchant-id: ' . $merchantId['merchant_id'], 'signature: ' . $signature , 'timestamp: ' . $timeStamp]);die;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
// For the body values such as amount, item_name, & item_description
        // curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['version: v1', 'merchant-id: ' . $merchantId['merchant_id'], 'signature: ' . $signature, 'timestamp: ' . $timeStamp]);

// Execute and close cURL
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
	
}
