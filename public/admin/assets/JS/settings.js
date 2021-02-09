function delete_admin(admin_id, my_id) {
    $.ajax({
        url: "delete_admin.php",
        method: "post",
        data: { admin_id: admin_id },
        dataType: "text",
        success: function(data) {
            var msg = JSON.parse(data);
            var stat = document.getElementById('status-area');
            stat.innerHTML = '';
            msg.forEach((item) => {
                stat.innerHTML += item;
            });
            open_all_admin_table(my_id);
        },
        error: function(err) {
            alert(err);
        }
    });
}

function fetch_admins(my_id) {
    $.get('get_all_admins.php', function(data, status) {
        var admins = JSON.parse(data);
        var elem = document.getElementById('all-admins');
        elem.innerHTML = `<tr  align="center">
                            <th>Name</th>
                            <th>Email</th>
                            <th>Added At</th>
                            <th>Action</th>
                          </tr>`;
        admins.forEach((admin) => {
            // console.log(admin);
            if (admin['id'] == my_id) {
                elem.innerHTML += `<tr  align="center">
                                <td>` + admin['f_name'] + ` ` + admin['l_name'] + `</td>
                                <td>` + admin['admin_email'] + `</td>
                                <td>` + admin['createdAt'] + `</td>
                                <td></td>
                              </tr>`;
            } else {
                elem.innerHTML += `<tr align="center">
                                <td>` + admin['f_name'] + ` ` + admin['l_name'] + `</td>
                                <td>` + admin['admin_email'] + `</td>
                                <td>` + admin['createdAt'] + `</td>
                                <td><span onclick="delete_admin('` + admin['id'] + `','` + my_id + `');" class="text-primary" style="cursor: pointer;">Delete</span></td>
                              </tr>`;
            }
        });
    });
}

function open_update_form() {
    $('.add-admin-form').css('display', 'none');
    $('.all-admin-table').css('display', 'none');
    $('.update-form').css('display', 'block');
}

function open_add_admin_form() {
    $('.update-form').css('display', 'none');
    $('.all-admin-table').css('display', 'none');
    $('.add-admin-form').css('display', 'block');
}

function open_all_admin_table(my_id) {
    $('.update-form').css('display', 'none');
    $('.add-admin-form').css('display', 'none');
    $('.all-admin-table').css('display', 'block');
    fetch_admins(my_id);
}

function toggle_password(my_id, elem_id) {
    var icon = document.getElementById(my_id);
    var inp_field = document.getElementById(elem_id);
    if (inp_field.type == "password") {
        inp_field.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        inp_field.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}



$('#update-info-form').submit(function(e) {
    $('#btn-info-updt').html('<span class="spinner-border spinner-border-sm"></span> Updating..');
    $('#btn-info-updt').attr('disabled', 'true');
    e.preventDefault();
    $.ajax({
        url: $('#update-info-form').attr('action'),
        method: 'post',
        data: $('#update-info-form :input').serializeArray(),
        dataType: 'text',
        success: function(data) {
            // console.log(data);
            var stat = document.getElementById('status-area');
            stat.innerHTML = '';
            var msg = JSON.parse(data);
            msg.forEach((item) => {
                stat.innerHTML += item;
            });
            // alert(msg[0]);
            $('#btn-info-updt').removeAttr('disabled');
            $('#btn-info-updt').html('Update <i class="fas fa-cloud-upload-alt"></i>');
            document.getElementById('update-info-form').reset();
            if(confirm("Please login again.")){
                window.location.href = "../logout.php";
            } else {
                window.location.href = "../logout.php";
            }
            // window.location.href = "../logout.php";
        },
        error: function(err) {
            alert(err);
            $('#btn-info-updt').removeAttr('disabled');
            $('#btn-info-updt').html('Update <i class="fas fa-cloud-upload-alt"></i>');
        }
    });
});


$('#add-admin-form').submit(function(e) {
    $('#btn-add-admin').html('<span class="spinner-border spinner-border-sm"></span> Adding..');
    $('#btn-add-admin').attr('disabled', 'true');
    e.preventDefault();
    $.ajax({
        url: $('#add-admin-form').attr('action'),
        method: 'post',
        data: $('#add-admin-form :input').serializeArray(),
        dataType: 'text',
        success: function(data) {
            // console.log(data);
            var stat = document.getElementById('status-area');
            stat.innerHTML = '';
            var msg = JSON.parse(data);
            msg.forEach((item) => {
                stat.innerHTML += item;
            });
            $('#btn-add-admin').removeAttr('disabled');
            $('#btn-add-admin').html('Add <i class="fas fa-user-plus"></i>');
            document.getElementById('add-admin-form').reset();
        },
        error: function(err) {
            alert(err);
            $('#btn-add-admin').removeAttr('disabled');
            $('#btn-add-admin').html('Add <i class="fas fa-user-plus"></i>');
        }
    });
});