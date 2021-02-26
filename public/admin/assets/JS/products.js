var busy_state = false;
function get_all_products() {
    if(busy_state) {
        return;
    } else {
        busy_state = true;
    }
    document.getElementById('server_is_busy').style.display = "block";
    $.ajax({
        url: "fetch_all_products.php",
        method: "post",
        data: {Offset: $('#all-products').data('offset')},
        dataType: "text",
        // async: false,
        success: function(data) {
            try {
                var res_str = '';
                if(parseInt($('#all-products').data('offset'))=='0') {
                    res_str = `<tr align="center">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>
                            Price <i class="fas fa-rupee-sign"></i>
                            <table>
                                <tr align="center">
                                    <td width="33.33%">Buying</td>
                                    <td width="33.33%">Selling Customer</td>
                                    <td width="33.33%">Selling Vendor</td>
                                </tr>
                            </table>
                        </th>
                        <th>In Stock</th>
                        <th width="10%">Updated At</th>
                        <th width="10%">Added At</th>
                        <th width="12%">Action</th>
                      </tr>`;
                }
                var products = JSON.parse(data);
                var size = products.length;
                if(size<25) {
                    $('#all-products').data('fetch_status','false');
                }
                products.forEach((product) => {
                    var legend = "";
                    if (parseInt(product['total_stock']) < 10) {
                        legend = "w3-pale-red";
                    } else if (parseInt(product['total_stock']) < 20) {
                        legend = "w3-pale-yellow";
                    }
                    res_str += `<tr class="` + legend + `" align="center">
                      <td>` + product['id'] + `</td>
                      <td>` + product['p_name'] + `</td>
                      <td>` + product['category'] + `</td>
                      <td>
                        <table width="100%">
                            <tr align="center">
                                <td width="33.33%">` + parseFloat(product['main_price']).toFixed(2) + `</td>
                                <td width="33.33%">` + parseFloat(product['unit_price']).toFixed(2) + `</td>
                                <td width="33.33%">` + parseFloat(product['vendor_price']).toFixed(2) + `</td>
                            </tr>
                        </table>
                      </td>
                      <td>` + product['total_stock'] + `</td>
                      <td width="10%">` + product['updatedAt'] + `</td>
                      <td width="10%">` + product['createdAt'] + `</td>
                      <td width="12%"><span class="text-success" style="cursor: pointer;" onclick="increase_stock('` + product['id'] + `','all_products');">Add</span> | <span class="text-primary" style="cursor: pointer;" onclick="edit_product('` + product['id'] + `','` + product['p_name'] + `','` + product['category'] + `','` + product['main_price'] + `','` + product['unit_price'] + `','` + product['vendor_price'] + `','` + product['total_stock'] + `');">Edit</span> | <span class="text-danger" style="cursor: pointer;" onclick="delete_product('` + product['id'] + `');">Delete</span></td>
                     </tr>`;
                });
                var offset = parseInt($('#all-products').data('offset'));
                offset += size;
                $('#all-products').data('offset', offset);
                $('#all-products').append(res_str);
                busy_state = false;
            } catch(e) {
                alert(data);
                busy_state = false;
            }
            document.getElementById('server_is_busy').style.display = "none";
            var fetched_product_count = $('#all-products > tr').length-1;
            $('#analytics-div').html('Total products fetched '+fetched_product_count);
        },
        error: function(err) {
            alert(err);
            document.getElementById('server_is_busy').style.display = "none";
            busy_state = false;
        }
    });
}

$('#table-holder').scroll(function(){
    var elem = document.getElementById('table-holder');
    var the_diff = elem.scrollHeight-elem.scrollTop;
    if(the_diff == elem.clientHeight) {
        var fetch_status = $('#all-products').data('fetch_status');
        if(fetch_status == "true") {
            get_all_products();
        }
    }
});

/*
Function : open_product_list() { code... }
Arguments : none;
Description : This function loads all the products that added 
to the products table in the database. It works step by step 
like -
    closes the div with class name edit-product
    closes the div with class name add-product
    display the div with class name all-products-list

    Set the data-offset attribute of the table with id all-products to 0
    Set the data-fetch_status attribute of the table with id all-products to true

    clear the table with id all-products

    call the get_all_products() function to fill the table
*/

function open_product_list() {
    var elem = document.getElementById('table-holder');
    elem.scrollTop = 0;
    // console.log("ScrollTop = " + elem.scrollTop);
    $('.edit-product').css("display", "none");
    $('.add-product').css('display', 'none');
    $('.all-products-list').css('display', 'block');
    $('#all-products').empty();
    $('#all-products').data("offset","0");
    $('#all-products').data('fetch_status','true');
    if(!busy_state){
        get_all_products();
    }
}

