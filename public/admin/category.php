<?php
require_once('../../private/initialize.php');

require_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mini Cart | Product Category</title>

  <!-- Icon for title -->
  <link rel="icon" href="assets/images/mini-cart-bolder.PNG" type="image/png">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

	<!-- Fontawesome 5.7.0 -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

	<!-- Local external style sheet -->
	<link rel="stylesheet" href="assets/CSS/style_index.css">

	<link rel="stylesheet" href="assets/CSS/style_category.css">
</head>
<body>
	<div id="status-area"></div>
	<?php 
    	include("core/menu.php"); 
    	include("core/header.php");
  	?>

  	<div class="container-fluid">
		<div class="row pt-3">
			<div class="col-sm-3">
				<div class="text-center">
					<h2><i class="fas fa-tags text-warning"></i> Category</h2>
				</div><br>
				<div class="text-center">
          <button class="btn big-btn-lst-cat pt-3" onclick="open_cat_list();">
            <h6>List Categories <i class="fas fa-clipboard-list"></i></h6>
          </button>
        </div><br>
        <div class="text-center">
         	<button class="btn big-btn-add-cat pt-3" onclick="open_add_cat();">
         		<h6>Add Category <i class="fas fa-plus-square"></i></h6>
         	</button>
        </div>
			</div>
			<div class="col-sm-9">
				<!-- Div for all category listing -->
        <div class="all-category-table p-3">
        	<div style="text-align: right;font-size: 20px;">
        		<span style="float: left;"><h4 style="padding-left: 28vw;">All Categories</h4></span>
        		<span style="cursor: pointer;" onclick="$('.all-category-table').css('display','none');">
              <i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            </span>
          </div><br>
          <table border="1" align="center" style="width: 100%;" id="all-categories">
          </table>
        </div>

        <!-- Div for add new category -->
        <div class="add-category-form p-3">
          <div style="text-align: right;font-size: 20px;">
          	<span style="float: left;"><h4 style="padding-left: 28vw;">Add Category</h4></span>
          	<span style="cursor: pointer;" onclick="$('.add-category-form').css('display','none');">
          		<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
          	</span>
          </div><br>
          <form action="services/add_category.php" method="post" id="add_category-form">
          	<label for="cat_name">Enter the catagory name <sup class="text-danger">*</sup></label><br>
          	<input type="text" name="category[cat_name]" class="form-control" required=""><br>
          	<label for="gst">Enter tax applicable (GST or non-GST %) <sup class="text-danger">*</sup></label><br>
          	<input type="number" name="category[gst_percentage]" class="form-control" placeholder="0.00" min="0" max="100" step="0.01" required=""><br>
          	<label for="admin-email">Enter admin email id <sup class="text-danger">*</sup></label><br>
          	<input type="email" name="category[admin_email]" class="form-control" value="<?php echo $session->email; ?>" readonly>
          	<br>
          	<div class="text-center">
          		<button class="btn btn-success" type="submit" value="add" name="submit" id="add-cat-btn">
          			Add <i class="fas fa-plus"></i>
          		</button>
          	</div>
          </form>
        </div>

        <!-- Div for edit category -->
        <div class="edit-category-form p-3">
        	<div style="text-align: right;font-size: 20px;">
            <span style="float: left;"><h4 style="padding-left: 22vw;">Edit Category</h4></span>
            <span style="cursor: pointer;" onclick="$('.edit-category-form').css('display','none');">
              <i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            </span>
          </div><br>
          <form action="" method="post" id="edit-category-form">
          	<label for="cat_name">Category Name <sup class="text-danger">*</sup></label><br>
          	<input type="text" name="cat[cat_name]" class="form-control" readonly="" required=""><br>
          	<label for="tax_val">Enter tax applicable (GST or non-GST %) <sup class="text-danger">*</sup></label><br>
          	<input type="number" name="cat[gst_percentage]" class="form-control" min="0" max="100" step="0.01" required=""><br>
          	<label for="admin-email">Enter admin email id <sup class="text-danger">*</sup></label><br>
          	<input type="email" name="cat[addedBy]" class="form-control" value="<?php echo $session->email; ?>" readonly>
          	<br>
          	<div class="text-center">
          		<button class="btn btn-warning" type="submit" value="update" name="catagory_id" id="edt-cat-btn">
          			Edit <i class="fas fa-pencil-alt"></i>
          		</button>
          	</div>
          </form>
        </div>
			</div>
		</div>
	  </div>

	<script src="assets/JS/category.js"></script>
	<script src="assets/JS/common_js.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
    		$('[data-toggle="tooltip"]').tooltip();
    		open_cat_list();
    		var session_msg = '<?php echo $session->message(); ?>';
    		if (session_msg != '') {
        		alert("System says : " + session_msg);
        		<?php $session->clear_message(); ?>
    		}
		});
	</script>
</body>
</html>