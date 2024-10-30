<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<?php
$nonce = wp_create_nonce( 'fsms_lead_add_edit' );
?>
<div class="fsms_div">
    <div class="headerLinksDiv">
        <div class="headerLinkDiv">
            <a href="admin.php?page=fsms_leads&action=lead_view&id=<?php echo esc_attr( $id ); ?>">View</a>
        </div>
    </div>
    <form class='fsms-form' method="post">
        <table>
            <caption>Add new lead</caption>
            <thead></thead>
            <tbody>
            <tr>
                <td width="25%">Department</td>
                <td>
                    <select name="lead_dep_id" id="lead_dep_id" onchange="departmentsList(this.value,0);">
                        <option>Please select</option>
						<?php foreach ( $departments as $department ):
							$selected = "";
							if ( $lead->lead_dep_id == $department->id ) {
								$selected = "selected";
							}
							?>
                            <option <?php echo esc_attr( $selected ); ?>
                                    value="<?php echo esc_attr( $department->id ); ?>"><?php echo esc_attr( $department->department_name ); ?></option>
						<?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sub department</td>
                <td>
                    <select name="lead_sub_dep_id" id="lead_sub_dep_id">
                        <option>Please select</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Customer email</td>
                <td><input class="form-control" type="text" name="lead_email"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_email ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Customer first name</td>
                <td><input class="form-control" type="text" name="lead_cus_fname"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_cus_fname ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Customer surname</td>
                <td><input class="form-control" type="text" name="lead_cus_sname"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_cus_sname ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Customer phone</td>
                <td><input class="form-control" type="text" name="lead_cus_phone"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_cus_phone ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Address line one</td>
                <td><input class="form-control" type="text" name="lead_cus_address"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_cus_address ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Customer city</td>
                <td><input class="form-control" type="text" name="lead_cus_city"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_cus_city ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Customer postcode</td>
                <td><input class="form-control" type="text" name="lead_cus_postcode"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_cus_postcode ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Appoinment date</td>
                <td><input class="form-control" type="text" id="lead_app_date" name="lead_app_date"
                           value="<?php echo stripslashes( @esc_attr( $lead->lead_app_date ) ); ?>" size="150"></td>
            </tr>
            <tr>
                <td>Internal details</td>
                <td>
					<?php wp_editor( stripslashes( @wp_kses_post( $lead->lead_details ) ), 'lead_details' ); ?>
                </td>
            </tr>
            <tr>
                <td>Job owner</td>
                <td>

                    <select name="lead_owner">
                        <option>Please select</option>
						<?php foreach ( $lead_owners as $user ):
							//print_r($user);
							$selectedOwner = "";
							if ( $lead->lead_owner == $user->ID ) {
								$selectedOwner = "selected";
							}
							?>
                            <option <?php echo esc_attr( $selectedOwner ); ?>
                                    value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_attr( $user->user_nicename ); ?></option>
						<?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Job worker</td>
                <td>
                    <select name="lead_worker">
                        <option>Please select</option>
						<?php
						foreach ( $lead_workers as $user ):
							$selectedOwner = "";
							if ( $lead->lead_worker == $user->ID ) {
								$selectedOwner = "selected";
							}
							?>
                            <option <?php echo esc_attr( $selectedOwner ); ?>
                                    value="<?php echo esc_attr( $user->ID ); ?>"><?php echo esc_attr( $user->user_nicename ); ?></option>
						<?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Job stage</td>
                <td>
                    <select name="lead_stage">
                        <option>Please select</option>
						<?php
						$selectedStage = "";
						foreach ( $stages as $stage ):
							if ( $lead->lead_stage == $stage->id ) {
								$selectedStage = "selected";
							}
							?>
                            <option <?php echo esc_attr( $selectedStage ); ?>
                                    value="<?php echo esc_attr( $stage->id ); ?>"><?php echo esc_attr( $stage->stage_name ); ?></option>
						<?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Invoice top
                     
                </td>
                <td>
                    <?php 
                    $invoice_top_blcok = @$lead->invoice_top_blcok;
                    if(empty($invoice_top_blcok)){
                        $invoice_top_blcok = ( ( get_option( 'invoice_top_setting' ) ) );
                    }
                    wp_editor( stripslashes( @wp_kses_post( $invoice_top_blcok ) ), 'invoice_top_blcok' );
                     ?>
                        
                    </td>
            </tr>
            <tr>
                <td>Invoice bottom
                     
                </td>
                <td>
                    <?php 
                     $invoice_bottom_blcok = @$lead->invoice_bottom_blcok;
                    if(empty($invoice_bottom_blcok)){
                        $invoice_bottom_blcok = ( ( get_option( 'invoice_bottom_setting' ) ) );
                    }
                    wp_editor( stripslashes( @wp_kses_post( $invoice_bottom_blcok ) ), 'invoice_bottom_blcok' ); 
                    ?>
                        
                    </td>
            </tr>
            <tr>
                <td>Quote top
                     
                </td>
                <td>
                    <?php 
                    $quote_top_blcok = @$lead->quote_top_blcok;
                    if(empty($quote_top_blcok)){
                        $quote_top_blcok = ( ( get_option( 'quote_top_setting' ) ) );
                    }
                    wp_editor( stripslashes( @wp_kses_post( $quote_top_blcok ) ), 'quote_top_blcok' ); 
                    ?>
                        
                    </td>
            </tr>
            <tr>
                <td>Quote bottom
                    
                </td>
                <td>
                <?php 
                $quote_bottom_blcok = @$lead->quote_bottom_blcok;
                if(empty($quote_bottom_blcok)){
                    $quote_bottom_blcok = ( ( get_option( 'quote_bottom_setting' ) ) );
                }
                wp_editor( stripslashes( @wp_kses_post( $quote_bottom_blcok  ) ), 'quote_bottom_blcok' ); 
                ?>
            </td>
            </tr>

            <tr>
                <td></td>
                <td colspan="">
                    <input type="submit" value="Save">
</div>
<input type="hidden" name="ok" value="1">
<input name="nonce" type="hidden" value="<?php echo $nonce; ?>">
</td>
</tr>
</tbody>

</table>
</form>
</div>