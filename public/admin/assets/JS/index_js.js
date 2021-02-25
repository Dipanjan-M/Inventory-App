/*
Function check_stock(){ code... }
Parameters : none;
Description : Checks the products table  in database
and report if stock value of any product is under 10 unit.
Sample call :  check_stock();
*/
function check_stock() {
    $.get("get_low_stocks.php", function(data, status) {
        var msgs = JSON.parse(data);
        if (msgs.length > 0) {
            document.getElementById('low-stock').style.display = "block";
        } else {
            document.getElementById('low-stock').style.display = "none";
        }
    });
}

/*
setInterval function to check stock after every 30sec
*/
setInterval(check_stock, 30000);


/*
Click event handler for button with id 'total-calc-btn'
Description: Calculate the total value of the bill including discount
*/
$(document).on('click', '#total-calc-btn', function(e) {
    e.preventDefault();
    var total_sale = 0;
    var total_buy = 0;
    $('#all-orders > .each-orders').each(function(key, value) {
        var nums = [];
        var selector_id = value.id;
        var strs = selector_id.split('-');
        var idn = parseInt(strs[strs.length - 1]);
        $('#' + value.id + ' *').filter(':input').each(function(key, val) {
            if (val.type == "number" && val.name == "order[product][" + idn + "][quantity]") {
                nums['quantity'] = val.value;
            }
            if (val.type == "number" && val.name == "order[product][" + idn + "][unit_price]") {
                nums['unit_price'] = val.value;
            }
            if (val.type == "number" && val.name == "order[product][" + idn + "][main_price]") {
                nums['main_price'] = val.value;
            }
        });
        total_sale += ( parseInt(nums['quantity']) * parseInt(nums['unit_price']) );
        total_buy  += ( parseInt(nums['quantity']) * parseInt(nums['main_price']) );
    });
    $('#discount-inp').attr("max", (total_sale - total_buy).toFixed(2));
    var disc = $('#discount-inp').val();
    var discount = (disc == '') ? 0.00 : parseFloat(disc);
    $('#total-show').html('<small>Maximum allowed discount = '+ (total_sale - total_buy).toFixed(2) +'</small><br>Total = <i class="fas fa-rupee-sign"></i> ' + parseFloat(total_sale).toFixed(2) + '<br>Discount = <i class="fas fa-rupee-sign"></i> ' + parseFloat(discount).toFixed(2) + '<br>Grand Total = <i class="fas fa-rupee-sign"></i> ' + parseFloat(total_sale - discount).toFixed(2));
});


/*
Event handler for order form submit
*/
$('#create-order').submit(function(e) {
    e.preventDefault();
    $('#btn-place-order').attr("disabled", "true");
    $('#server_is_busy').css('display', 'block');
    if (!$('#all-orders').is(':empty')) {
        $.ajax({
            url: $('#create-order').attr("action"),
            method: "post",
            data: $('#create-order :input').serializeArray(),
            dataType: "text",
            success: function(data) {
                try {
                    var msgs = JSON.parse(data);
                    $('#create-order').trigger("reset");
                    $('#total-show').html('');
                    $('#btn-place-order').removeAttr("disabled");
                    $('.create-order').css('display', 'none');
                    generate_bill_to_print(msgs);
                } catch (e) {
                    $('#status-area').html(data);
                    $('#btn-place-order').removeAttr("disabled");
                }
                $('#server_is_busy').css('display', 'none');
            },
            error: function(err) {
                alert(err);
                $('#server_is_busy').css('display', 'none');
                $('#btn-place-order').removeAttr("disabled");
            }
        });
    } else {
        alert("Please add some product to make an order.");
        $('#server_is_busy').css('display', 'none');
        $('#btn-place-order').removeAttr("disabled");
    }

});


/*
Function toggle_collapse_cast_details() { code... }
Arguments : none;
Reference call : toggle_collapse_cast_details();
Description : Toggle the display property of customer details section
in order form
*/
function toggle_collapse_cast_details() {
    if ($('#cust-details').css('display') == '' || $('#cust-details').css('display') == 'block') {
        $('#cust-details').css('display', 'none');
        document.getElementById('collapse-cust').classList.replace('fa-angle-up', 'fa-angle-down');
    } else {
        $('#cust-details').css('display', 'block');
        document.getElementById('collapse-cust').classList.replace('fa-angle-down', 'fa-angle-up');
    }
}



