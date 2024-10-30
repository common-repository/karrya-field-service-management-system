<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
<table width="100%" style="">
    <caption>
        Lead list

    </caption>
    <thead>
    <tr>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=id'>ID</a></th>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=name'>Name</a></th>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=dep'>Department</a></th>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=subDep'>Sub Department</a>
        </th>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=owner'>Lead owner</a></th>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=worker'>Lead worker</a></th>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=appDate'>App date</a></th>
        <th><a href='admin.php?page=fsms_work_leads&dir=<?php echo esc_attr( $odir ); ?>&ob=stage'>Stage</a></th>
        <th>Option</th>
    </tr>
    </thead>
    <tbody>
	<?php foreach ( $leads as $lead ): ?>
        <tr>
            <td><?php echo esc_attr( $lead->id ); ?></td>
            <td><?php echo esc_attr( $lead->lead_cus_fname ) . "" . esc_attr( $lead->lead_cus_sname ); ?></td>
            <td><?php echo esc_attr( $lead->departmentName ); ?></td>
            <td><?php echo esc_attr( $lead->subDepartmentName ); ?></td>
            <td><?php echo esc_attr( $lead->user_nicename ); ?></td>
            <td><?php echo esc_attr( $lead->worker ); ?></td>
            <td><?php echo esc_attr( $lead->lead_app_date ); ?></td>
            <td><?php echo esc_attr( $lead->stage_name ); ?></td>
            <td>
                <a href="admin.php?page=fsms_work_leads&action=lead_view&id=<?php echo esc_attr( $lead->id ); ?>">View</a>
                | <a
                        href="admin.php?page=fsms_work_leads&action=lead_sheet&id=<?php echo esc_attr( $lead->id ); ?>">Add
                    lead
                    sheet</a></td>
        </tr>
	<?php endforeach; ?>

	<?php

	$leadPagi = "";
	$pageText = 'Page:';

	if ( $totalLeadsCount > $pageCount ) {
		$pageText = 'Page:';
	}
	$leadPagi .= "<tr>";
	$leadPagi .= "<td colspan='9' class='rgt'>&nbsp;" . $pageText;

	//echo $acPageCount;
	$acPageCount    = ceil( ( $totalLeadsCount / $pageCount ) );
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
			$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_work_leads&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>1</a></span>";
		} else {
			$leadPagi .= "<span class='cPointer'><a href='admin.php?page=fsms_work_leads&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>1</a></span>";
		}


		$leadPagi .= "...&nbsp;";
	}

	for ( $i = $startpage; $i <= $endpage; $i ++ ) {
		if ( $page == $i ) {
			$leadPagi .= "<span class='cPointer' style='font-weight: bold;' ><a href='admin.php?page=fsms_work_leads&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>" . $i . "</a></span>";
		} else {
			$leadPagi .= "<span class='cPointer' ><a href='admin.php?page=fsms_work_leads&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>" . $i . "</a></span>";
		}


	}
	if ( $endpage < $totalPageCount ) {
		$leadPagi .= "...&nbsp;";
		if ( $page == $totalPageCount ) {
			$leadPagi .= "&nbsp;" . $totalPageCount . "</span>";
		} else {
			$leadPagi .= "&nbsp;<span class='cPointer'><a href='admin.php?page=fsms_work_leads&pagei=" . $i . "&dir=" . $filters['dir'] . "&ob=" . $filters['getOb'] . "'>" . $totalPageCount . "</a></span>&nbsp;";
		}

	}

	$leadPagi .= "</td>";
	$leadPagi .= "</tr>";
	echo wp_kses_post( $leadPagi );
	?>
    </tbody>
</table>
</div>

