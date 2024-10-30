<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
} // Exit if accessed directly
?>
<div class="fsms_div">
<div class="wrap">
    <div>
 
<div class="fsms_notification"> 
<div class="headerTxt">Please read and set all the setting below</div>
<p>
     
1. You can use these parameters ((lead_id))
((customer_name))((customer_email)) ((customer_phone)) ((department)) ((sub_department))
((customer_address)) ((customer_city)) ((customer_postcode)) ((lead_worker)) ((lead_owner))
((lead_stage))

</p>
<p>
    Before use the system please setup following steps
    <ul>
        <ol>Add departments </ol>
        <ol>Add sub departments </ol>
        <ol>Add user roles (lead owner / lead worker / lead supplier) </ol>
        <ol>Add stock </ol>
        <ol>Add charge type (Ex: matiral / parking .. ) </ol>
        <ol>Add stages </ol>
    </ul>
</p>
<p>
</p>
</div>
    </div>

    <form action="options.php" method="post">


		<?php

		settings_fields( 'fsms-settings' );

		do_settings_sections( 'fsms-settings' );

		?>

        <table>
            <caption>
                <div>Settings</div>
            </caption>
            <tr>
                <td width="25%">Company address</td>
                <td>
                    <textarea name="fsms_company_address" rows="5"
                              cols="55"><?php echo esc_attr( get_option( 'fsms_company_address' ) ); ?></textarea>
                </td>
            </tr>

            <tr>
                <td>VAT</td>
                <td><input style="width:150px;" type="number" placeholder="vat" name="fsms_vat"
                           value="<?php echo esc_attr( get_option( 'fsms_vat' ) ); ?>"/></td>

            </tr>

            <tr>
                <td>Currency symbol</td>
                <td><input style="width:50px;" type="text" placeholder="&pound;" name="fsms_currency_symbol"
                           value="<?php echo esc_attr( get_option( 'fsms_currency_symbol' ) ); ?>"/></td>

            </tr>
             
            <tr>
                <td>Number of rows</td>
                <td><input style="width:50px;" type="text" placeholder="25" name="fsms_no_of_rows"
                           value="<?php echo esc_attr( get_option( 'fsms_no_of_rows' ) ); ?>"/></td>

            </tr>
            <tr>
                <td>From Email address</td>
                <td><input type="text" placeholder="from@yourcompany.com" name="from_email_address"
                           value="<?php echo esc_attr( get_option( 'from_email_address' ) ); ?>"/>

                </td>

            </tr>
            <tr>
                <td>Welcome Email subject

                </td>
                <td><input type="text" placeholder="Thanks you" name="thanks_email_subject"
                           value="<?php echo esc_attr( get_option( 'thanks_email_subject' ) ); ?>"/>

                </td>

            </tr>
            <tr>
                <td>Welcome Email body
                   
                </td>
                <td> <?php wp_editor( stripslashes( wp_kses_post( ( get_option( 'thanks_email' ) ) ) ), 'thanks_email' ); ?>

                </td>

            </tr>
            <tr>
                <td>Email header</td>
                <td> <?php wp_editor( wp_kses_post( stripslashes( ( get_option( 'email_header' ) ) ) ), 'email_header' ); ?>

                </td>

            </tr>
            <tr>
                <td>Email footer</td>
                <td> <?php wp_editor( ( ( wp_kses_post( get_option( 'email_footer' ) ) ) ), 'email_footer' ); ?>

                </td>

            </tr>
            <tr>
                <td>Invoice top</td>
                <td> <?php wp_editor( ( ( wp_kses_post( get_option( 'invoice_top_setting' ) ) ) ), 'invoice_top_setting' ); ?>

                </td>

            </tr>
            <tr>
                <td>Invoice bottom</td>
                <td> <?php wp_editor( ( ( wp_kses_post( get_option( 'invoice_bottom_setting' ) ) ) ), 'invoice_bottom_setting' ); ?>

                </td>

            </tr>
            <tr>
                <td>Quote top</td>
                <td> <?php wp_editor( ( ( wp_kses_post( get_option( 'quote_top_setting' ) ) ) ), 'quote_top_setting' ); ?>

                </td>

            </tr>
            <tr>
                <td>Quote bottom</td>
                <td> <?php wp_editor( ( ( wp_kses_post( get_option( 'quote_bottom_setting' ) ) ) ), 'quote_bottom_setting' ); ?>

                </td>

            </tr>
            <tr>
                <td></td>
                <td><?php submit_button(); ?></td>
            </tr>
        </table>
    </form>
</div>
</div>
