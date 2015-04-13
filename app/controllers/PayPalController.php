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
               // if (Config::get('app.debug')) {     //ha a config mappában, az app file, debug változó értéke true (amúgy most az)
                    $error_white_list =[
                    'CAPTURE_AMOUNT_LIMIT_EXCEEDED', // összeg meghaladta a megengedett határértéket
                    'CREDIT_CARD_REFUSED',      //A használt hitelkártyát fizetési elutasították. Küldje el újra a kérést egy másik hitelkártyát.
                    'DATA_RETRIEVAL',          //Probléma volt, hogy az adatok. Küldje el újra a kérést. Ha a hiba továbbra is fennáll, lépjen kapcsolatba 
                    'EXPIRED_CREDIT_CARD',    //lejárt a kártya
                    'FEATURE_UNSUPPORTED_FOR_PAYEE', //Funkció nem támogatott
                    'INSUFFICIENT_FUNDS',     //Vevő nem tud fizetni - fedezethiány.
                    'INTERNAL_SERVICE_ERROR', //Egy belső szolgáltatási hiba történt
                    'INVALID_ACCOUNT_NUMBER', //számlaszám nem létezik
                    'INVALID_ARGUMENT',       //Érvénytelen argumentum került elfogadásra a kérést.
                    'INVALID_CITY_STATE_ZIP', //Érvénytelen állami városi zip kombináció
                    'INVALID_EXPERIENCE_PROFILE_ID',
                    'PAYEE_ACCOUNT_LOCKED_OR_CLOSED', //Kedvezményezett számlája zárolva van, vagy zárva
                    'PAYEE_ACCOUNT_NO_CONFIRMED_EMAIL', //kedvezményezett számlája nem rendelkezik visszaigazolt e-mail
                    'PAYEE_ACCOUNT_RESTRICTED',  //A számla megkapta ezt a fizetési korlátozott, és nem kapják meg a kifizetéseket ebben az időben.
                    'PAYER_ACCOUNT_LOCKED_OR_CLOSED', //Payer le van zárva, vagy zárt.
                    'PAYER_ACCOUNT_RESTRICTED', //fizető fél számlája korlátozott
                    'PAYER_CANNOT_PAY',         //Payer nem tud fizetni, ez a tranzakció Paypal.
                    'PAYER_EMPTY_BILLING_ADDRESS', //Számlázási cím üres
                    'PAYMENT_APPROVAL_EXPIRED',  //Fizetés jóváhagyása már lejárt
                    'PAYMENT_EXPIRED',           //A fizetési lejárt, mert túl sok idő telt el a fizetés létrehozása vagy jóváhagyása és végrehajtása, hogy fizetés. Indítsa újra a kifizetési kérelem kezdve fizetési létrehozását.
                    'PAYMENT_REQUEST_ID_INVALID', //Paypal kérésére azonosítója érvénytelen. Kérjük, próbálj ki egy másikat.
                    'PAYMENT_STATE_INVALID',  //A fizetési állam nem teszi lehetővé ezt a fajta kérést.
                    'PERMISSION_DENIED', //Ön nem rendelkezik a megfelelő engedélyekkel a kérés teljesítéséhez.
                    'REFUND_EXCEEDED_TRANSACTION_AMOUNT', //Visszatérítést megtagadta - a kért visszatérítés összegét meghaladja a tranzakció összegének megtérítését
                    'REFUND_TIME_LIMIT_EXCEEDED', //Ez a tranzakció túl öreg
                    'TOO_MANY_REAUTHORIZATIONS', //Egyszerre csak egyszer Engedélyezze újból a fizetésit.
                    'TRANSACTION_ALREADY_REFUNDED',
                    'TRANSACTION_LIMIT_EXCEEDED',  //Teljes kifizetés összege meghaladta a tranzakciós limit
                    'TRANSACTION_REFUSED', //Ezt a kérést elutasították.
                    'TRANSACTION_REFUSED_BY_PAYPAL_RISK', //Ez a tranzakció visszautasításra került a PayPal kockázatát.
                    'TRANSACTION_REFUSED_PAYEE_PREFERENCE', //A kereskedelmi számla beállítások úgy vannak beállítva, hogy megtagadja az adott fajta tranzakciót.
                    'ORDER_ALREADY_COMPLETED', //Megrendelés már elévül, lejárt vagy befejezett
                    'ORDER_VOIDED', //Megrendelni semmissé vált
                    'WALLET_TOO_MANY_ATTEMPTS', //Elérte a maximális fizetési kísérlet erre a token.
                    'ACCOUNT_RESTRICTED', //Tranzakciót nem lehet feldolgozni. Kérjük, lépjen kapcsolatba PayPal Customer Service.
                    'INVALID_CC_NUMBER', //Adjon meg egy érvényes hitelkártya számát és típusát
                    'MISSING_CVV2', //Kérjük, adja CVV2 hitel-kártyát.
                    'CALL_FAILED_PAYMENT', //A fizetés elmulasztása.
                    'GATEWAY_DECLINE_CVV2', //Kérjük, érvényes hitelkártyát.
                    'NEGATIVE_BALANCE', //negatív egyenleg
                    'RECEIVER_ACCOUNT_LOCKED', //Vevő számláján zárolt vagy inaktív
                    'VALIDATION_ERROR' // TESZTHEZ
                    ];
                    
                    $err_data = json_decode($ex->getData(), true);
                    $errors = [];
                    
                    foreach ($error_white_list as $error)
                    { 
                       if($err_data['name'] == $error){
                           foreach ($err_data['details'] as $e)
                            {
                                $errors[] = $e['issue'];
                            }  
                       }
                        
                    }
                    
                    return Redirect::back()
                        ->withErrors($errors);
//                } else {
//                    die('Some error occur, sorry for inconvenient');
//                }
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
        $t = Transaction::getList($result->getTransactions());
        //itt lehet kiszedni a kapott adatokat paypaltol
        echo '<pre>';
        print_r($t);
        echo '</pre>';
        exit; // DEBUG RESULT, remove it later

        if ($result->getState() == 'approved') { // sikeres tranzakció
        
            $succTransaction = TransactionsPaypal::where('payment_id','=',$payment_id)->first();
           
            $succTransaction->complate = 1;
            $succTransaction->update();
            
             //email küldés
            $data = [
                'name'=>'Fis'
            ];
            //view,data,callback
            Mail::send('emails.paypal.buy', $data, function($message)
            {
                $message->to('fis_misi@hotmail.com','Teszttt')
                        ->subject('Sikeres befizetés ma');
            });
            Cart::destroy();
            return Redirect::route('menuitems.index')
                            ->with('success', 'Payment success');
        }
            // ha valami beszart utaláskor
        return Redirect::route('menuitems.index')
                        ->with('error', 'Payment failed');
    }
}
