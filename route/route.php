<?php
use Peji\Router as Router;
use Peji\View as View;

function pageNotFount($get = []) {
	$a['title'] = $get['title'];
	$a['Country'] = $get['Country'];
	return Peji\Bootstrap::start( 'user', 'notfound', 'index', 0, [], $a );

}

Router::setPath( getPath() );

Router::route( '{:all}', function( $p ) {
	$controller = urldecode($p[0])?:'index';

	if( $controller == 'index' ) {	
		$action = 'index';
		$id = urldecode(@$p[1]);
		$params = $p;
		array_shift( $p );
		array_shift( $p );
	} else {
		$action = @$p[1]?:'index';
		$id = @$p[2];
		$params = $p;
	}


	if( ! Peji\Bootstrap::start( 'user', $controller, $action, $id, $params ) ) {
		if( ! Peji\Bootstrap::start( 'user', 'page', 'index', $controller, $params )  ) {
			pageNotFount();
		}
	}
});


Router::dispatch(function( $status ) {

	if( $status == 404 ) {
		pageNotFount();
	}
});