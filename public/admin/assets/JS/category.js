function edit_category(cat_id, cat_name, cat_tax) {
    $('#edit-category-form').trigger('reset');
    $('#edit-category-form input').each(function() {
        // console.log(this.name);
        if (this.name == 'cat[cat_name]' && this.value == '') {
            this.value = cat_name;
        }

        if (this.name == 'cat[gst_percentage]' && this.value == '') {
            this.value = cat_tax;
        }
    });
    $('#edt-cat-btn').val(cat_id);
    open_edt_cat();
}

$('#edit-category-form').submit(function(e) {
    e.preventDefault();
    var elem = document.getElementById('status-area');
    var cat_id = $('#edt-cat-btn').val();
    $('#edt-cat-btn').attr("disabled", "true");
    $('#edt-cat-btn').html('<span class="spinner-border spinner-border-sm"></span> Processing...');
    var form_data = $('#edit-category-form :input').serializeArray();
    form_data.push({ name: "cat[cat_id]", value: cat_id });
    $.ajax({
        url: "services/edit_category.php",
        method: "post",
        data: form_data,
        dataType: "text",
        success: function(data) {
            elem.innerHTML = '';
            var msgs = JSON.parse(data);
            msgs.forEach((msg) => {
                elem.innerHTML += msg;
            });
            $('#edt-cat-btn').removeAttr("disabled");
            $('#edt-cat-btn').html('Edit <i class="fas fa-pencil-alt"></i>');
            open_cat_list();
        },
        error: function(err) {
            alert(err);
            $('#edt-cat-btn').removeAttr("disabled");
            $('#edt-cat-btn').html('Edit <i class="fas fa-pencil-alt"></i>');
        }
    });
    $('#edit-category-form').trigger('reset');
});

function fetch_categories() {
    $.get("services/fetch_all_categories.php", function(data, status) {
        var elem = document.getElementById('all-categories');
        elem.innerHTML = `<tr align="center">
                                    <th>Name</th>
                                    <th>Tax Applicable</th>
                                    <th>Added By</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th>Action</th>
                                </tr>`;
        // elem.innerHTML = '';
        var categories = JSON.parse(data);
        categories.forEach((category) => {
            // console.log(category);
            elem.innerHTML += `<tr align="center">
                                            <td>` + category['cat_name'] + `</td>
                                            <td>` + parseFloat(category['gst_percentage']).toFixed(2) + `%</td>
                                            <td>` + category['addedBy'] + `</td>
                                            <td>` + category['createdAt'] + `</td>
                                            <td>` + category['updatedAt'] + `</td>
                                            <td>
                                                <span class="text-primary" style="cursor: pointer;" onclick="edit_category('` + category['id'] + `','` + category['cat_name'] + `','` + parseFloat(category['gst_percentage']).toFixed(2) + `');">
                                                    Edit
                                                </span> | 
                                                <span class="text-danger" style="cursor: pointer;" onclick="delete_category('` + category['id'] + `');">
                                                    Delete
                                                </span>
                                            </td>
                                        </tr>`;
        });
    });
}

function delete_category(cat_id) {
    var elem = document.getElementById('status-area');
    elem.innerHTML = '';
    $.ajax({
        url: "services/delete_category.php",
        method: "post",
        data: { id: cat_id },
        dataType: "text",
        success: function(data) {
            var msgs = JSON.parse(data);
            msgs.forEach((msg) => {
                elem.innerHTML += msg;
            });
            open_cat_list();
        },
        error: function(err) {
            alert(err);
        }
    });
}

function open_cat_list() {
    $('.add-category-form').css('display', 'none');
    $('.edit-category-form').css('display', 'none');
    $('.all-category-table').css('display', 'block');
    fetch_categories();
}

function open_add_cat() {
    $('.all-category-table').css('display', 'none');
    $('.edit-category-form').css('display', 'none');
    $('.add-category-form').css('display', 'block');
}

function open_edt_cat() {
    $('.all-category-table').css('display', 'none');
    $('.add-category-form').css('display', 'none');
    $('.edit-category-form').css('display', 'block');
}

$('#add_category-form').submit(function(e) {
    e.preventDefault();
    var elem = document.getElementById('status-area');
    $('#add-cat-btn').html('<span class="spinner-border spinner-border-sm"></span> Adding...');
    $('#add-cat-btn').attr("disabled", "true");
    $.ajax({
        url: $('#add_category-form').attr('action'),
        method: "post",
        data: $('#add_category-form :input').serializeArray(),
        dataType: "text",
        success: function(data) {
            // alert(data);
            elem.innerHTML = '';
            var msg = JSON.parse(data);
            msg.forEach((item) => {
                elem.innerHTML += item;
            });
            $('#add-cat-btn').html('Add <i class="fas fa-plus"></i>');
            $('#add-cat-btn').removeAttr("disabled");
        },
        error: function(err) {
            alert(err);
            $('#add-cat-btn').html('Add <i class="fas fa-plus"></i>');
            $('#add-cat-btn').removeAttr("disabled");
        }
    });
    $('#add_category-form').trigger('reset');
});