<?php

use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class TransactionItems extends Eloquent implements RemindableInterface {
	
	use RemindableTrait;

	protected $table = 'transaction_items';
	protected $primaryKey = 'id';
	
}