<?php
namespace App\Controller\User;

use App\Controller\baseController as baseController;
use App\Lib\searchBuilder;
use Peji\DB\DB;
use Peji\Session;
use Peji\Cache as PejiCache;

class appController extends baseController {

	function afterApp() {

	
	}

	function setViewDir( $dir ) {
		$this->set('viewDir', $dir );
	}

	public function beforeApp() {
		$this->setViewDir('user');

		$this->set("tdir", $this->controller);
		$this->set("pick", $this->action);
		$this->set('queryString', $this->queryString() );
		
	}

	protected function queryString() {
		$ret = '';
		$pre = '?';
		foreach( $this->get as $k => $v ) {
			@$ret .= @$pre.''.@$k.'='.@$v;
			$pre = '&';
		}
		return $ret;
	}

	protected function keyPairParams( $arr ) {
		$ret = [];
		if( @$arr ) foreach( $arr as $k => $v ) {
			if( $k % 2 !== 0 )
				continue; 

			$ret[$v] = @$arr[$k+1];
		}
		
		return $ret;
	}
}

?>