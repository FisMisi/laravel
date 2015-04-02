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

    public function postPayment() 
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName('Item 1') // item name
                ->setCurrency('USD')
                ->setQuantity(2)
                ->setPrice('150'); // unit price
        $item_2 = new Item();
        $item_2->setName('Item 2')
                ->setCurrency('USD')
                ->setQuantity(4)
                ->setPrice('70');
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1, $item_2, $item_3));
        $amount = new Amount();
        $amount->setCurrency('USD')
                ->setTotal(580);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(URL::route('payment.status')) // Specify return URL
                ->setCancelUrl(URL::route('payment.status'));
        $payment = new Payment();
        $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
        try {
            $payment->create($this->_api_context);
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
            return Redirect::away($redirect_url);
        }
        return Redirect::route('original.route')
                        ->with('error', 'Unknown error occurred');
    }

}