/*
Function : generate_bill_to_print(data_arg) { code... }
Argument : Take a confirmed order details as argumnet.
Reference call : generate_bill_to_print(arg);
Description : Generate formatted taxed invoice and normal invoice to print
*/
function generate_bill_to_print(data) {
    var discount = parseFloat(data['billing_customer']['discount']);
    var taxed_table =  `<table border="1" width="100%">
                            <tr align="center">
                                <th width="50%">Particulars</th>
                                <th>Unit Price</th>
                                <th>Qty.</th>
                                <th>CGST</th>
                                <th>SGST</th>
                                <th>Total</th>
                            </tr>`;
    var normal_table = `<table border="1" width="100%">
                            <tr align="center">
                                <th width="60%">Particulars</th>
                                <th>Unit Price</th>
                                <th>Qty.</th>
                                <th>Total</th>
                            </tr>`;
    var grand_total = 0;
    data['bill_details'].forEach((bill) => {
        var item_name = bill['p_name'];
        var item_unit_price = parseFloat(bill['unit_price']);
        var item_quantity = parseInt(bill['quantity']);
        var item_tax = parseFloat(bill['tax']);
        var item_cost_price = (item_unit_price * 100) / (100 + item_tax);
        var item_total_price = item_unit_price * item_quantity;
        var total_cost_price = item_cost_price * item_quantity;
        var tax_payed = item_total_price - total_cost_price;
        var cgst_or_sgst = tax_payed/2;
        taxed_table += `<tr align="center">
                            <td width="50%">` + item_name + `</td>
                            <td>` + item_cost_price.toFixed(2) + `</td>
                            <td>` + item_quantity + `</td>
                            <td> @`+ (item_tax/2).toFixed(2) + `%&nbsp;&nbsp;<i class="fas fa-rupee-sign"></i>` + cgst_or_sgst.toFixed(2) + `</td>
                                    <td> @`+ (item_tax/2).toFixed(2) + `%&nbsp;&nbsp;<i class="fas fa-rupee-sign"></i>` + cgst_or_sgst.toFixed(2) + `</td>
                            <td align="right" style="padding-right: .5vw;">` + item_total_price.toFixed(2) + `</td>
                        </tr>`;
        normal_table += `<tr align="center">
                            <td width="60%">` + item_name + `</td>
                            <td>` + item_unit_price.toFixed(2) + `</td>
                            <td>` + item_quantity + `</td>
                            <td align="right" style="padding-right: .5vw;">` + item_total_price.toFixed(2) + `</td>
                        </tr>`;
        grand_total += item_total_price;
    });
    taxed_table += `<tr align="center">
                        <td colspan="5"><strong>Grand Total</strong></td>
                        <td align="right" style="padding-right: .5vw;">` + grand_total.toFixed(2) + `<br>(-) ` + discount.toFixed(2) + `
                        <br><strong>` + (grand_total - discount).toFixed(2) + `</strong><br>(rounded)</td>
                    </tr>
                    </table>`;
    normal_table += `<tr align="center">
                        <td colspan="3"><strong>Grand Total</strong></td>
                        <td align="right" style="padding-right: .5vw;">` + grand_total.toFixed(2) + `<br>(-) ` + discount.toFixed(2) + `
                        <br><strong>` + (grand_total - discount).toFixed(2) + `</strong><br>(rounded)</td>
                    </tr>
                    </table>`;

    var the_html = `<div id="final-bill-head">
                        <div class="row">
                            <div class="col-6">
                                <h4>Payment Receipt</h4>
                            </div>
                            <div class="col-6" style="text-align: right;padding-right: 3vw;padding-top: 1vw;">
                                <i class="fas fa-times text-danger" style="cursor: pointer;" onclick="close_final_bill();"></i>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 text-center">
                                <button class="btn btn-primary" onclick="print_bill('tax-bill','without-tax-bill');">Print Tax invoice</button>
                            </div>
                            <div class="col-6 text-center">
                                <button class="btn btn-primary" onclick="print_bill('without-tax-bill','tax-bill');">Print invoice</button>
                            </div>
                        </div><br>
                    </div>
                    <div class="row" id="bill">
                        <div class="col" id="tax-bill">
                            <div class="p-3 border">
                                <div class="text-center">
                                    <h4>Piya Motors</h4>
                                    <h6><span class="border"> &nbsp; TAX Invoice &nbsp; </span></h6>
                                    <p>
                                        Nimtala, Ranapur, Daspur - 721212<br>
                                        Paschim Mednipur, West Bengal, India<br>
                                        Mobile No - +91-9800619198 / 7872707955<br>
                                        Email - piyamotor.yamaha@gmail.com
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <h5>` + data['billing_customer']['name'] + `</h5>
                                        <p>` + data['billing_customer']['address'] + `</p>
                                    </div>
                                    <div class="col-sm pt-3 text-right">
                                        <p>
                                            Mobile : +91-` + data['billing_customer']['phone'] + ` <br>
                                            E-mail : ` + data['billing_customer']['email'] + `
                                        </p>
                                        <strong>Date : ` + data['billing_customer']['date'] + `</strong>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p>Bill No. - <u><b>` + data['billing_customer']['bill_id'] + `</b></u></p>
                                </div><hr>
                                <div class="row p-3">` + taxed_table + `</div>
                            </div>
                        </div><br>
                        <div class="col" id="without-tax-bill">
                            <div class="p-3 border">
                                <div class="text-center">
                                    <h4>Piya Motors</h4>
                                    <h6><span class="border"> &nbsp; Invoice &nbsp; </span></h6>
                                    <p>
                                        Nimtala, Ranapur, Daspur - 721212<br>
                                        Paschim Mednipur, West Bengal, India<br>
                                        Mobile No - +91-9800619198 / 7872707955<br>
                                        Email - piyamotor.yamaha@gmail.com
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <h5>` + data['billing_customer']['name'] + `</h5>
                                        <p>` + data['billing_customer']['address'] + `</p>
                                    </div>
                                    <div class="col-sm pt-3 text-right">
                                        <p>
                                            Mobile : +91-` + data['billing_customer']['phone'] + ` <br>
                                            E-mail : ` + data['billing_customer']['email'] + `
                                        </p>
                                        <strong>Date : ` + data['billing_customer']['date'] + `</strong>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p>Bill No. - <u><b>` + data['billing_customer']['bill_id'] + `</b></u></p>
                                </div><hr>
                                <div class="row p-3">` + normal_table + `</div>
                            </div>
                        </div>
                    </div>`;
    $('.final-bill').css("display", "block");
    $('.final-bill').html(the_html);
}