$('.big-btn-lst-prod').unbind('click').click((e)=>{
    e.preventDefault();
    open_product_list();
});

function display_edit_product_form() {
    $('#analytics-div').html('');
    $('.add-product').css('display', 'none');
    $('.all-products-list').css('display', 'none');
    $('.edit-product').css("display", "block");
}

function open_add_product() {
    $('#analytics-div').html('');
    $('.edit-product').css("display", "none");
    $('.all-products-list').css('display', 'none');
    $('.add-product').css('display', 'block');
}

function edit_product(p_id, p_name, p_cat, p_main_price, p_unit_price, p_vendor_price, p_stock) {
    var elem = document.getElementById('edit-product');
    elem.innerHTML = '';
    elem.innerHTML += `<div class="row">
                    <div class="col">
                      <label for="prod_id">Product ID <sup class="text-danger">*System generated</sup></label>
                    </div>
                    <div class="col">
                      <input type="text" class="form-control" name="edit_product[id]" value="` + p_id + `" readonly>
                    </div>
                  </div><br>
            <label for="prod-name">Enter product name <sup class="text-danger">*</sup></label><br>
                  <input type="text" name="edit_product[p_name]" class="form-control" value="` + p_name + `" required=""><br>
                  <div class="row">
                    <div class="col-sm">
                      <label for="unit-price">Enter customer selling price per unit <sup class="text-danger">*</sup></label><br>
                      <input type="number" name="edit_product[unit_price]" value="` + parseFloat(p_unit_price).toFixed(2) + `" min="0" step="0.01" placeholder="0.00" required="" class="form-control">
                    </div>
                    <div class="col-sm">
                      <label for="vendor-price">Enter vendor selling price per unit <sup class="text-danger">*</sup></label><br>
                      <input type="number" name="edit_product[vendor_price]" value="` + parseFloat(p_vendor_price).toFixed(2) + `" min="0" step="0.01" placeholder="0.00" required="" class="form-control">
                    </div>
                    <div class="col-sm">
                      <label for="cat-name">Select category <sup class="text-danger">*</sup></label><br>
                      <select name="edit_product[category]" id="sel-edt-prod-cat" class="form-control">
                        
                      </select>
                    </div>
                  </div><br>
                  <div class="row">
                    <div class="col-sm">
                      <label for="main-price">Enter buying price per unit <sup class="text-danger">*</sup></label><br>
                      <input type="number" name="edit_product[main_price]" value="` + parseFloat(p_main_price).toFixed(2) + `" placeholder="0.00" min="0" step="0.01" required="" class="form-control">
                    </div>
                    <div class="col-sm">
                      <label for="stock">Enter the total number of products <sup class="text-danger">*</sup></label><br>
                      <input type="number" name="edit_product[total_stock]" min="0" step="1" class="form-control" placeholder="0" value="` + p_stock + `" required="">
                    </div>
                  </div><br>
                  <div class="text-center">
                    <button class="btn btn-warning" type="submit" name="submit" id="btn-edt-prod">
                      Edit <i class="fas fa-pencil-alt"></i>
                    </button>
                  </div>`;
    // console.log(p_id, p_name, p_cat, p_unit_price, p_stock);
    $.get("fetch_all_categories.php", function(data, status) {
        var sel_elem = document.getElementById('sel-edt-prod-cat');
        sel_elem.innerHTML = '';
        var categories = JSON.parse(data);
        categories.forEach((category) => {
            // console.log(category);
            if (category['cat_name'] == p_cat) {
                sel_elem.innerHTML += `<option value="` + category['cat_name'] + `" selected>` + category['cat_name'] + `(` + parseFloat(category['gst_percentage']).toFixed(2) + `%)</option>`;
            } else {
                sel_elem.innerHTML += `<option value="` + category['cat_name'] + `">` + category['cat_name'] + `(` + parseFloat(category['gst_percentage']).toFixed(2) + `%)</option>`;
            }
        });
    });
    display_edit_product_form();
}

