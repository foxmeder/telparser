<?php
$a = new telparser;
$tel = '13718464678';
var_dump($a->parse($tel));

/*
AA  AAA  AAAA  AAAAA  升降序ABC  升降序ABCD  AABB  AABBCC  AABBCCDD  AAABBB  AAAABB  AABBB  AAAAB  AAAB  ABAB  ABABAB  ABCABC
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
	private $_sig = array();

	private $_reg = array(
		'AA' => '/(\d)\1$/',
		'AAA' => '/(\d)\1{2}$/',
		'AAAA' => '/(\d)\1{3}$/',
		'AAAAA' => '/(\d)\1{4}$/',
		'AABB' => '/(\d)\1([^\1])\2$/',
		'AABBCC' => '/(?!\d*?(\d)\1{2}\d*?)(?:(\d)\2){3}$/',
		'AABBCCDD' => '/(?!\d*?(\d)\1{2}\d*?)(?:(\d)\2){4}$/',
		'ABBA' => '/(\d)([^\1])\2\1$/',
		'ABAB' => '/(?:(\d)[^\1]){2}$/',
		'ABABAB' => '/(?:(\d)[^\1]){3}$/',
		//'AABBCC' => '/(?!(\d)\1{5})(?:(\d)\2){3}/',
		'AAABBB' => '/(\d)\1{2}([^\1])\2{2}$/',
		'AAAABB' => '/(\d)\1{3}([^\1])\2$/',
		'AABBB' => '/(\d)\1([^\1])\2{2}$/',
		'AAAAB' => '/(\d)\1{3}[^\1]$/',
		'AAAB' => '/(\d)\1{2}[^\1]$/',
		'ABCABC' => '/(?!\d*?(\d)\1{2}\d*?)(\d{3})\2$/',
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
