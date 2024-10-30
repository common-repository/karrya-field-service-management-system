<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
$nonce = wp_create_nonce( 'fsms_stock_search' );

?>
<div class="fsms_div">
<div id="stock_block">
    <div>
        <form method="GET" action="">
            <table>
                <caption>Search</caption>
                <tr>
                    <th colspan="3"></th>
                </tr>
                <tr>
                    <td>

                        <input type="hidden" name="page" value="fsms_stocks">
                        <input type="hidden" name="action" value="search">
                        <input type="hidden" name="nonce" value="<?php echo $nonce; ?>">
                        <input id="sku" onkeyup='searchSku();' autocomplete='off' type="text" name="searchKey" value="<?php echo @esc_attr( $filters['searchKey'] ); ?>">
                        <div id='suggesstion-box'></div> 


                    </td>
                    <td><input type="submit" name="search" value="Search"></td>
                    <td><a href="admin.php?page=fsms_stocks"><input type="Button" name="search" value="Reset"></a></td>
                </tr>
            </table>
        </form>

    </div>
    <table width="100%">
        <caption>
            Stock list
            <span><a href="admin.php?page=fsms_stocks&action=add">Add new</a></span>
        </caption>
        <thead>
        <tr>
            <th><a href=''>ID</a></th>
            <th><a href=''>Added date</a></th>
            <th><a href=''>Charge type</a></th>
            <th><a href=''>Sku</a></th>
            <th><a href=''>Description</a></th>
            <th><a href=''>Stock count</a></th>
            <th><a href=''>Used count</a></th>
            <th><a href=''>Buy amount</a></th>
            <th><a href=''>Sell amount</a></th>
            <th><a href=''>Lead id</a></th>
            <th><a href=''>Supplier</a></th>
            <th>Option</th>
        </tr>
        </thead>
        <tbody>
		<?php
		foreach ( $stocks as $stock ):
			$charge_type_id = $stock->charge_type;
			$charge_type = "";
			if ( $charge_type_id > 0 ) {
				$entryCategoryObj = FSMSChargeType::findEntryCategory( $stock->charge_type );

				$charge_type = $entryCategoryObj[0]->charge_type;
			}


			?>
            <tr>
                <td><?php echo esc_attr( $stock->id ); ?></a></td>
                <td><?php echo esc_attr( $stock->added_date ); ?></a></td>
                <td><?php echo esc_attr( $charge_type ); ?></a></td>
                <td><?php echo esc_attr( $stock->sku ); ?></td>
                <td><?php echo esc_attr( $stock->description ); ?></td>
                <td><?php echo esc_attr( $stock->added_stock_count ); ?></td>
                <td><?php echo esc_attr( $stock->used_stock_count ); ?></td>
                <td><?php echo esc_attr( $stock->buy_amount ); ?></td>
                <td><?php echo esc_attr( $stock->sell_amount ); ?></td>
                <td><?php echo esc_attr( $stock->lead_id ); ?></td>
                <td><?php echo esc_attr( $stock->user_nicename ); ?></td>
                <td>
                    <a href="admin.php?page=fsms_stocks&action=edit&id=<?php echo esc_attr( $stock->id ); ?>">Edit</a>
                </td>

            </tr>
		<?php endforeach; ?>
		<?php

		$leadPagi = "";
		$pageText = 'Page:';
		if ( $totalCount > $pageCount ) {
			$pageText = 'Page:';
		}
		$leadPagi .= "<tr>";
		$leadPagi .= "<td colspan='12' class='rgt'>&nbsp;" . $pageText;

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
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_stocks&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>1</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_stocks&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>1</a></span>";
			}


			$leadPagi .= "...&nbsp;";
		}

		for ( $i = $startpage; $i <= $endpage; $i ++ ) {
			if ( $page == $i ) {
				$leadPagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=fsms_stocks&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			} else {
				$leadPagi .= "<span class='cPointer' ><a href='admin.php?page=fsms_stocks&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $i . "</a></span>";
			}


		}
		if ( $endpage < $totalPageCount ) {
			$leadPagi .= "...&nbsp;";
			if ( $page == $totalPageCount ) {
				$leadPagi .= "&nbsp;" . $totalPageCount . "</span>";
			} else {
				$leadPagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=fsms_stocks&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "&nonce=" . $filters['nonce'] . "&action=" . $filters['action'] . "&searchKey=" . $filters['searchKey'] . "'>" . $totalPageCount . "</a></span>&nbsp;";
			}

		}

		$leadPagi .= "</td>";
		$leadPagi .= "</tr>";
		echo wp_kses_post( $leadPagi );
		?>
		</tbody>
    </table>
</div>
</div>