$('#edit-product').submit(function(e) {
    var elem = document.getElementById('status-area');
    elem.innerHTML = '';
    e.preventDefault();
    $('#btn-edt-prod').attr("disabled", "true");
    $('#btn-edt-prod').html(`<span class="spinner-border spinner-border-sm"></span> Processing...`);
    $.ajax({
        url: "edit_product.php",
        method: "post",
        data: $('#edit-product :input').serializeArray(),
        dataType: "text",
        success: function(data) {
            // alert(data);
            var msgs = JSON.parse(data);
            msgs.forEach((msg) => {
                elem.innerHTML += msg;
            });
            $('#btn-edt-prod').removeAttr("disabled");
            $('#btn-edt-prod').html(`Edit <i class="fas fa-pencil-alt"></i>`);
            open_product_list();
        },
        error: function(err) {
            alert(err);
            $('#btn-edt-prod').removeAttr("disabled");
            $('#btn-edt-prod').html(`Edit <i class="fas fa-pencil-alt"></i>`);
        }
    });
    $('#edit-product').trigger("reset");
});

function isDigit(str) {
    return /^ *[0-9]+ *$/.test(str);
}

function increase_stock(p_id, call_from) {
    var amount = prompt("Enter amount to increase stock by : ", 0);
    if (isDigit(amount)) {
        var increment = parseInt(amount);
        if (increment <= 0) {
            alert("Enter value greater than 0.");
        } else {
            $.ajax({
                url: "increment_stock.php",
                method: "post",
                data: { id: p_id, inc_amount: increment },
                dataType: "text",
                success: function(data) {
                    alert(data);
                    if (call_from == 'all_products') {
                        open_product_list();
                    } else if (call_from == 'low_stock') {
                        edit_low_stocks();
                    }
                },
                error: function(err) {
                    alert(err);
                }
            });
        }
    } else {
        alert("Please enter a valid number");
    }
}

function delete_product(p_id) {
    $.ajax({
        url: "delete_product.php",
        method: "post",
        data: { id: p_id },
        dataType: "text",
        success: function(data) {
            var elem = document.getElementById('status-area');
            elem.innerHTML = '';
            var msgs = JSON.parse(data);
            msgs.forEach((msg) => {
                elem.innerHTML += msg;
                open_product_list();
            });
        },
        error: function(err) {
            alert(err);
        }
    });
    open_product_list();
}

function get_options() {
    $.get("fetch_all_categories.php", function(data, status) {
        var elem = document.getElementById('sel-prod-cat');
        elem.innerHTML = '';
        var msgs = JSON.parse(data);
        msgs.forEach((msg) => {
            elem.innerHTML += `<option value="` + msg['cat_name'] + `">` + msg['cat_name'] + ` (` + parseFloat(msg['gst_percentage']).toFixed(2) + `%)` + `</option>`;
        });
    });
}

$('#add-product').submit(function(e) {
    e.preventDefault();
    var elem = document.getElementById('status-area');
    $('#btn-add-prod').attr("disabled", "true");
    $('#btn-add-prod').html('<span class="spinner-border spinner-border-sm"></span> Processing...');
    $.ajax({
        url: "add_product.php",
        method: "post",
        data: $('#add-product :input').serializeArray(),
        dataType: "text",
        success: function(data) {
            // alert(data);
            elem.innerHTML = '';
            var msgs = JSON.parse(data);
            msgs.forEach((msg) => {
                elem.innerHTML += msg;
            });
            $('#btn-add-prod').removeAttr("disabled");
            $('#btn-add-prod').html('Add <i class="fas fa-plus-square"></i>');
        },
        error: function(err) {
            alert(err);
            $('#btn-add-prod').removeAttr("disabled");
            $('#btn-add-prod').html('Add <i class="fas fa-plus-square"></i>');
        }
    });
    $('#add-product').trigger("reset");
});


