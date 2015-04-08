<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;

/**
 * Description of PayPalController
 *
 * @author Markó Mihály
 */
class PayPalController extends \BaseController {

    private $_api_context;

    function __construct() {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function postPayment() {
        //dd(Input::all());
        //fizetési app kiválasztása
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        
        //termékek feltöltése
        $counter = 0;
        $items = [];
       
        $tax = 0.25;
        $totalTax = 0.00;
        $total = 0;
        
        foreach (Input::all() as $input)
        {
            $subTotal = 0;
            $price = 0;
            if(Input::has('item_name'.$counter))
            {   
                 $qtt = Input::get('item_qtt'.$counter);
                 $price = Input::get('item_price'.$counter);
                 $subTotal = $qtt * $price;
                         
                 $item = new Item();
                 $item->setName(Input::get('item_name'.$counter)) // item name
                        ->setCurrency('USD')
                        ->setQuantity($qtt)
                        ->setPrice($price); // unit price
                        
                 $items[] = $item;
            }
            $total += $subTotal;
            $counter++;
            $totalTax = $totalTax + ((($price*$tax)) * $qtt);
        }
//        var_dump($total);
//        var_dump($totalTax);
//        exit;
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems($items);
        
        $details = new Details();
        //össze kell adódnia
        $details->setTax($totalTax)  //áfa értékek összege
                ->setSubtotal($total); //áfa nélküli összeg
        
        //áfaösszegek + teljes összeg
        $totalFine  = $totalTax + $total;
        
        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal($totalFine)  //végösszeg áfával
                ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Te bevásárló kosarad');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('payment.status')) // PayPalról vissza érkező url
                ->setCancelUrl(URL::route('payment.status'));     //Ha kilépbnénk a paypal oldalon majd ide irányíts URL

        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));

        try {
              //fizetési objektum létrehozása az apinak
              $payment->create($this->_api_context);
            } catch (PayPalConnectionException $ex) { // TO DO urlt meg kéne adni
                if (Config::get('app.debug')) {     //ha a config mappában, az app file, debug változó értéke true (amúgy most az)
                    echo "Exception: " . $ex->getMessage() . PHP_EOL;
                    $err_data = json_decode($ex->getData(), true);
                    var_dump($err_data);
                    exit;
                } else {
                    die('Some error occur, sorry for inconvenient');
                }
        }
        
        //ha sikerült a létrehozás megyünk tovább ...
        
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();  //összeállított url
                break;
            }
        }

        // sessionbe letároljuk a fizetési azonosítót
        Session::put('paypal_payment_id', $payment->getId());
       
        //tranzakció létrehozása db-ben
        $newTransaction = new TransactionsPaypal();
        
        $newTransaction->user_id = Auth::user()->id;
        $newTransaction->payment_id = $payment->getId();
        $newTransaction->save();
        
        if (isset($redirect_url)) {
            // redirect to paypal
            return Redirect::away($redirect_url);
        }
        
        //egyébként redirekt az index oldalra ismeretlen hibával
        return Redirect::route('menuitems.index')
                        ->with('error', 'Unknown error occurred');
    }

    
    public function getPaymentStatus() 
    {
        // Get the payment ID before session clear
        $payment_id = Session::get('paypal_payment_id');

        // clear the session payment ID
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            //ha a paypal-on a kilépes linkfe kattintunk
            return Redirect::route('menuitems.index')
                            ->with('error', 'Fizetésből kilépve');
        }
        
        //tranzakció adatait kérem itt le
        $payment = Payment::get($payment_id, $this->_api_context);

        // PaymentExecution object includes information necessary 
        // to execute a PayPal account payment. 
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site

        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);
        
        //itt lehet kiszedni a kapott adatokat paypaltol
//        echo '<pre>';
//        print_r($result);
//        echo '</pre>';
//        exit; // DEBUG RESULT, remove it later

        if ($result->getState() == 'approved') { // sikeres tranzakció
        
            $succTransaction = TransactionsPaypal::where('payment_id','=',$payment_id)->first();
           
            $succTransaction->complate = 1;
            $succTransaction->update();
            
            return Redirect::route('menuitems.index')
                            ->with('success', 'Payment success');
        }
            // ha valami beszart utaláskor
        return Redirect::route('menuitems.index')
                        ->with('error', 'Payment failed');
    }
}
