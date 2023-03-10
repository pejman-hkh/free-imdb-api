<?php
namespace Peji\DB;
use Peji\Cache as PejiCache;

class DHC {

	function __construct( $class ) {
		$this->db = DB::$db;
		$this->class = $class;
	}
	
	function __destruct() {
		//echo "here dhc\n";
	}

	public static $instance;
	public static function getInstance( $class ) {

		if( @$ret = self::$instance[ $class ] ) {
			return $ret;
		}

		self::$instance[ $class ] = new self( $class );
		return self::$instance[ $class ];
	}

	function query( $sql = '', $bind = [] ) {
		$this->sql .= $sql;
		$this->bind = $bind;

		return $this;
	}

	function sql( $sql = '', $table = '', $extraSql = '', $bind = [] ) {
		$this->sql = $sql;
		$this->table = $table;
		$this->extraSql = $extraSql;
		$this->bind = $bind;

		return $this;
	}

	function execute( $bind = [] ) {
		$this->db = DB::$db;
		
		return $this->db->prepare( $this->sql )->execute(@$bind);
	}

	function count1( $bind = [] ) {
		if( count1( $this->bind ) > 0 ) {
			$bind = array_merge( $bind, $this->bind );
		}
		$this->sql = " select count(*) as count from ".$this->table." ".$this->extraSql;

		$this->db = DB::$db;
		$fetch = $this->db->prepare( $this->sql )->execute(@$bind)->fetch();

		return $fetch['count'];
	}

	function find( $bind = [], $simple = false ) {
		$class = $this->class;
		//$o = new $class();
		if( count1( $this->bind ) > 0 ) {
			$bind = array_merge( $bind, $this->bind );
		}

		if( @count1( (array)$this->paginateData ) > 0 ) {
			if( strtolower( substr( trim($this->sql), 0, 6 ) ) == 'select' ) {
				//$this->sql = substr( trim($this->sql), 6);
				//$this->sql = 'SELECT SQL_CALC_FOUND_ROWS '.$this->sql;
			}
		}

		//echo $this->sql;
		//echo "\n";


		if( isset( $_GET['showSql']) ) {
			$startTime = microtime(true);
		}

		$this->db = DB::$db;
		$query = $this->db->prepare( $this->sql )->execute(@$bind);

		if( isset( $_GET['showSql']) ) {
			$execTime = microtime(true) - $startTime;
		}

		if( @$this->paginateData ) {
			$csql = $this->sql;
			$csql = substr($csql, strpos( $csql, 'from') );
			$csql = substr($csql, 0, strpos( $csql, 'limit') );
			$countsql = "select count(*) as count ".$csql;
			$countsql = preg_replace($regex = '#order\s*by\s*(\w+)\s*(\w+)\s*#i', '', $countsql);
			$countsql = preg_replace($regex = '#\,\s*(\w+)\s*(desc|asc)\s*#i', '', $countsql);

			$jbind = json_encode( $bind );
			$md5 = md5($countsql.$jbind);
			$check = DB::sql("select * from countcache where md5 = ?")->findFirst([ $md5 ]);

			if( @$check->id ) {
				$this->count = $check->ncount;
				if(  time() - $check->date > 60 ) {
					DB::sql("update countcache set status = 2 where id = ? ")->execute([ $check->id ]);
				}
			} else {
				$fetch = DB::sql( $countsql )->findFirst($bind);
				if( ! @$check->id && $fetch->count >= 2000 ) {
					DB::sql("insert into countcache(nsql,bind,md5,ncount,date) values(?,?,?,?,?)")->execute([$countsql, $jbind, $md5, $fetch->count, time() ]);
				}

				$this->count = $fetch->count;
			}
			//$fetch = $this->db->prepare("SELECT FOUND_ROWS()")->execute()->fetch();
			//$this->count = @$fetch["FOUND_ROWS()"];
		}

		$ret = [];
		while( $v = $query->next() ) {
			if( $simple ) {
				$ret[] = (object)$v;
			} else {			
				$o = new $class();
				$o->recordExists = true;
				$o->setObj( $v );
		
				$ret[] = $o;
			}
		}

		if( isset( $_GET['showSql']) ) {
			$arr = PejiCache::get('sqls');
			$arr[] = [ $this->sql, $bind, $execTime];
			PejiCache::set('sqls', $arr);		
		}


		return $ret;
	}

	function findFirst( $bind = [] ) {
		return @$this->find( $bind )[0];
	}

	public function paginate( $limit, $page = 1 ) {
		$this->paginateData = [ $limit, $page ];
		$c = (int)( $page * $limit - $limit );
		$limit = (int)$limit;
		$this->sql .= " limit ".($c > 0 ? $c : 0).", $limit";

		return $this;
	}

	public function getPaginate() {

		$number = @$this->paginateData[0]?:1;
		$page = $this->paginateData[1];

		unset($this->paginateData);

		$count = $this->count;

		$limit = 4;
		$nP = ceil( $count / $number );

		$data["start"] = ( $page - $limit ) <= 0 ? 1 : $page - $limit;
		$data["end"] = ( $page + $limit >= $nP ) ? $nP : $page + $limit;
		$data["count"] = $count;
		$data["endPage"] = ceil($count / $number);
		$data["next"] = $page >= ceil( $count / $number ) ? $page : $page + 1;
		$data["prev"] = $page <= 1 ? 1 : $page - 1;

		return $data;	
	}	
}