function edit_low_stocks() {
    $('#all-products').data("offset","0");
    $('#all-products').data('fetch_status','false');
    $('#all-products').empty();
    $('.edit-product').css("display", "none");
    $('.add-product').css('display', 'none');
    $('.all-products-list').css('display', 'block');
    document.getElementById('server_is_busy').style.display = "block";
    document.getElementById('all-products').style.display = "none";
    $.get("get_low_stocks.php", function(data, status) {
        var products = JSON.parse(data);
        // console.log(msgs);
        var elem = document.getElementById('all-products');
        var res_str = `<tr align="center">
                        <th>Name</th>
                        <th>Category</th>
                        <th>
                            Price <i class="fas fa-rupee-sign"></i>
                            <table>
                                <tr align="center">
                                    <td width="33.33%">Buying</td>
                                    <td width="33.33%">Selling Customer</td>
                                    <td width="33.33%">Selling Vendor</td>
                                </tr>
                            </table>
                        </th>
                        <th>In Stock</th>
                        <th width="10%">Updated At</th>
                        <th width="10%">Added At</th>
                        <th width="12%">Action</th>
                      </tr>`;
        products.forEach((product) => {
            res_str += `<tr align="center">
                      <td>` + product['p_name'] + `</td>
                      <td>` + product['category'] + `</td>
                      <td>
                        <table width="100%">
                            <tr align="center">
                                <td width="33.33%">` + parseFloat(product['main_price']).toFixed(2) + `</td>
                                <td width="33.33%">` + parseFloat(product['unit_price']).toFixed(2) + `</td>
                                <td width="33.33%">` + parseFloat(product['vendor_price']).toFixed(2) + `</td>
                            </tr>
                        </table>
                      </td>
                      <td>` + product['total_stock'] + `</td>
                      <td width="10%">` + product['updatedAt'] + `</td>
                      <td width="10%">` + product['createdAt'] + `</td>
                      <td width="12%"><span class="text-success" style="cursor: pointer;" onclick="increase_stock('` + product['id'] + `','low_stock');">Add</span> | <span class="text-primary" style="cursor: pointer;" onclick="edit_product('` + product['id'] + `','` + product['p_name'] + `','` + product['category'] + `','` + product['main_price'] + `','` + product['unit_price'] + `','` + product['vendor_price'] + `','` + product['total_stock'] + `');">Edit</span> | <span class="text-danger" style="cursor: pointer;" onclick="delete_product('` + product['id'] + `');">Delete</span></td>
                     </tr>`;
        });
        document.getElementById('server_is_busy').style.display = "none";
        $('#all-products').html(res_str);
        document.getElementById('all-products').style.display = "block";
        var fetched_product_count = $('#all-products > tr').length-1;
        $('#analytics-div').html('Total products fetched '+fetched_product_count);
    });
}


$(".search-box").keyup(function() {
    $('#all-products').data("offset","0");
    $('#all-products').data('fetch_status','false');
    $('#all-products').empty();
    var query = $(".search-box").text();
    if(query != '') {
        document.getElementById('server_is_busy').style.display = "block";
        $.ajax({
            url: "product_search.php",
            method: "post",
            data: { key: query },
            dataType: "text",
            success: function(data) {
                var elem = document.getElementById('all-products');
                var res_str = `<tr align="center">
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>
                                        Price <i class="fas fa-rupee-sign"></i>
                                        <table>
                                            <tr align="center">
                                                <td width="33.33%">Buying</td>
                                                <td width="33.33%">Selling Customer</td>
                                                <td width="33.33%">Selling Vendor</td>
                                            </tr>
                                        </table>
                                    </th>
                                    <th>In Stock</th>
                                    <th width="10%">Updated At</th>
                                    <th width="10%">Added At</th>
                                    <th width="12%">Action</th>
                                </tr>`;
                try {
                    var products = JSON.parse(data);
                    products.forEach((product)=>{
                        var legend = "";
                        if (parseInt(product['total_stock']) < 10) {
                            legend = "w3-pale-red";
                        } else if (parseInt(product['total_stock']) < 20) {
                            legend = "w3-pale-yellow";
                        }
                        res_str +=`<tr class="` + legend + `" align="center">
                                            <td>` + product['p_name'] + `</td>
                                            <td>` + product['category'] + `</td>
                                            <td>
                                                <table width="100%">
                                                    <tr align="center">
                                                        <td width="33.33%">` + parseFloat(product['main_price']).toFixed(2) + `</td>
                                                        <td width="33.33%">` + parseFloat(product['unit_price']).toFixed(2) + `</td>
                                                        <td width="33.33%">` + parseFloat(product['vendor_price']).toFixed(2) + `</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td>` + product['total_stock'] + `</td>
                                            <td width="10%">` + product['updatedAt'] + `</td>
                                            <td width="10%">` + product['createdAt'] + `</td>
                                            <td width="12%"><span class="text-success" style="cursor: pointer;" onclick="increase_stock('` + product['id'] + `','all_products');">Add</span> | <span class="text-primary" style="cursor: pointer;" onclick="edit_product('` + product['id'] + `','` + product['p_name'] + `','` + product['category'] + `','` + product['main_price'] + `','` + product['unit_price'] + `','` + product['vendor_price'] + `','` + product['total_stock'] + `');">Edit</span> | <span class="text-danger" style="cursor: pointer;" onclick="delete_product('` + product['id'] + `');">Delete</span></td>
                                        </tr>`;
                    });
                } catch(e) {
                    if(data != '') {
                        elem.innerHTML = '<h4 class="text-danger">'+data+'</h4>';
                    }
                }
                $('#all-products').html(res_str);
                document.getElementById('server_is_busy').style.display = "none";
            },
            error: function(err) {
                alert(err);
            }
        });
    } else {
        open_product_list();
    }
    
});