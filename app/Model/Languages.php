<?php
namespace App\Model;
class Languages extends \Peji\DB\Model {
	var $table = 'languages';

	function getMShort() {
		$str = ( htmlspecialchars_decode( explode("?", $this->imdbLink)[1] ) );
		parse_str( $str, $qs );
		$short = strtolower( $qs['primary_language'] );
		return $short;
	}
		
}