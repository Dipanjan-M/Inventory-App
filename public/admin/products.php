<?php
require_once('../../private/initialize.php');

require_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Piyamotors | Products</title>

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

	<!-- W3 CSS -->
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

	<!-- Local external style sheet -->
	<link rel="stylesheet" href="assets/CSS/style_index.css">

	<link rel="stylesheet" href="assets/CSS/style_products.css">
	<style>
		.edit-product {
			display: none;
		}

    .search-box {
      display: inline-flex;
      width: 100%;
      height: 2.1vw;
      border: 1px solid #00f;
      padding-left: .5vw;
      padding-top: .14vw;
    }

    .waiting{
      position: absolute;
      z-index: 100;
      margin-left: -50px;
      left: 50%;
      top: 45%;
      width: 100px;
      height: 100px;
      background-color: #fff;
      font-size: 70px;
      text-align: center;
      box-shadow: 0px 1px 20px 7px;
      border-radius: 8px;
      display: none;
    }
    div#waiting-spinner{
      color: #000;
      animation: spinning 2s linear infinite;
    }
    @keyframes spinning{
      100% {transform: rotate(360deg);}
    }
	</style>
</head>
<body>
  <div id="server_is_busy" class="waiting">
    <div id="waiting-spinner">
      <i class="fas fa-cog"></i>
      <!-- <i class="fas fa-fan"></i> -->
    </div>
  </div>
	<div id="status-area"></div>
	<?php 
    	include("core/menu.php"); 
    	include("core/header.php");
  	?>
  	<div class="container-fluid">
		<div class="row pt-3">
			<div class="col-sm-3">
				<div class="text-center">
					<h2><i class="fas fa-cubes text-success"></i> Products</h2>
				</div><br>
				<div class="text-center">
          			<button class="btn big-btn-lst-prod">
            			<h6>List Products <i class="fas fa-clipboard-list"></i></h6>
          			</button>
        		</div><br>
        		<div class="text-center">
         			<button class="btn big-btn-add-prod" onclick="open_add_product();">
         				<h6>Add Products <i class="fas fa-plus-square"></i></h6>
         			</button>
        		</div><br>
            <div class="text-center">
              <button class="btn big-btn-chk-stk" onclick="edit_low_stocks();">
                <h6>Low stocks <i class="fas fa-exclamation-triangle"></i></h6>
              </button>
            </div>
            <div class="p-3 text-success">
              <h3 id="analytics-div"></h3>
            </div>
			</div>
			<div class="col-sm-9">
				<!--  Div for product listing -->
				<div class="all-products-list">
          <div class="row">
            <div class="col-sm-4" style="padding-top: .5vw;">
              <div class="search-box" contenteditable="" data-placeholder="Enter product name to search"></div>
            </div>
            <div class="col-sm-4 text-center">
              <h4>
                All Products 
                <i class="fas fa-square text-warning" data-toggle="tooltip" title="Stock < 20"></i> 
                <i class="fas fa-square w3-text-red" data-toggle="tooltip" title="Stock < 10"></i>
              </h4>
            </div>
            <div class="col-sm-4 text-right">
              <i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close" style="cursor: pointer;" onclick="$('.all-products-list').css('display','none');"></i>
            </div>
          </div>
          <div id="table-holder" style="overflow: auto;">
            <table border="1" align="center" style="width: 100%;font-size: 12px;" id="all-products" data-offset="0" data-fetch_status="true"></table>
          </div>
				</div>

				<!-- Div for add product -->
				<div class="add-product p-3">
					<div style="text-align: right;font-size: 20px;">
        			 	<span style="float: left;"><h4 style="padding-left: 30vw;">Add Product</h4></span>
        				<span style="cursor: pointer;" onclick="$('.add-product').css('display','none');">
              				<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            			</span>
          			</div><br>
          			<form action="add_product.php" method="post" id="add-product">
          				<label for="prod-name">Enter product name <sup class="text-danger">*</sup></label><br>
          				<input type="text" name="product[p_name]" class="form-control" required=""><br>
          				<div class="row">
          					<div class="col-sm">
          						<label for="unit-price">Enter customer selling price per unit <sup class="text-danger">*</sup></label><br>
          						<input type="number" name="product[unit_price]" placeholder="0.00" min="0" step="0.01" required="" class="form-control">
          					</div>
                    <div class="col-sm">
                      <label for="vendor-price">Enter vendor selling price per unit <sup class="text-danger">*</sup></label><br>
                      <input type="number" name="product[vendor_price]" placeholder="0.00" min="0" step="0.01" required="" class="form-control">
                    </div>
          					<div class="col-sm">
          						<label for="cat-name">Select tax category <sup class="text-danger">*</sup></label><br>
          						<select name="product[category]" id="sel-prod-cat" class="form-control">
          						</select>
          					</div>
          				</div><br>
                  <div class="row">
                    <div class="col-sm">
                      <label for="main-price">Enter buying price per unit <sup class="text-danger">*</sup></label><br>
                      <input type="number" name="product[main_price]" placeholder="0.00" min="0" step="0.01" required="" class="form-control">
                    </div>
                    <div class="col-sm">
                      <label for="stock">Enter the total number of products <sup class="text-danger">*</sup></label><br>
                      <input type="number" name="product[total_stock]" min="0" step="1" class="form-control" placeholder="0" required="">
                    </div>
                  </div><br>
          				<div class="text-center">
          					<button class="btn btn-success" type="submit" name="submit" id="btn-add-prod">
          						Add <i class="fas fa-plus-square"></i>
          					</button>
          				</div>
          			</form>
				</div>

				<!-- Div for edit product -->
				<div class="edit-product p-3">
					<div style="text-align: right;font-size: 20px;">
        			 	<span style="float: left;"><h4 style="padding-left: 30vw;">Edit Product</h4></span>
        				<span style="cursor: pointer;" onclick="$('.edit-product').css('display','none');">
              				<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            			</span>
          			</div><br>
          			<form action="" method="post" id="edit-product">
          				
          			</form>
				</div>
			</div>
		</div>
	</div>

	<script src="assets/JS/products.js"></script>
	<script src="assets/JS/common_js.js"></script>
	<script type="text/javascript">
    $(window).resize(function(){
      $('#table-holder').css("max-height", $(window).height()-150);
    });

    $('.search-box').focus(function(e){
      if($('.search-box').text() == $('.search-box').data("placeholder")) {
        $('.search-box').text('');
      }
    });

    $('.search-box').blur(function(e){
      if($('.search-box').text() == '') {
        $('.search-box').text($('.search-box').data("placeholder"));
      }
    });

		$(document).ready(function() {
      $('#table-holder').css("max-height", $(window).height()-150);
    	$('[data-toggle="tooltip"]').tooltip();
      if($('.search-box').text() == '') {
        $('.search-box').text($('.search-box').data("placeholder"));
      }
    	get_options();
    	open_product_list();
		});
	</script>
</body>
</html>