/*
Function : close_final_bill() { code... }
Description : To close final bill view
*/
function close_final_bill() {
    $('#tax-bill').empty();
    $('#without-tax-bill').empty();
    $('.final-bill').css('display', 'none');
}


/*
Function : print_bill(arg1, arg2) { code... }
Description : Take two id as argument one for printing 
and one for hiding
*/
function print_bill(x, y) {
    var the_div = document.getElementById(x);
    var not_the_div = document.getElementById(y);
    document.getElementById('final-bill-head').style.display = "none";
    not_the_div.style.display = "none";
    document.body.style.visibility = "hidden";
    the_div.style.visibility = "visible";
    window.print();
    document.getElementById('final-bill-head').style.display = "block";
    not_the_div.style.display = "block";
    document.body.style.visibility = "visible";
}

$('#new-order').click(function() {
    $('#all-orders').empty();
    $('#total-show').empty();
    $('.create-order').css("display", "block");
});

$('#search-input').keyup(function() {
    $('#server_is_busy').css('display', 'block');
    var key = $('#search-input').text();
    $.ajax({
        url: "search_product.php",
        method: "post",
        data: { search_key: key },
        dataType: "text",
        success: function(data) {
            try {
                var elem = document.getElementById('available-products');
                var res_str = '';
                res_str += `<table border="1" align="center" id="available-products-tbl" style="font-size: 14px;">
                                <tr align="center">
                                    <th>Name</th>
                                    <th>Tax %</th>
                                    <th>
                                        Unit Price
                                        <table align="center" width="100%">
                                            <tr align="center">
                                                <td width="50%">Customer</td>
                                                <td width="50%">Vendor</td>
                                            </tr>
                                        </table>
                                    </th>
                                    <th>In stock</th>
                                    <th>
                                        Action
                                        <table align="center" width="100%">
                                            <tr align="center">
                                                <td width="50%">Customer</td>
                                                <td width="50%">Vendor</td>
                                            </tr>
                                        </table>
                                    </th>
                                </tr>`;
                var msgs = JSON.parse(data);
                var counter = 0;
                msgs.forEach((msg) => {
                    counter++;
                    res_str += `<tr align="center" id="query-row-` + counter + `">
                                    <td>` + msg['p_name'] + `</td>
                                    <td>` + parseFloat(msg['gst_percentage']).toFixed(2) + `</td>
                                    <td>
                                        <table width="100%">
                                            <tr align="center">
                                                <td width="50%">
                                                    `+parseFloat(msg['unit_price']).toFixed(2)+`
                                                </td>
                                                <td width="50%">
                                                    `+parseFloat(msg['vendor_price']).toFixed(2)+`
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>` + msg['total_stock'] + `</td>
                                    <td>
                                        <table width="100%">
                                            <tr align="center">
                                                <td width="50%">
                                                    <i class="fas fa-cart-plus text-primary" style="cursor: pointer;" onclick="add_to_order('` + msg['p_name'] + `','` + msg['main_price'] + `','` + msg['gst_percentage'] + `','` + msg['unit_price'] + `','` + msg['total_stock'] + `','query-row-` + counter + `');"></i>
                                                </td>
                                                <td width="50%">
                                                    <i class="fas fa-luggage-cart text-primary" style="cursor: pointer;" onclick="add_to_order('` + msg['p_name'] + `','` + msg['main_price'] + `','` + msg['gst_percentage'] + `','` + msg['vendor_price'] + `','` + msg['total_stock'] + `','query-row-` + counter + `');"></i>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>`;
                });
                res_str += `</table>`;
                $('#available-products').html(res_str);
            } catch (e) {
                $('#available-products').html(data);
            }
            $('#server_is_busy').css('display', 'none');
        },
        error: function(err) {
            alert(err);
            $('#server_is_busy').css('display', 'none');
        }
    });
});

