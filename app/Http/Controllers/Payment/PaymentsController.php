<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PaymentsController extends Controller
{
  //
  public function showPaymentPage(){
      $cart = Session::get('cart');
      $payment_info = Session::get('payment_info');
      // has not paied yet
      if($payment_info['status'] == 'on_hold'){
        return view('payment.payment', ['payment_info'=>$payment_info]);
    }else{
        return redirect()->route('/');
    }
  }

  public function validate_payment($paypalPaymentID, $paypalPayerID){
    $paypalEnv = 'sandbox';
    $paypalURL = 'https://api.sandbox.paypal.com/v1/';
    $paypalClientID = 'AXMXSjy1iVQ4wl6GJOCUPGJJTVWZRO5gmeriNNgrvfcMoczQTaG3_KL7iKqMy8lm1SNCIqZKy0haHRQG';
    $paypalSecret = 'EIOWFp37OrUgpIr5PLlPgJokkdAbmQH1OoL2Z_-j_ZjQ_3Djo-bSQcQCN5uRVm5MGHVmxGb4xLh0qP1C	';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $paypalURL.'oauth2/token');
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $paypalClientID.":".$paypalSecret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    $response = curl_exec($ch);
    curl_close($ch);

    if(empty($response)){
      return false;
    }else{
      $jsonData = json_decode($response);
      dump($jsonData);
      $curl = curl_init($paypalURL.'payments/payment/'.$paypalPaymentID);
      curl_setopt($curl, CURLOPT_POST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '.$jsonData->access_token,
        'Accept: application/json',
        'Content-Type: application/xml'
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      // Transaction data
      $result = json_decode($response);
      return $result;
    }
  }

  private function storePaymentInfo($paypalPaymentID, $paypalPayerID){
    $payment_info = Session::get('payment_info');
    // dump($payment_info);
    $order_id = $payment_info['order_id'];
    $status = $payment_info['status'];
    $amount = $payment_info['price'];
    if($status == 'on_hold'){
      // create a new payment row in payment table
      $date = date('Y-m-d H:i:s');
      $newPaymentArray = array('order_id'=>$order_id, 'date'=>$date, 'amount'=>$amount,
        'paypal_payment_id'=>$paypalPaymentID, 'paypal_payer_id'=>$paypalPayerID
      );
      $created_order = DB::table('payments')->insert($newPaymentArray);
      // update payment status in orders table to paid
      DB::table('orders')->where('order_id', $order_id)->update(['status'=>'paid']);

    }
  }

  public function showPaymentReceipt($paypalPaymentID, $paypalPayerID){
    if(!empty($paypalPaymentID) && !empty($paypalPayerID)){
      // will return json contains transaction status
      // $this->validate_payment($paypalPaymentID, $paypalPayerID);
      $this->storePaymentInfo($paypalPaymentID, $paypalPayerID);
      $payment_receipt = Session::get('payment_info');
      $payment_receipt['paypal_payment_id'] = $paypalPaymentID;
      $payment_receipt['paypal_payer_id'] = $paypalPayerID;
      Session::forget('payment_info');
      return view('payment.receipt', ['payment_receipt'=>$payment_receipt]);

    }else{
      return 'Error occurred';
    }
  }


}
