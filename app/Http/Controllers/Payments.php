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
use App\Models\Payfast;
use App\Services\ClientService;
use App\Models\Companies_temp;
use App\Models\Packages;
//use Storage;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
//use App\Orders;
//use App\ordersProduts;
//use App\OrderCards;
/**
 * Description of Payments
 *
 * @author macuser
 */
class Payments extends Controller {

	/**
     * @var ClientService
     */
    private $clientService;

	public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
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
			return response()->json(["status" => "failed", "err_msg" => "required parameters missing or empty"]);
		}

		// Return the payment form.
		return $payfast->paymentForm();
	}
	// make payment, create form, save order in the databse and redirect to payfast
	public function realCardPayment(Payfast $payfast, Companies_temp $company) {

		//$company_id = !empty($request['company_id']) ? $request->get("company_id") : 0;
		$total_amount = 0;

		$company = $company->load('packages');
		// make payment  /**/
		if (!empty($company->id)) {

			//$company = Companies_temp::where('id', $company_id)->first();
			$total_amount = !empty($company->packages->price) ? $company->packages->price : 0;
			$item = "Order".$company->id;
			// make payment
			$payfast->setAmount($total_amount);
			$payfast->setItem('Order Details',$item);
			$payfast->setMpaymentId($company->id);
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
		$status = 'COMPLETE';
        $this->createnewclient($request->get("m_payment_id"), $status);
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

	// create new account after payment
	private function createnewclient($m_payment_id, $status) {

		//  try {
        switch ($status) {
            case 'COMPLETE': // Things went as planned, update your order status and notify the customer/admins

				// create new account and domain
				$url = $this->clientService->persistClient($m_payment_id);
				echo $url;
                break;
            default: // We've got problems, notify admin to check logs.

                break;
        }
		return $url;
    }

    public function showSuccessfullMessage() {

		alert()->success('SuccessAlert', 'Your subscription was successful. Email confirmatin have been sent to you!!');
		return redirect("/new_client_registration");
    }

    public function showFailedMessage(Request $request) {

		alert()->success('SuccessAlert', 'The subscriptions was not successful, because the transaction was cancelled!!!');
		return redirect("/new_client_registration");
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
	// new tenant creation
	// create new account after payment
	public function createNewTenants($clientID) {

		// create new account and domain
		$url = $this->clientService->persistClient($clientID);

		activity()->log('New Client Registration Successful! please check your email.');
		//return redirect("/make-payment/$clientID");
		return redirect("/new_client_registration");
    }

}
