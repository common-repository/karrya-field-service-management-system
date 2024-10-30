<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_site_search' );

?>
<div class="fsms_div">
<div id="site_block">
    <div>
        <form method="GET" action="">
            <table>
                <caption>Search</caption>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <td>

                        <input type="hidden" name="page" value="fsms_sites">
                        <input type="hidden" name="action" value="search">
                        <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                        <input type="text" name="searchKey" value="<?php echo @esc_attr( $filters['searchKey'] ); ?>">


                    </td>
                    <td><input type="submit" name="search" value="Search"></td>
                    <td><a href="admin.php?page=fsms_sites"><input type="Button" name="search" value="Reset"></a></td>
                </tr>
            </table>
        </form>

    </div>
    <table width="100%">
        <caption>
            Site list

        </caption>
        <tr>
            <th><a href=''>ID</a></th>
            <th><a href=''>Site name</a></th>
            <th><a href=''>Site city</a></th>
            <th><a href=''>Customer name</a></th>
            <th>Option</th>
        </tr>
		<?php foreach ( $sites as $site ): ?>
            <tr>
                <td><?php echo esc_attr( $site->id ); ?></a></td>
                <td><?php echo esc_attr( $site->site_name ); ?></td>
                <td><?php echo esc_attr( $site->site_city ); ?></td>
                <td><?php echo esc_attr( $site->cust_id ); ?></td>
                <td><a href="admin.php?page=fsms_sites&action=view&id=<?php echo esc_attr( $site->id ); ?>">View</a> |
                    <a
                            href="admin.php?page=fsms_sites&action=add_lead&id=<?php echo esc_attr( $site->id ); ?>">Add
                        lead</a> |
                    <a href="admin.php?page=fsms_sites&action=edit&id=<?php echo esc_attr( $site->id ); ?>">Edit</a> |
                    <a
                            href="admin.php?page=fsms_sites&action=delete&id=<?php echo esc_attr( $site->id ); ?>"
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
		$urlPagi        = "";

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
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_sites&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>1</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_sites&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>1</a></span>";
			}


			$leadPagi .= "...&nbsp;";
		}

		for ( $i = $startpage; $i <= $endpage; $i ++ ) {
			if ( $page == $i ) {
				$leadPagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=fsms_sites&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer' ><a href='admin.php?page=fsms_sites&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			}


		}
		if ( $endpage < $totalPageCount ) {
			$leadPagi .= "...&nbsp;";
			if ( $page == $totalPageCount ) {
				$leadPagi .= "&nbsp;" . $totalPageCount . "</span>";
			} else {
				$leadPagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=fsms_sites&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $totalPageCount . "</a></span>&nbsp;";
			}

		}

		$leadPagi .= "</td>";
		$leadPagi .= "</tr>";
		echo wp_kses_post( $leadPagi );
		?>
    </table>
</div>
</div>
