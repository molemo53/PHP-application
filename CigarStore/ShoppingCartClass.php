<?php
	class ShoppingCart
	{
		private $oderID = 1;
		private $cigarID = 0;
		private $quantity = 0;
		private $total = 0;
		private $my_proc = 1;
		
		public function __construct()
		{
			echo "<p>I am in CONSTRUCT</p>";
		}
		
		public function setOrderID($order_id)
		{
			$this->oderID = $order_id;
		}
		
		public function setCigarID($cigar_id)
		{
			$this->cigarID = $cigar_id;
		}
		
		public function setQuantity($quant)
		{
			$this->quantity = $quant;
		}
		
		public function setTotal($total)
		{
			$this->total = $total;
		}
		
		public function setProcess($in_process)
		{
			$this->my_proc = $in_process;
		}
		
		public function getOrderID()
		{
			return $this->oderID;
		}
		
		public function getCigarID()
		{
			return $this->cigarID;
		}
		
		public function getQuantity()
		{
			return $this->quantity;
		}
		
		public function getTotal()
		{
			return $this->total;
		}
		
		public function getProcess()
		{
			return $this->my_proc;
		}
	}
?>