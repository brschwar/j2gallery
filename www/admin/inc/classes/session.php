<?php
// A class to help work with Sessions
// In this case, to store shopping cart info.

class Session {
	
	private $has_cart=false;
	public $cart;
	
	
	
	function __construct() {
		session_start();
		$this->check_cart();
		/*
		if($this->has_cart) {
			// print out list of items....
		} else {
			// echo "You have no items in your cart.";
		}
		*/	
	}
	
	
	public function add_item($item) {
		if($item){
			$separater = "";
			if ($this->cart != '') $separater = ",";
			$this->cart .= ($separater.$item);
			if (isset($_SESSION['cart'])) {
				$_SESSION['cart'] .= $separater.$item;
			} else {
				$_SESSION['cart'] = $item;
			}
			$this->has_cart = true;
		}
	}
	
	
	public function remove_item($item) {
		$str_array = explode(",", $this->cart);
		$this->clear_cart();
		foreach ($str_array as $value) {
			if ($value != $item && $value != '') {
				$this->add_item($value);
			} else {
				$item = '';		
			}
		}
		
	}
	
	
	public function clear_cart() {
		if ($this->has_cart) {
			$this->cart = $_SESSION['cart'] = '';
			$this->has_cart = false;
		}
	}	
	
	public function return_cart() {
		if ($this->has_cart) {
			return $this->cart;
		} else {
			return '';
		}
	}
	
	
	private function check_cart() {
		if(isset($_SESSION['cart'])) {
			$this->cart = $_SESSION['cart'];
			$this->has_cart = true;
		} else {
			$this->has_cart = false;
		}
	}
	
	
	
}

$session = new Session();

?>