function add_to_order(name, buying_price, tax, unit_price, in_stock, tr_id) {
    var elem = document.getElementById('all-orders');
    var next_id = 0;
    if(!$('#all-orders').is(':empty')) {
        $("#all-orders > .each-orders").each(function(key, value) {
            var id_str = value.id;
            var strs = id_str.split('-');
            var current_id = parseInt(strs[strs.length - 1]);
            next_id = next_id > current_id ? next_id : current_id;
        });
    }
    var product_no = next_id + 1;
    $('#all-orders').append(`<div class="each-orders" id="each-orders-` + product_no + `">
                                <div class="row">
                                    <div class="col">
                                        <label for="order">Order ` + product_no + `</label>
                                    </div>
                                    <div class="col" style="text-align: right;">
                                        Delete <i class="fas fa-minus-circle text-danger" style="cursor: pointer;" onclick="$('#each-orders-` + product_no + `').remove();"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="product-name">Product name</label>
                                        <input type="text" name="order[product][` + product_no + `][name]" class="form-control" required="" readonly value="` + name + `">
                                    </div>
                                    <div class="col">
                                        <label for="quantity">Enter quantity (Max : ` + in_stock + `)</label>
                                        <input type="number" name="order[product][` + product_no + `][quantity]" class="form-control" min="1" max="` + in_stock + `" step="1" required="">
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" name="order[product][` + product_no + `][main_price]" readonly="" value="` + parseFloat(buying_price).toFixed(2) + `" required="" style="display: none;">
                                        <label for="price">Price per unit (in rupees)</label>
                                        <input type="number" name="order[product][` + product_no + `][unit_price]" min="0" step="0.01" class="form-control" readonly="" value="` + parseFloat(unit_price).toFixed(2) + `" required="">
                                    </div>
                                    <div class="col">
                                        <label for="tax">Tax applicable (%)</label>
                                        <input type="number" name="order[product][` + product_no + `][tax]" min="0" step="0.01" class="form-control" value="` + parseFloat(tax).toFixed(2) + `" readonly="" required="">
                                    </div>
                                </div>
                            </div><br>`);
    $('#' + tr_id).remove();
}

