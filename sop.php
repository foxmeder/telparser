<?php
$s = new sop;

array(6,9,3);
array(1=>45, 2=>56, 0=>34);

var_dump($sop->parseGet(array('hd'=>'123'));

class sop
{
	public function __construct($get)
	{
	}

	public function parseGet($get)
	{
		unset($get['page']);
	}

	private function _parseHd($get)
	{
		$hds = array(123,456,234,456);
		return $this->_parseArray($hds, $get, 'hd');
	}

	private function _parseArray($opts, $get, $name, $assoc = false)
	{
		$ret = array();
		$chopt = $get[$name];
	
		foreach($opts as $k=>$opt)
		{
			$v = $assoc ? $k : $opt;
			$selected = $chopt !== '' and $chopt == $v;
			$get[$name] = $selected ? '' : $v;
			$tmp = array(
				'label' => $opt,
				'uri' => http_build_query($get),
				'selected' => $selected,
			);
			$ret[] = $tmp;
		}
		return $ret;
	}
}

