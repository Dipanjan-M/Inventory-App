function get_gen_report() {
    $('#report-on-demand').css('display', 'none');
    $('#general-report').css('display', 'block');
}

function open_search_order() {
    $('#general-report').css('display', 'none');
    $('#report-on-demand').css('display', 'block');
}

$('#get-report-form').submit(function(e) {
    e.preventDefault();
    var tbl = document.getElementById('report-tbl');
    tbl.innerHTML = `<tr align="center">
          						<th>Bill ID</th>
          						<th>Selling Price</th>
          						<th>Buying Price</th>
          						<th>Discount</th>
          						<th>Profit</th>
          					</tr>`;
    $.ajax({
        url: $('#get-report-form').attr('action'),
        method: "post",
        data: $('#get-report-form :input').serializeArray(),
        dataType: "text",
        success: function(data) {
            try {
                var orders = JSON.parse(data);
                orders.forEach((order) => {
                    // console.log(order);
                    tbl.innerHTML += `<tr>
										<td>` + order['bill_id'] + `</td>
										<td>` + parseFloat(order['selling_price']).toFixed(2) + `</td>
										<td>` + parseFloat(order['buying_price']).toFixed(2) + `</td>
										<td>` + parseFloat(order['discount']).toFixed(2) + `</td>
										<td>` + parseFloat(order['selling_price'] - order['buying_price'] - order['discount']).toFixed(2) + `</td>
									</tr>`;
                });
            } catch (e) {
                alert(data);
                tbl.innerHTML = '';
            }
        },
        error: function(err) {
            alert(err);
        }
    });
    var inp_date_data = $('#get-report-form :input').serializeArray()[0];
    $('#date-for-report').html('<h6 class="text-success"> Report as on - '+ inp_date_data.value +'</h6>');
    $('#get-report-form').trigger('reset');
});