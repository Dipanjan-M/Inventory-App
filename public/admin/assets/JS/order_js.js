$('#search-bill-form').submit(function(e) {
    e.preventDefault();
    get_bill($('#bill_id_inp').val());
    $('#search-bill-form').trigger('reset');
});

function open_orders_list() {
    get_all_orders();
    $('.search-order').css('display', 'none');
    $('.all-orders-list').css("display", "block");
}

function get_all_orders() {
    $.get("fetch_all_orders.php", function(data, status) {
        var orders_table = document.getElementById('all-orders');
        var res_string = `<tr align="center">
                            <th width="40%">Customer</th>
                            <th>
                                Orders
                                <table style="width: 100%;">
                                  <tr align="center">
                                    <td width="45%">Name</td>
                                    <td width="20%">Price</td>
                                    <td width="20%">Tax</td>
                                    <td width="15%">Qty.</td>
                                  </tr>
                                </table>
                            </th>
                            <th>Action</th>
                          </tr>`;
        var orders = JSON.parse(data);
        orders.forEach((order) => {
            res_string += `<tr align="center">
                                <td width="40%" align="left" style="padding-left: 1vw;">
                                  ` + order['customer_details']['name'] + ` <br>
                                  ` + order['customer_details']['address'] + ` <br>
                                  +91-` + order['customer_details']['mobile'] + `<br>
                                  ` + order['customer_details']['email'] + ` <br>
                                  Bill Id : ` + order['customer_details']['bill_id'] + ` <br>
                                  Date : ` + order['customer_details']['date'] + `
                                </td>
                                <td>
                                    <table style="width: 100%;">`;
            order['customer_details']['orders'].forEach((item) => {
                res_string += `<tr align="center">
                                    <td width="45%">` + item['p_name'] + `</td>
                                    <td width="20%">` + item['unit_price'].toFixed(2) + `</td>
                                    <td width="20%">` + item['tax'].toFixed(2) + `</td>
                                    <td width="15%">` + item['quantity'] + `</td>
                                  </tr>`;
            });

            res_string += `</table>
                                  </td>
                                  <td>
                                    <i class="fas fa-file-invoice text-primary" data-toggle="tooltip" title="Get Invoice" style="font-size: 2em;cursor: pointer;" onclick="get_bill('` + order['customer_details']['bill_id'] + `');"></i>
                                  </td>
                              </tr>`;
        });
        orders_table.innerHTML = res_string;
        $('#spinners-all').remove();
    });
}

function open_search_order() {
    $('.all-orders-list').css('display', 'none');
    $('.search-order').css('display', 'block');
}

function get_bill(b_id) {
    $.ajax({
        url: "fetch_bill.php",
        method: "post",
        data: { bill_id: b_id },
        dataType: "text",
        success: function(data) {
            try {
                var msgs = JSON.parse(data);
                generate_bill_to_print(msgs);
            } catch(e) {
                alert(data);
            }
        },
        error: function(err) {
            alert(err);
        }
    });
}

function generate_bill_to_print(data) {
    var discount = parseFloat(data['billing_customer']['discount']);
    var taxed_table = `<table border="1" width="100%">
                                    <tr align="center">
                                        <th width="50%">Particulars</th>
                                        <th>Unit Price</th>
                                        <th>Qty.</th>
                                        <th>GST (%)</th>
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
        taxed_table += `<tr align="center">
                                    <td width="50%">` + item_name + `</td>
                                    <td>` + item_cost_price.toFixed(2) + `</td>
                                    <td>` + item_quantity + `</td>
                                    <td>` + item_tax.toFixed(2) + `</td>
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
                        <td colspan="4"><strong>Grand Total</strong></td>
                        <td align="right" style="padding-right: .5vw;">` + grand_total.toFixed(2) + `<br>(-) `+discount.toFixed(2)+`
                        <br><strong>`+ (grand_total-discount).toFixed(2) +`</strong><br>(rounded)</td>
                    </tr>
                    </table>`;
    normal_table += `<tr align="center">
                        <td colspan="3"><strong>Grand Total</strong></td>
                        <td align="right" style="padding-right: .5vw;">` + grand_total.toFixed(2) + `<br>(-) `+discount.toFixed(2)+`
                        <br><strong>`+ (grand_total-discount).toFixed(2) +`</strong><br>(rounded)</td>
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
                                            <h4><i class="fas fa-stethoscope"></i> Piya Motors</h4>
                                            <h6><span class="border"> &nbsp; TAX Invoice &nbsp; </span></h6>
                                            <p>
                                                Nimtala, Daspur - xxxxxx <br> Paschim Mednipur, West Bengal, India
                                                <br>Mobile No - +91-xxxxxxxxxx <br> Email - piyamotors@email.com
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
                                            <h4><i class="fas fa-stethoscope"></i> Piya Motors</h4>
                                            <h6><span class="border"> &nbsp; Invoice &nbsp; </span></h6>
                                            <p>
                                                Nimtala, Daspur - xxxxxx <br> Paschim Mednipur, West Bengal, India
                                                <br>Mobile No - +91-xxxxxxxxxx <br> Email - piyamotors@email.com
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

function close_final_bill() {
    $('#tax-bill').empty();
    $('#without-tax-bill').empty();
    $('.final-bill').css('display', 'none');
}

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