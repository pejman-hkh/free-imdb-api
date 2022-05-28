<?
namespace App\Controller;

use Peji\Controller;

class baseController extends Controller {

	function flash( $msg, $status = 0, $data = [] ) {
		$this->disableView = 1;
		echo json_encode( [ 'msg' => $msg, 'status' => $status, 'data' => $data ] );
		//exit();
	}

	function isAjax() {
		if( isset( $this->get['ajax'] ) || isset( $this->post['ajax'] ) ) {
			return true;
		}
		return false;
	}

	function validate( $arr ) {
		foreach( $arr as $k => $v ) {
			
			if( is_array( $v ) ) {
				$check = $v[0];
				$error = $v[1];
			} else {
				$error = $v.' is required';
				$check = $v;
			}

			if( empty( $this->post[ $k ] ) && $check === 'required' ) {

				$this->error = $error;
				return 1;
			}

		}
	}
}

?>