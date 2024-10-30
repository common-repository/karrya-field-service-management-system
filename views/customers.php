<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_customer_search' );

?>
<div class="fsms_div">
<div id="customer_block">
    <div>
        <form method="GET" action="">
            <table>
                <caption>Search</caption>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <td>

                        <input type="hidden" name="page" value="fsms_customers">
                        <input type="hidden" name="action" value="search">
                        <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                        <input type="text" name="searchKey" value="<?php echo @esc_attr( $filters['searchKey'] ); ?>">


                    </td>
                    <td><input type="submit" name="search" value="Search"></td>
                    <td><a href="admin.php?page=fsms_customers"><input type="Button" name="search" value="Reset"></a>
                    </td>
                </tr>
            </table>
        </form>

    </div>
    <table width="100%">
        <caption>
            Stage list
            <span><a href="admin.php?page=fsms_customers&action=add">Add new</a></span>
        </caption>
        <tr>
            <th>
                <a href='admin.php?page=fsms_customers&dir=<?php echo esc_attr( $odir ); ?>&ob=id<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>ID</a>
            </th>
            <th>
                <a href='admin.php?page=fsms_customers&dir=<?php echo esc_attr( $odir ); ?>&ob=fname<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>Name</a>
            </th>
            <th>
                <a href='admin.php?page=fsms_customers&dir=<?php echo esc_attr( $odir ); ?>&ob=email<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>Email</a>
            </th>
            <th>
                <a href='admin.php?page=fsms_customers&dir=<?php echo esc_attr( $odir ); ?>&ob=phone<?php echo "&nonce=" . $filters['nonce'] . "&action=" . esc_attr( $filters['action'] ) . "&searchKey=" . esc_attr( $filters['searchKey'] ); ?>'>Phone</a>
            </th>
            <th>Option</th>
        </tr>
		<?php foreach ( $customers as $customer ): ?>
            <tr>
                <td><?php echo esc_attr( $customer->id ); ?></a></td>
                <td><?php echo esc_attr( $customer->cust_fname ); ?><?php echo esc_attr( $customer->cust_lname ); ?></td>
                <td><?php echo esc_attr( $customer->cust_email ); ?></td>
                <td><?php echo esc_attr( $customer->cust_phone ); ?></td>
                <td>
                    <a href="admin.php?page=fsms_customers&action=view&id=<?php echo esc_attr( $customer->id ); ?>">View</a>
                    | <a
                            href="admin.php?page=fsms_customers&action=add_lead&id=<?php echo esc_attr( $customer->id ); ?>">Add
                        lead</a> | <a
                            href="admin.php?page=fsms_customers&action=edit&id=<?php echo esc_attr( $customer->id ); ?>">Edit</a>
                    | <a href="admin.php?page=fsms_customers&action=delete&id=<?php echo esc_attr( $customer->id ); ?>"
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
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_customers&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>1</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_customers&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>1</a></span>";
			}


			$leadPagi .= "...&nbsp;";
		}

		for ( $i = $startpage; $i <= $endpage; $i ++ ) {
			if ( $page == $i ) {
				$leadPagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=fsms_customers&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer' ><a href='admin.php?page=fsms_customers&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			}


		}
		if ( $endpage < $totalPageCount ) {
			$leadPagi .= "...&nbsp;";
			if ( $page == $totalPageCount ) {
				$leadPagi .= "&nbsp;" . $totalPageCount . "</span>";
			} else {
				$leadPagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=fsms_customers&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $totalPageCount . "</a></span>&nbsp;";
			}

		}

		$leadPagi .= "</td>";
		$leadPagi .= "</tr>";
		echo wp_kses_post( $leadPagi );
		?>
    </table>
</div>
</div>


