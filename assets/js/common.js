function showWorkerMessageBox() {
    jQuery("#owrkerMessage").show();
}

function showOwnerMessageBox() {
    jQuery("#ownerMessage").show();
}

function cancelOwnerMessageBox() {
    jQuery("#ownerMessage").hide();
}

function cancelWorkerMessageBox() {
    jQuery("#owrkerMessage").hide();
}

function messageReplyOwner(id) {
    jQuery("#ownerMessage").show();
    jQuery(".message_parent_id").val(id);
}

function messageReplyWorker(id) {
    jQuery("#owrkerMessage").show();
    jQuery(".message_parent_id").val(id);
}

function leadBook(frm) {
    var error = 0;
    var errorMessage = "";
    if (frm.lead_email.value == "") {
        error++;
        errorMessage += "Please add email address \n";
    }
    if (frm.lead_cus_phone.value == "") {
        error++;
        errorMessage += "Please add contact number \n";
    }
    if (error > 0) {
        alert(errorMessage);
        return false;
    }
    jQuery("#lead_details").val(jQuery("#tinymce").html());
    jQuery(".loader").show();
    jQuery("#bookbtn").hide();
    var myformData = new FormData(frm);
    //var form_data = jQuery(frm).serialize();
    /*jQuery.post(
        fsms_i18n.ajax_url, 
        form_data, 
        function(msg) {
         alert(msg);
    }); */

    jQuery.ajax({
        type: "POST",
        data: myformData,
        dataType: "json",
        url: fsms_i18n.ajax_url,
        cache: false,
        processData: false,
        contentType: false,
        enctype: 'multipart/form-data',
        success: function (data, textStatus, jqXHR) {
            console.log(data);
            jQuery('html, body').animate({
                scrollTop: jQuery(".frontEndLeadDiv").offset().top
            }, 100);
            jQuery(".loader").hide();
            jQuery(".frontEndLeadDiv").html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //if fails
            console.log(jqXHR);
        }
    });
}


function loadChargeBlock() {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'view_charge_block',
            lead_id: fsms_js_vars.id,

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            jQuery("#charge_block").html(response.charge);

        }

    });
}

function loadCostBlock() {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'view_cost_block',
            lead_id: fsms_js_vars.id,

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            jQuery("#cost_block").html(response.cost);

        }

    });
}

function loadPaymentBlock() {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'view_payment_block',
            lead_id: fsms_js_vars.id,

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            jQuery("#payment_block").html(response.payment);

        }

    });
}

function departmentsList(id, sub_dep_id) {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: fsms_js_vars.ajaxurl,
        data: {
            action: 'list_subdepartment',
            dep_id: id,
            sub_dep_id: sub_dep_id,

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            jQuery("#lead_sub_dep_id").html(response);

        }

    });
}

function departmentsListFrontEnd(id, sub_dep_id) {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: fsms_js_vars.ajaxurl,
        data: {
            action: 'list_front_end_subdepartment',
            dep_id: id,
            sub_dep_id: sub_dep_id,

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            jQuery("#lead_sub_dep_id").html(response);

        }

    });
}

function showChargeAddEditBlock() {
    jQuery("#addChargeForm").show();
}

function showCostAddEditBlock() {
    jQuery("#addCostForm").show();
}

function showPaymentAddEditBlock() {
    jQuery("#addPaymentForm").show();
}

function addCharge() {
    var is_publish_to_quote = jQuery("#is_publish_to_quote").val();


    var is_publish_to_invoice = jQuery("#is_publish_to_invoice").val();

    var is_add_to_quote = 0;
    if (jQuery("#is_add_to_quote").is(":checked")) {
        is_add_to_quote = 1;
    }
    var is_add_to_invoice = 0;
    if (jQuery("#is_add_to_invoice").is(":checked")) {
        is_add_to_invoice = 1;
    }
    var is_deduct_from_stock = 0;
    if (jQuery("#is_deduct_from_stock").is(":checked")) {
        is_deduct_from_stock = 1;
    }
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'charge_insert',
            lead_id: fsms_js_vars.id,
            charge_amount: jQuery('#charge_amount').val(),
            charge_description: jQuery('#charge_description').val(),

            charge_type: jQuery('#charge_type').val(),
            display_order: jQuery('#display_order').val(),
            is_publish_to_quote: is_publish_to_quote,
            is_publish_to_invoice: is_publish_to_invoice,
            is_add_to_quote: is_add_to_quote,
            is_add_to_invoice: is_add_to_invoice,
            is_deduct_from_stock: is_deduct_from_stock,
            vat: jQuery('#vat').val(),
            sku: jQuery('#sku').val(),
            qty: jQuery('#qty').val(),
            supplier_id: jQuery('#supplier_id').val(),

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            loadChargeBlock();
            jQuery("#addChargeForm").hide();

        }

    });
}

function addCost() {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'cost_insert',
            lead_id: fsms_js_vars.id,
            cost_amount: jQuery('#cost_amount').val(),
            cost_description: jQuery('#cost_description').val(),

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            loadCostBlock();
            jQuery("#addCostForm").hide();

        }

    });
}

function addPayment() {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'payment_insert',

            lead_id: fsms_js_vars.id,
            payment_amount: jQuery('#payment_amount').val(),
            payment_description: jQuery('#payment_description').val(),

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("The following error occured: " + textStatus, errorThrown);
        },
        success: function (response) {

            loadPaymentBlock();
            jQuery("#addPaymentForm").hide();

        }

    });
}

function cancelCharge() {
    jQuery("#addChargeForm").hide();
}

function cancelCost() {
    jQuery("#addCostForm").hide();
}

function cancelPayment() {
    jQuery("#addPaymentForm").hide();
}

function sendInvoice() {

}

function deleteEntry(id, entryType) {
    if (confirm("Are you sure to delete?")) {
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: ajaxurl,
            data: {
                action: 'payment_delete',
                id: id

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("The following error occured: " + textStatus, errorThrown);
            },
            success: function (response) {
                if (entryType == 1) {
                    loadChargeBlock();
                }
                if (entryType == 2) {
                    loadPaymentBlock();
                }
                if (entryType == 3) {
                    loadCostBlock();
                }

                jQuery("#entry_" + id).hide();

            }

        });
    }
}

jQuery(document).ready(function () {
    jQuery('#fromDate,#toDate').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
function searchSku(){
        if(jQuery("#sku").val().length < 4 ){
            return;
        }
        jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'search_sku', 
            sku: jQuery("#sku").val(), 
            nonceVal: fsms_js_vars.nonceVal,            
             

        },
         
        beforeSend: function(){
            jQuery("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
            jQuery("#suggesstion-box").show();
            jQuery("#suggesstion-box").html(data);
            jQuery("#search-box").css("background","#FFF");
        }
        });
    }
function setSkuVal(stock_id){

        jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            action: 'get_sku_details', 
            stock_id: stock_id, 
            nonceVal: fsms_js_vars.nonceVal,            
             

        },
         
        beforeSend: function(){
            //jQuery("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
            jQuery("#suggesstion-box").hide();
            jQuery("#charge_description").val(data.description);
            jQuery("#charge_amount").val(data.charge_amount);
            jQuery("#charge_type").val(data.charge_type);
            jQuery("#sell_amount").val(data.charge_amount);
            jQuery("#buy_amount").val(data.buy_amount); 
            jQuery("#supplier_id").val(data.supplier_id);
            jQuery("#sku").val(data.sku);   
            jQuery("#description").val(data.description);

        }
        });

    }
