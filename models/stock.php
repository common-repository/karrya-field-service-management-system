<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSStock {
	function add( $vars ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$result          = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $stock_tablename . " SET
			sku=%s,description=%s,added_stock_count=%f,added_date=%s,buy_amount=%f,sell_amount=%f,supplier_id=%d,charge_type=%d ",
			$vars['sku'], $vars['description'], $vars['added_stock_count'], date( "Y-m-d H:i:s" ) , $vars['buy_amount'], $vars['sell_amount'], $vars['supplier_id'], $vars['charge_type']) );


		if ( $result === false ) {
			return false;
		}

		return true;
	}

	public static function addOutOfStock( $sku, $qty, $lead_id, $charge_type ) {
		global $wpdb;


		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$result          = $wpdb->query( $wpdb->prepare( "INSERT INTO " . $stock_tablename . " SET charge_type=%d,lead_id=%d,sku=%s,used_stock_count=%f,is_lac=%d,added_date=%s ", $charge_type, $lead_id, $sku, $qty, 1, date( "Y-m-d H:i:s" ) ) );


		if ( $result === false ) {
			return false;
		}

		return true;
	}


	// prepare and sanitize vars
	function prepare_vars( &$vars ) {
		$vars['sku']               = sanitize_text_field( $vars['sku'] );
		$vars['description']       = sanitize_text_field( $vars['description'] );
		$vars['added_stock_count'] = sanitize_text_field( $vars['added_stock_count'] );

		$vars['supplier_id'] = sanitize_text_field( $vars['supplier_id'] );
		$vars['sell_amount'] = sanitize_text_field( $vars['sell_amount'] );
		$vars['buy_amount'] = sanitize_text_field( $vars['buy_amount'] );
		$vars['charge_type'] = sanitize_text_field( $vars['charge_type'] );

	}


	// list all sku, paginated. 
	// allow filters
	function find( $filters = null ) {
		global $wpdb;
		$searchParam = "";
		$searchvar   = "";


		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$tablename_users      = $wpdb->prefix . "users";
		$rstart          = $filters['rstart'];
		$rend            = $filters['rend'];
		$dir             = $filters['dir'];
		$ob              = $filters['ob'];

		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE S.sku LIKE %s OR S.description LIKE %s";
			$searchvar   = $filters['searchKey'];
			$stocks      = $wpdb->get_results( $wpdb->prepare( "SELECT S.*, U.user_nicename FROM " . $stock_tablename . " as S LEFT JOIN " . $tablename_users . " as U ON U.ID=S.supplier_id " . $searchParam . " ORDER BY $ob $dir LIMIT %d, %d", "%" . $searchvar . "%", "%" . $searchvar . "%", $rstart, $rend ) );
		} else {
			$stocks = $wpdb->get_results( $wpdb->prepare( "SELECT S.*,U.user_nicename FROM " . $stock_tablename . " as S LEFT JOIN " . $tablename_users . " as U ON U.ID=S.supplier_id ORDER BY $ob $dir LIMIT %d, %d", $rstart, $rend ) );
		}

		//echo $wpdb->last_query;
		return $stocks;

	}

	function getTotalCount( $filters = null ) {
		global $wpdb;
		$param     = "";
		$searchVar = "";
		if ( ! empty( $filters ) && $filters['searchKey'] != "" ) {
			$searchParam = " WHERE description LIKE '%" . $filters['searchKey'] . "' OR sku LIKE '%" . $filters['searchKey'] . "'";
		}
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		if ( ! empty( $filters ) && isset( $filters['searchKey'] ) ) {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $stock_tablename . "" . $searchParam ) );
		} else {
			$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $stock_tablename . "" ) );
		}


		return $totalCount;

	}

	function edit( $vars, $id ) {
		global $wpdb;

		$this->prepare_vars( $vars );
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$result          = $wpdb->query( $wpdb->prepare( "UPDATE " . $stock_tablename . " SET
			description=%s, sku=%s, added_stock_count=%f, charge_type=%d,buy_amount=%f,sell_amount=%f,supplier_id=%d WHERE id=%d",
			$vars['description'], $vars['sku'], $vars['added_stock_count'], $vars['charge_type'], $vars['buy_amount'], $vars['sell_amount'], $vars['supplier_id'], $id ) );

		if ( $result === false ) {
			return false;
		}

		return true;
	}

	// return specific stock details
	function get( $id ) {
		global $wpdb;
		$id              = intval( $id );
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$stock           = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $stock_tablename . " WHERE id=%d", $id ) );

		return $stock;
	}

	function delete( $id ) {
		global $wpdb;
		$id             = intval( $id );
		$site_tablename = $wpdb->prefix . "fsms_site";
		$result         = $wpdb->query( $wpdb->prepare( "UPDATE " . $site_tablename . " SET site_status=%d WHERE id=%d", 0, $id ) );


		if ( ! $result ) {
			return false;
		}

		return true;
	}

	public static function totalSkyRawCount( $sku ) {
		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$totalCount      = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM " . $stock_tablename . " WHERE sku=%s AND added_stock_count>used_stock_count ORDER BY id ASC ", $sku ) );

		//echo "<br>".$wpdb->last_query."-".$totalCount;
		return $totalCount;
	}

	public static function totalAddedSkuRawCount( $sku ) {
		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$totalCount      = $wpdb->get_var( $wpdb->prepare( "SELECT count(*) FROM " . $stock_tablename . " WHERE sku=%s AND added_stock_count>0 ORDER BY id ASC ", $sku ) );

		//echo "<br>".$wpdb->last_query."-".$totalCount;
		return $totalCount;
	}

	public static function updateStockAvailability( $sku, $qty, $stat, $end, $lead_id, $charge_type ) {
		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$stat            = intval( $stat );
		$end             = intval( $end );
		$qty             = (float) ( $qty );

		/*
		global $wpdb;
		$qty             = intval( $qty );
		$site_tablename = $wpdb->prefix . "fsms_site";
		$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $stock_tablename . " WHERE sku='".$sku."' AND added_stock_count>used_stock_count ORDER BY id ASC LIMIT 0,1" ) );
		*/
		$rowCount = FSMSStock::totalSkyRawCount( $sku );
		//echo "<br>rowCount".$rowCount;
		$stock = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $stock_tablename . " WHERE sku=%s AND added_stock_count>used_stock_count ORDER BY id ASC LIMIT %d,%d", $sku, $stat, $end ) );
		//echo $wpdb->last_query;
		$added_stock_count = @$stock->added_stock_count;
		$used_stock_count  = @$stock->used_stock_count;
		$totCount          = (float) ( $added_stock_count - $used_stock_count );
		//echo "<br>stat".$stat;
		if ( $stat < $rowCount ):
			if ( $qty <= $totCount ) {
				//echo "<br>qty".$qty;
				//echo "<br>totCount".$totCount;
				FSMSStock::updateStock( $stock->id, $qty );
			} else {
				//echo "<br>secount-totCount".$totCount;
				FSMSStock::updateStock( $stock->id, $totCount );
				$newQty = (float) ( $qty - $totCount );
				//echo "<br>secount-newQty".$newQty;
				FSMSStock::updateStockAvailability( $sku, $newQty, $stat, $end, $lead_id, $charge_type );
			}
		else:
			FSMSStock::addOutOfStock( $sku, $qty, $lead_id, $charge_type );
		endif;

	}

	public static function updateStock( $id, $qty ) {
		global $wpdb;
		$id              = intval( $id );
		$qty             = (float) ( $qty );
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$result          = $wpdb->query( $wpdb->prepare( "UPDATE " . $stock_tablename . " SET used_stock_count=%f+used_stock_count WHERE id=%d", $qty, $id ) );
		//echo "<br>".$wpdb->last_query;

		if ( ! $result ) {
			return false;
		}

		return true;
	}

	public static function deductStock( $id, $qty ) {
		global $wpdb;
		$id              = intval( $id );
		$qty             = (float) ( $qty );
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$result          = $wpdb->query( $wpdb->prepare( "UPDATE " . $stock_tablename . " SET used_stock_count=used_stock_count-%f WHERE id=%d", $qty, $id ) );
		echo "<br>" . $wpdb->last_query;
		//die();
		if ( ! $result ) {
			return false;
		}

		return true;
	}

	public static function deductStockAvailability( $sku, $qty, $stat, $end, $lead_id, $charge_type ) {
		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$stat            = intval( $stat );
		$end             = intval( $end );
		$qty             = (float) ( $qty );

		/*
		global $wpdb;
		$qty             = intval( $qty );
		$site_tablename = $wpdb->prefix . "fsms_site";
		$totalCount = $wpdb->get_var( ( "SELECT COUNT(*) FROM " . $stock_tablename . " WHERE sku='".$sku."' AND added_stock_count>used_stock_count ORDER BY id ASC LIMIT 0,1" ) );
		*/
		$rowCount = FSMSStock::totalAddedSkuRawCount( $sku );
		//echo "<br>qty".$qty;
		//echo "<br>rowCount".$rowCount;
		$stock = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM " . $stock_tablename . " WHERE sku=%s AND used_stock_count > 0 ORDER BY id DESC LIMIT %d,%d", $sku, $stat, $end ) );
		//echo $wpdb->last_query;
		$used_stock_count = $stock->used_stock_count;

		$totCount = (float) ( $used_stock_count );
		//echo "<br>stat".$stat;

		if ( $stat < $rowCount && $used_stock_count > 0 ):

			if ( $qty <= $totCount ) {
				//echo "<br>qty".$qty;
				//echo "<br>totCount".$totCount;
				FSMSStock::deductStock( $stock->id, $qty );
			} else {
				//echo "<br>secount-totCount".$totCount;
				FSMSStock::deductStock( $stock->id, $totCount );
				$newQty = (float) ( $totCount - $qty );
				//echo "<br>secount-newQty".$newQty;
				FSMSStock::deductStockAvailability( $sku, - $newQty, $stat, $end, $lead_id, $charge_type );
			}
		else:
			//FSMSStock::addOutOfStock( $sku, $qty,$lead_id,$charge_type );
		endif;

	}
	public static function search_sku(){

		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$tablename_users      = $wpdb->prefix . "users";
		$sku = sanitize_text_field( $_POST['sku'] );
		 

		$stocks          = $wpdb->get_results( $wpdb->prepare( "SELECT S.*,U.user_nicename FROM " . $stock_tablename . " as S LEFT JOIN " . $tablename_users . " as U ON U.ID=S.supplier_id  WHERE sku like %s", "%".$sku."%" ) );

		if ( wp_verify_nonce( $_POST['nonceVal'], 'ajax-nonce' )) {

			$list .= '<ul id="country-list">';
			foreach ( $stocks as $stock ):
				$list .= '<li onClick="setSkuVal('.esc_attr($stock->id).');" value="'.esc_attr($stock->id).'">'.esc_attr($stock->sku).' - '.esc_attr($stock->user_nicename).'</li>'; 
			endforeach;
			$list .= '</ul>'; 
		}
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $list );
		die();
	}

	public static function search_description(){

		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$description = sanitize_text_field( $_POST['charge_description'] );
		 

		$stocks          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $stock_tablename . " WHERE description like %s", "%".$sku."%" ) );

		if ( wp_verify_nonce( $_POST['nonceVal'], 'ajax-nonce' )) {

			$list .= '<ul id="country-list">';
			foreach ( $stocks as $stock ):
				$list .= '<li onClick="setSkuVal('.esc_attr($stock->id).');" value="'.esc_attr($stock->id).'">'.esc_attr($stock->sku).'</li>'; 
			endforeach;
			$list .= '</ul>'; 
		}
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $list );
		die();
	}

	public static function get_sku_details(){
		global $wpdb;
		$stock_tablename = $wpdb->prefix . "fsms_stock";
		$stock_id = sanitize_text_field( $_POST['stock_id'] );
		 

		$stocks          = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM " . $stock_tablename . " WHERE id = %d", $stock_id) );
		$stock = $stocks[0];
		if ( wp_verify_nonce( $_POST['nonceVal'], 'ajax-nonce' )) {

			$list = array("description"=>$stock->description,"charge_amount"=>$stock->sell_amount,"charge_type"=>$stock->charge_type,"supplier_id"=>$stock->supplier_id,"buy_amount"=>$stock->buy_amount,"sku"=>$stock->sku); 
		}
		// normally, the script expects a json respone
		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $list );
		die();
	}

}