<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Transactions extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'transactions';
	protected $primaryKey = 'id';

	public function getItems() {
		return TransactionItems::where('transaction_id', '=', $this->id)->get();
	}
	
	public function getItemsToArray() {
		return $this->getItems()->toArray();
	}
        
        public static function saveToDB($transaction) {
            $amount = $transaction->getAmount();
            $itemList = $transaction->getItemList();
            $items = $itemList->getItems();
            $details = $amount->getDetails();
            var_dump($items);
            foreach($items as $item) {
                var_dump($item->getName());
                var_dump($item->getCurrency());
                var_dump($item->getPrice());
                var_dump($item->getQuantity());
                echo '<br/>________________________________________<br/>';
            }
            var_dump($details->getSubtotal());
            var_dump($details->getTax());
            
            die();
        }

}