<?php
$a = new telparser;
$tel = '13718464678';
var_dump($a->parse($tel));

/*
重复数字（如“88”）

有两个或两个以上挨在一起的重复数字。666、3333……

连续数字（如“123”）

有三位或三位以上的连续数字，包含升序、降序。9876、4567……

循环数字（如“1212”）

有四位或四位以上的循环数字。5656、348348……

AABB

有两个两位重复数字。4466、7722……

ABBA

1331、3663……

AAA

111、222、333、444……

AAAA

1111、2222、3333、4444……

升降序ABCD

有四位连续数字，包含升序、降序。1234、5678、6543……

AABBCC

有三个两位重复数字。446699、332277……

AAABBB

有两个三位重复数字、333666、555999……

*/

class telparser
{
	private $_parsers = array(
		'is_repeat' => '_isRepeat',
		'is_continue' => '_isContinue',
		'is_loop' => '_isLoop',
		'num_cnt' => '_numCount',
	);

	private $_arr_tel = array();

	private $_reg = array(
		'AA' => '/(\d)\1/',
		'AAA' => '/(\d)\1/{2}/',
		'AAAA' => '/(\d)\1{3}/',
		'AABB' => '/(?!(\d)\1{3})(?:(\d)\2){2}/',
		'ABBA' => '/(\d)([^\1])\2\1/',
		'ABAB' => '/(?:(\d)[^\1]){2}/',
		'AABBCC' => '/(?!(\d)\1{5})(?:(\d)\2){3}/',
		'AAABBB' => '/(\d)\1{2}([\d^\1])\2{2}/',
		//'AABBAABB' => '/(?!(\d)\1{3})((?:(\d)\3){2})\2/',
		//'AABBCCDD' => '/((\d)\2([^\2])\3)(?!\1)(\d)\4([^\4])\5/',
		//'ABABCDCD' => '/(?:(?!(\d)\1)(\d{2})\2){2}/',
		//'ABCDABCD' => '//',
	);

	public function __construct()
	{
		
	}	

	public function parse($tel)
	{
		$this->_arr_tel = str_split($tel);
		$ret = array();
		foreach($this->_parsers as $key=>$parser)
		{
			$ret[$key] = $this->$parser($tel);
		}
		return $ret;
	}

	private function _isRepeat($tel)
	{
		$reg = '/(\d)\1/';
		//preg_match_all($reg, $tel, $tmp);
		//var_dump($tmp);
		return preg_match($reg, $tel) ? 1 : 0;
	}

	private function _isContinue($tel)
	{
		$arr_tel = $this->_arr_tel;
		foreach($arr_tel as $key=>$v)
		{
			if(!isset($arr_tel[$key+2]))
			{
				return 0;
			}
			if($arr_tel[$key+1] == $v+1 and $arr_tel[$key+2] == $v+2)
			{
				return 2;
			}
		}
		return 0;
	}
	
	private function _isLoop($tel)
	{
		$reg = '/(\d{2,})\1/';
		return preg_match($reg, $tel) ? 4 : 0;
	}

	private function _numCount($tel)
	{
		$ret = array();
		foreach($this->_arr_tel as $v)
		{
			$ret['num_'.$v] += 1;
		}
		return $ret;
	}
}
