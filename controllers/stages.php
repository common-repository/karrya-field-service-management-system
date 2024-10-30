<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
class FSMSStages {
	public static function manage() {
		$_stage = new FSMSStage();

		$action = sanitize_text_field( ( isset( $_GET['action'] ) ) ? ( $_GET['action'] ) : ( 'list' ) );
		switch ( $action ) {
			case 'add':
				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_stage_add_edit' ) ) {
					$_stage->add( $_POST );
					$success = "added";
					fmsm_redirect( "admin.php?page=fsms_stages&action=list" );
				}
				include( FSMS_PATH . "/views/addEditStage.php" );
				break;
			case 'delete':
				$id = intval( $_GET['id'] );
				$_stage->delete( $id );
				$success = "deleted";
				fmsm_redirect( "admin.php?page=fsms_stages&action=list" );
				break;
			case 'edit':
				$id = intval( $_GET['id'] );

				if ( ! empty( $_POST['ok'] ) && wp_verify_nonce( $_POST['nonce'], 'fsms_stage_add_edit' ) ) {
					$_stage->edit( $_POST, $id );
					$success = "edit";
					fmsm_redirect( "admin.php?page=fsms_stages&action=list" );
				}
				$stage = $_stage->get( $id );
				include( FSMS_PATH . "/views/addEditStage.php" );
				break;
			case 'search':
				if ( isset( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], 'fsms_stage_search' ) ) {

					$searchKey = sanitize_text_field( ( isset( $_GET['searchKey'] ) ) ? ( $_GET['searchKey'] ) : ( "" ) );

					$pagei     = sanitize_text_field( ( isset( $_GET['pagei'] ) ) ? ( $_GET['pagei'] ) : ( 0 ) );
					$pageCount = 5;
					if ( esc_attr( get_option( 'fsms_no_of_rows' ) ) > 0 ) {

						$pageCount = esc_attr( get_option( 'fsms_no_of_rows' ) );

					} else {
						$pageCount = 5;
					}
					if ( $pagei == "" || $pagei == 0 ) {
						$P          = 1;
						$offSetPage = 0;
					} else {
						$P          = $pagei;
						$offSetPage = $pagei - 1;
					}
					$rstart = $offSetPage * $pageCount;
					$rend   = $pageCount;
					$dir    = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
					if ( $dir != 'ASC' and $dir != 'DESC' ) {
						$dir = 'ASC';
					}
					$odir  = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
					$ob    = "id";
					$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );
					if ( ! empty( $obGet ) ) {

						if ( $obGet == "id" ) {
							$ob = "id";
						}
						if ( $obGet == "stagename" ) {
							$ob = "stage_name";
						}
						if ( $obGet == "stageorder" ) {
							$ob = "stage_order";
						} else {
							$ob = "id";
						}


						$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
					}


					$filters    = array(
						"rstart"    => $rstart,
						"rend"      => $rend,
						"ob"        => $ob,
						"dir"       => $dir,
						"getOb"     => $obGet,
						"searchKey" => $searchKey,
						"nonce"     => $_GET['nonce'],
						"action"    => $action
					);
					$stages     = $_stage->find( $filters );
					$totalCount = $_stage->getTotalCount( $filters );
					include( FSMS_PATH . "/views/stages.php" );
				}

				break;
			case 'list':
			default:
				$pagei     = sanitize_text_field( ( isset( $_GET['pagei'] ) ) ? ( $_GET['pagei'] ) : ( 0 ) );
				$pageCount = 5;
				if ( esc_attr( get_option( 'fsms_no_of_rows' ) ) > 0 ) {

					$pageCount = esc_attr( get_option( 'fsms_no_of_rows' ) );

				} else {
					$pageCount = 5;
				}
				if ( $pagei == "" || $pagei == 0 ) {
					$P          = 1;
					$offSetPage = 0;
				} else {
					$P          = $pagei;
					$offSetPage = $pagei - 1;
				}
				$rstart = $offSetPage * $pageCount;
				$rend   = $pageCount;
				$dir    = sanitize_text_field( ( isset( $_GET['dir'] ) ) ? ( $_GET['dir'] ) : ( 'DESC' ) );
				if ( $dir != 'ASC' and $dir != 'DESC' ) {
					$dir = 'ASC';
				}
				$odir  = ( $dir == 'ASC' ) ? 'DESC' : 'ASC';
				$ob    = "id";
				$obGet = sanitize_text_field( ( isset( $_GET['ob'] ) ) ? ( $_GET['ob'] ) : ( $ob ) );
				if ( ! empty( $obGet ) ) {

					if ( $obGet == "id" ) {
						$ob = "id";
					}
					if ( $obGet == "stagename" ) {
						$ob = "stage_name";
					}
					if ( $obGet == "stageorder" ) {
						$ob = "stage_order";
					} else {
						$ob = "id";
					}


					$orderby = "ORDER BY " . sanitize_text_field( $ob ) . ' ' . $dir;
				}


				$filters    = array(
					"rstart"    => $rstart,
					"rend"      => $rend,
					"ob"        => $ob,
					"dir"       => $dir,
					"getOb"     => $obGet,
					"searchKey" => "",
					"nonce"     => "",
					"action"    => ""
				);
				$stages     = $_stage->find( $filters );
				$totalCount = $_stage->getTotalCount();
				include( FSMS_PATH . "/views/stages.php" );
				break;
		}
	}
}