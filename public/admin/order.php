<?php
require_once('../../private/initialize.php');

require_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mini Cart | Orders</title>

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
	<style>
		.big-btn-all-orders {
			width: 60%;
    		border-radius: 0px;
    		background: #0f0c29;
    		/* fallback for old browsers */
    		background: -webkit-linear-gradient(to right, #24243e, #302b63, #0f0c29);
    		/* Chrome 10-25, Safari 5.1-6 */
    		background: linear-gradient(to right, #24243e, #302b63, #0f0c29);
    		/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    		text-decoration: none;
    		color: #fff;
    		box-shadow: 4px 4px 11px 3px rgb(148 148 148 / 60%);
    		padding-top: 1vw;
		}

		.big-btn-all-orders:hover {
			color: #fff;
		}

		.big-btn-search-order {
			width: 60%;
    		border-radius: 0px;
    		background: #606c88;
    		/* fallback for old browsers */
    		background: -webkit-linear-gradient(to right, #3f4c6b, #606c88);
    		/* Chrome 10-25, Safari 5.1-6 */
    		background: linear-gradient(to right, #3f4c6b, #606c88);
    		/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    		text-decoration: none;
    		color: #fff;
    		box-shadow: 4px 4px 11px 3px rgb(148 148 148 / 60%);
    		padding-top: 1vw;
		}

		.big-btn-search-order:hover {
			color: #fff;
		}

		.all-orders-list {
			display: none;
		}

		.search-order {
			display: none;
		}

		hr {
    		margin-top: .3rem;
    		margin-bottom: .5rem;
		}

		.final-bill {
    		position: absolute;
    		top: 0px;
    		width: 100%;
    		z-index: 100;
    		background-color: #fff;
    		display: none;
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
	<div class="final-bill p-3">
	</div>
	<?php 
    	include("core/menu.php"); 
    	include("core/header.php");
  	?>

  	<div class="container-fluid">
		<div class="row pt-3">
			<div class="col-sm-3">
				<div class="text-center">
					<h2><i class="fas fa-shopping-cart text-info"></i> Orders</h2>
				</div><br>
				<div class="text-center">
          			<button class="btn big-btn-all-orders" onclick="open_orders_list();">
            			<h6>All orders <i class="fas fa-clipboard-list"></i></h6>
          			</button>
        		</div><br>
        		<div class="text-center">
         			<button class="btn big-btn-search-order" onclick="open_search_order();">
         				<h6>Search order <i class="fas fa-search"></i></h6>
         			</button>
        		</div>
			</div>
			<div class="col-sm-9">
				<!--  Div for order listing -->
				<div class="all-orders-list">
					<div style="text-align: right;font-size: 20px;">
        			 	<span style="float: left;"><h4 style="padding-left: 30vw;">All Orders </h4></span>
        				<span style="cursor: pointer;" onclick="$('.all-orders-list').css('display','none');">
              				<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            			</span>
          			</div><br>
          			<div style="overflow: auto;" id="table-holder">
          				<table border="1" align="center" data-offset="0" data-fetch_status="true" style="width: 100%;font-size: 12px;" id="all-orders"></table>
          			</div>
          			<!-- <table border="1" align="center" style="width: 100%;" id="all-orders" ></table> -->
				</div>

				<!-- Div for order searching -->
				<div class="search-order p-3">
					<div style="text-align: right;font-size: 20px;">
        			 	<span style="float: left;"><h4 style="padding-left: 30vw;">Search Orders </h4></span>
        				<span style="cursor: pointer;" onclick="$('.search-order').css('display','none');">
              				<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            			</span>
          			</div><br>
					<form action="#" method="post" id="search-bill-form">
						<div class="row">
							<div class="col-sm-8">
								<input type="text" id="bill_id_inp" name="bill_id" placeholder="Enter Bill Id" class="form-control" required="">
							</div>
							<div class="col-sm-4 text-center">
								<input type="submit" class="btn btn-primary" name="submit" value="Search">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script src="assets/JS/order_js.js"></script>
	<script src="assets/JS/common_js.js"></script>
	<script type="text/javascript">
		$(window).resize(function(){
			$('#table-holder').css("max-height", $(window).height()-150);
		});
		$(document).ready(function() {
			$('#table-holder').css("max-height", $(window).height()-150);
			open_orders_list();
    		$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</body>
</html>