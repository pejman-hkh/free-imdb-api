<?php
namespace App\Controller\User;

use App\Controller\baseController as baseController;
use App\Lib\searchBuilder;
use Peji\DB\DB;
use Peji\Session;
use Peji\Cache as PejiCache;

class appController extends baseController {

	function afterApp() {
		if( isset( $_GET['showSql']) ) {
	
			$arr = PejiCache::get('sqls');
			usort($arr,function( $a, $b ) {
				return $a[2] < $b[2];
			});

			echo '<link rel="stylesheet" href="'.baseUrl.'css/debug.css">';
			echo '<script>
				var data = '.json_encode( $arr ).';
	
			</script>';
			echo '<script src="'.baseUrl.'js/debug.js"></script>';

		}	
	
	}

	function setViewDir( $dir ) {
		$this->set('viewDir', $dir );
	}

	public function beforeApp() {
		$this->setViewDir('user');

		$this->set("title", 'Free Imdb Api');
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