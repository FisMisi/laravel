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
        
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName(Input::get('item_name')) // item name
                ->setCurrency('USD')
                #->setTax(0.20)
                ->setQuantity(5)
                ->setPrice(Input::get('item_price')); // unit price

        $item_2 = new Item();
        $item_2->setName('véres húrka') // item name
                ->setCurrency('USD')
                #->setTax(0.15)
                ->setQuantity(10)
                ->setPrice(100); // unit price
        
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1, $item_2));
        
        $details = new Details();
        $details->setSubtotal(2000);
        $details->setTax(350);
                
        
        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal(2350)
                ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
                    ->setItemList($item_list)
 //               ->setInvoiceNumber(uniqid())
                    ->setDescription('Your transaction description');
        
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('/postmodelregistraton/step2/payment_status')) // Specify return URL
                      ->setCancelUrl(URL::route('/postmodelregistraton/step2/payment_status'));
        
        //adatok összeszedése
        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        //dd($payment);
        try {
                $payment->create($this->_api_context);
             //  dd($payment);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    echo "Exception: " . $ex->getMessage() . PHP_EOL;
                    $err_data = json_decode($ex->getData(), true);
                    exit;
                } else {
                    die('Some error occur, sorry for inconvenient');
                }
        }
         
        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        Session::put('paypal_payment_id', $payment->getId());
        if (isset($redirect_url)) {
            // redirect to paypal
           // dd($payment);
            return Redirect::away($redirect_url);
        }

        return Redirect::route('/postmodelregistraton/step2/payment_status')
                        ->with('error', 'Unknown error occurred');
    }

    
    public function getPaymentStatus() 
    {
      //  dd('sadadadasdasdada');
        // Get the payment ID before session clear
        $payment_id = Session::get('paypal_payment_id');

        // clear the session payment ID
        Session::forget('paypal_payment_id');
        if (empty($_GET['PayerID']) || empty($_GET['token'])) {
            return Redirect::to('/model-registration/step2')
                            ->with('error', 'Payment failed');
        }
        $payment = Payment::get($payment_id, $this->_api_context);

        // PaymentExecution object includes information necessary 
        // to execute a PayPal account payment. 
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site

        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);
        echo '<pre>';
        print_r($result.'asdasdasd');
        echo '</pre>';
        exit; // DEBUG RESULT, remove it later

        if ($result->getState() == 'approved') { // payment made
            return Redirect::route('/postmodelregistraton/step2/payment_status')
                            ->with('success', 'Payment success');
        }

        return Redirect::route('/postmodelregistraton/step2/payment_status')
                        ->with('error', 'Payment failed');
    }
    
    public function singlePayout()
    {
        $payouts = new \PayPal\Api\Payout();
       
        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
        // ### NOTE:
        // You can prevent duplicate batches from being processed. If you specify a `sender_batch_id` that was used in the last 30 days, the batch will not be processed. For items, you can specify a `sender_item_id`. If the value for the `sender_item_id` is a duplicate of a payout item that was processed in the last 30 days, the item will not be processed.
        // #### Batch Header Instance
        $senderBatchHeader->setSenderBatchId(uniqid())
            ->setEmailSubject("You have a Payout!");
        // #### Sender Item
        // Please note that if you are using single payout with sync mode, you can only pass one Item in the request
        $senderItem = new \PayPal\Api\PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thanks for your patronage!')
            ->setReceiver('personal@ikron.hu')
            ->setSenderItemId("2014031400023")
            /*->setAmount(new \PayPal\Api\Currency('{
                                "value":"1.0",
                                "currency":"USD"
                            }'));*/
            ->setAmount(array(
                        'value' => '17.0',
                        'currency' => 'USD'
                        ));
        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);
        // For Sample Purposes Only.
        $request = clone $payouts;
        // ### Create Payout
        try {
            $output = $payouts->createSynchronous(
                            $this->_api_context, 
                            new \PayPal\Transport\PayPalRestCall($this->_api_context));
            
        } catch (Exception $ex) {
            dd($request);
            #dd("Created Single Synchronous Payout", "Payout", null, $request, $ex);
            exit(1);
        }
        #dd('2');
        #dd("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);
        return $output;
    }
    
    public function getPayoutStatus()
    {
        // # Get Payout Item Status Sample
        //
        // Use this call to get data about a payout item, including the status, without retrieving an entire batch. 
        // You can get the status of an individual payout item in a batch in order to review the current status 
        // of a previously-unclaimed, or pending, payout item.
        // https://developer.paypal.com/docs/api/#get-the-status-of-a-payout-item
        // API used: GET /v1/payments/payouts-item/<Payout-Item-Id>
        /** @var \PayPal\Api\PayoutBatch $payoutBatch */
        
        $payoutBatch = require 'GetPayoutBatchStatus.php';
        // ## Payout Item ID
        // You can replace this with your Payout Batch Id on already created Payout.
        $payoutItems = $payoutBatch->getItems();
        $payoutItem = $payoutItems[0];
        $payoutItemId = $payoutItem->getPayoutItemId();
        // ### Get Payout Item Status
        try {
            $output = \PayPal\Api\PayoutItem::get($payoutItemId, $this->_api_context);
        } catch (Exception $ex) {
            dd($output);
            exit(1);
        }
        
        //ResultPrinter::printResult("Get Payout Item Status", "PayoutItem", $output->getPayoutItemId(), null, $output);
        return $output;  
    }
}