function add_manual_order() {
    var elem = document.getElementById('all-orders');
    var next_id = 0;
    if(!$('#all-orders').is(':empty')) {
        $("#all-orders > .each-orders").each(function(key, value) {
            var id_str = value.id;
            var strs = id_str.split('-');
            var current_id = parseInt(strs[strs.length - 1]);
            next_id = next_id > current_id ? next_id : current_id;
        });
    }
    var product_no = next_id + 1;
    $('#all-orders').append(`<div class="each-orders" id="each-orders-` + product_no + `">
                                <div class="row">
                                    <div class="col">
                                        <label for="order">Order ` + product_no + `</label>
                                    </div>
                                    <div class="col" style="text-align: right;">
                                        Delete <i class="fas fa-minus-circle text-danger" style="cursor: pointer;" onclick="$('#each-orders-` + product_no + `').remove();"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="product-name">Product name <sup class="text-danger">*</sup></label>
                                        <input type="text" name="order[product][` + product_no + `][name]" class="form-control" required="">
                                    </div>
                                    <div class="col-3">
                                        <label for="quantity">Enter quantity <sup class="text-danger">*</sup></label>
                                        <input type="number" name="order[product][` + product_no + `][quantity]" class="form-control" min="1" step="1" required="">
                                    </div>
                                    <div class="col-3">
                                        <label for="tax">Tax if applicable (%) </label>
                                        <input type="number" name="order[product][` + product_no + `][tax]" min="0" step="0.01" class="form-control">
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col">
                                        <label for="price">Buying price per unit (in rupees) <sup class="text-danger">*</sup></label>
                                        <input type="number" name="order[product][` + product_no + `][main_price]" min="0" step="0.01" class="form-control" required="">
                                    </div>
                                    <div class="col">
                                        <label for="price">Selling price per unit (in rupees) <sup class="text-danger">*</sup></label>
                                        <input type="number" name="order[product][` + product_no + `][unit_price]" min="0" step="0.01" class="form-control" required="">
                                    </div>
                                </div>
                            </div><br>`);
}

function get_last_7ds_analytics() {
    $.get("last_7_days_sale.php", function(data, status) {
        var msgs = JSON.parse(data);
        // console.log(msgs);
        var ctx1 = document.getElementById('myChart1').getContext('2d');
        var myChart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: msgs['labels'],
                datasets: [{
                    label: 'Sales Per Day',
                    data: msgs['data'],
                    backgroundColor: [
                        'rgba(244, 67, 54, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 225, 59, 0.5)',
                        'rgba(75, 192, 120, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 152, 0, 0.5)',
                        'rgba(244, 114, 208, 0.5)'
                    ],
                    borderColor: [
                        'rgba(244, 67, 54, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 225, 59, 1)',
                        'rgba(75, 192, 120, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 152, 0, 1)',
                        'rgba(244, 114, 208, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
}


// var ctx2 = document.getElementById('myChart2').getContext('2d');
// var myChart2 = new Chart(ctx2, {
//     type: 'pie',
//     data: {
//         labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange', 'other'],
//         datasets: [{
//             label: '# of Votes',
//             data: [12, 19, 3, 5, 2, 3, 8],
//             backgroundColor: [
//                 'rgba(244, 67, 54, 0.5)',
//                 'rgba(54, 162, 235, 0.5)',
//                 'rgba(255, 225, 59, 0.5)',
//                 'rgba(75, 192, 120, 0.5)',
//                 'rgba(153, 102, 255, 0.5)',
//                 'rgba(255, 152, 0, 0.5)',
//                 'rgba(244, 114, 208, 0.5)'
//             ],
//             borderColor: [
//                 'rgba(244, 67, 54, 1)',
//                 'rgba(54, 162, 235, 1)',
//                 'rgba(255, 225, 59, 1)',
//                 'rgba(75, 192, 120, 1)',
//                 'rgba(153, 102, 255, 1)',
//                 'rgba(255, 152, 0, 1)',
//                 'rgba(244, 114, 208, 1)'
//             ],
//             borderWidth: 1
//         }]
//     },
//     options: {
//         scales: {
//             yAxes: [{
//                 ticks: {
//                     beginAtZero: true
//                 }
//             }]
//         }
//     }
// });