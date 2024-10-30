jQuery(document).ready(function ($) {

    $('#addCostNew,#addPaymentNew').on('click', function (e) {
        e.preventDefault();

        $("#payment_type").val($(this).text());
        $("#addChargeForm").show();
    });


    // add and view
    $('#add_charge').on('click', function (e) {

        e.preventDefault();

        var title = $('#st-title').val();
        var content = $('#st-content').val();
        var nonce = $('#st_sticky_note_create_nonce_field').val();

        if (1 == 1) {

        }
    });


// send invoice
    $('#send_invoice').on('click', function (e) {

        e.preventDefault();
        $("#send_invoice").hide();
        $(".loader").show();

        if (1 == 1) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: 'send_invoice_to_customer',
                    lead_id: fsms_js_vars.id,
                    nonce: $("#nonce").val(),
                    ok: $("#ok").val(),
                    invoiceDivContent: $("#invoiceDiv").html(),
                    send_cc_email: $("#send_cc_email").val(),
                    send_to_email: $("#send_to_email").val(),
                    send_to_email_subject: $("#send_to_email_subject").val(),


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("The following error occured: " + textStatus, errorThrown);
                },
                success: function (response) {

                    //alert(response)
                    $(".invoiceSendBlock").html(response);

                }

            });
        }
    });

    // send quote
    $('#send_quote').on('click', function (e) {

        e.preventDefault();
        $("#send_quote").hide();
        $(".loader").show();

        if (1 == 1) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: ajaxurl,
                data: {
                    action: 'send_quote_to_customer',
                    lead_id: fsms_js_vars.id,
                    nonce: $("#nonce").val(),
                    ok: $("#ok").val(),
                    invoiceDivContent: $("#invoiceDiv").html(),
                    send_cc_email: $("#send_cc_email").val(),
                    send_to_email: $("#send_to_email").val(),
                    send_to_email_subject: $("#send_to_email_subject").val(),


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("The following error occured: " + textStatus, errorThrown);
                },
                success: function (response) {

                    //alert(response)
                    $(".invoiceSendBlock").html(response);

                }

            });
        }
    });

});

jQuery(document).ready(function () {
    jQuery('#lead_app_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});



 

           
    