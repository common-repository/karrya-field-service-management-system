<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_stage_search' );

?>
<div class="fsms_div">
<div id="stage_block">
    <div>
        <form method="GET" action="">
            <table>
                <caption>Search</caption>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <td>

                        <input type="hidden" name="page" value="fsms_stages">
                        <input type="hidden" name="action" value="search">
                        <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                        <input type="text" name="searchKey" value="<?php echo @esc_attr( $filters['searchKey'] ); ?>">


                    </td>
                    <td><input type="submit" name="search" value="Search"></td>
                    <td><a href="admin.php?page=fsms_stages"><input type="Button" name="search" value="Reset"></a></td>
                </tr>
            </table>
        </form>

    </div>
    <table width="100%">
        <caption>
            Stage list
            <span><a href="admin.php?page=fsms_stages&action=add">Add new</a></span>
        </caption>
        <tr>
            <th>
                <a href='admin.php?page=fsms_stages&dir=<?php esc_attr( $odir ); ?>&ob=id<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>ID</a>
            </th>
            <th>
                <a href='admin.php?page=fsms_stages&dir=<?php echo esc_attr( $odir ); ?>&ob=stagename<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>Stage</a>
            </th>
            <th>
                <a href='admin.php?page=fsms_stages&dir=<?php echo esc_attr( $odir ); ?>&ob=stageorder<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>Order</a>
            </th>
            <th>Status</th>
            <th>Option</th>
        </tr>
		<?php foreach ( $stages as $stage ): ?>
            <tr>
                <td><?php echo esc_attr( $stage->id ); ?></a></td>
                <td><?php echo esc_attr( $stage->stage_name ); ?></td>
                <td><?php echo esc_attr( $stage->stage_order ); ?></td>
                <td>
					<?php
					$stageStatusTxt = "Enable";
					if ( $stage->stage_status == 0 ) {
						$stageStatusTxt = "Disable";
					}
					echo esc_attr( $stageStatusTxt );
					?></td>
                <td><a href="admin.php?page=fsms_stages&action=edit&id=<?php echo esc_attr( $stage->id ); ?>">Edit</a> |
                    <a
                            href="admin.php?page=fsms_stages&action=delete&id=<?php echo esc_attr( $stage->id ); ?>"
                            onclick="return confirm('Are you sure?')">Delete</a></td>

            </tr>
		<?php endforeach; ?>
		<?php

		$leadPagi = "";
		$pageText = 'Page:';
		if ( $totalCount > $pageCount ) {
			$pageText = 'Page:';
		}
		$leadPagi .= "<tr>";
		$leadPagi .= "<td colspan='8' class='rgt'>&nbsp;" . $pageText;

		//echo $acPageCount;
		$acPageCount    = ceil( ( $totalCount / $pageCount ) );
		$totalPageCount = $acPageCount;
		$page           = (int) ( $P );


		if ( ( $page - 9 ) > 0 ) {
			$startpage = $page - 9;
		} else {
			$startpage = 1;
		}


		if ( ( $page + 9 ) < $totalPageCount ) {
			$endpage = $page + 9;
		} else {
			$endpage = $totalPageCount;
		}


		if ( $startpage > 1 ) {
			if ( $page == 1 ) {
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_stages&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>1</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_stages&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>1</a></span>";
			}


			$leadPagi .= "...&nbsp;";
		}

		for ( $i = $startpage; $i <= $endpage; $i ++ ) {
			if ( $page == $i ) {
				$leadPagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=fsms_stages&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer' ><a href='admin.php?page=fsms_stages&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			}


		}
		if ( $endpage < $totalPageCount ) {
			$leadPagi .= "...&nbsp;";
			if ( $page == $totalPageCount ) {
				$leadPagi .= "&nbsp;" . $totalPageCount . "</span>";
			} else {
				$leadPagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=fsms_stages&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $totalPageCount . "</a></span>&nbsp;";
			}

		}

		$leadPagi .= "</td>";
		$leadPagi .= "</tr>";
		echo wp_kses_post( $leadPagi );
		?>
    </table>
</div>
</div>

