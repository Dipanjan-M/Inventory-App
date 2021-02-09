<?php
require_once('../../private/initialize.php');

require_login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Piyamotors | Orders</title>

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
	</style>
</head>
<body>
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
         				<h6>Search order <i class="fas fa-plus-square"></i></h6>
         			</button>
        		</div>
			</div>
			<div class="col-sm-9">
				<!--  Div for order listing -->
				<div class="all-orders-list p-3">
					<div style="text-align: right;font-size: 20px;">
        			 	<span style="float: left;"><h4 style="padding-left: 30vw;">All Orders </h4></span>
        				<span style="cursor: pointer;" onclick="$('.all-orders-list').css('display','none');">
              				<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            			</span>
          			</div><br>
          			<table border="1" align="center" style="width: 100%;" id="all-orders">
          				<tr align="center">
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
          				</tr>
          				<tr align="center">
          					<td width="40%" align="left" style="padding-left: 1vw;">
          						Dipanjan Maity <br>
          						108, Kshetra Banerjee Lane, Shibpur, Howrah - 711102, West Bengal, India <br>
          						+91-8910646211 <br>
          						dipanjanmaity08@gmail.com <br>
          						Bill Id : wQtpr-1162354789 <br>
          						Date : 2021-02-09
          					</td>
          					<td>
          					  	<table style="width: 100%;">
          					  		<tr align="center">
          					  			<td width="45%">Smart obd tool</td>
          					  			<td width="20%">5000.00</td>
          					  			<td width="20%">14.00%</td>
          					  			<td width="15%">1</td>
          					  		</tr>
          					  		<tr align="center">
          					  			<td width="45%">Hand gloves</td>
          					  			<td width="20%">150.00</td>
          					  			<td width="20%">5.00%</td>
          					  			<td width="15%">5</td>
          					  		</tr>
          					  		<tr align="center">
          					  			<td width="45%">Something not listed</td>
          					  			<td width="20%">120.00</td>
          					  			<td width="20%">4.00%</td>
          					  			<td width="15%">10</td>
          					  		</tr>
          					  		<tr align="center">
          					  			<td width="45%">Bike Engine Oil</td>
          					  			<td width="20%">980.00</td>
          					  			<td width="20%">18.00%</td>
          					  			<td width="15%">1</td>
          					  		</tr>
          					  	</table>
          				    </td>
          				    <td>
          				    	<i class="fas fa-file-invoice text-primary" data-toggle="tooltip" title="Get Invoice" style="font-size: 2em;cursor: pointer;"></i>
          				    </td>
          				</tr>
          			</table>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function open_orders_list() {
			$('.all-orders-list').css("display", "block");
			get_all_orders();
		}

		function get_all_orders() {
			$.get("fetch_all_orders.php", function(data, status) {
				console.log(data);
			});
		}
	</script>

	<script src="assets/JS/common_js.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
    		$('[data-toggle="tooltip"]').tooltip();
    		var session_msg = '<?php echo $session->message(); ?>';
    		if (session_msg != '') {
        		alert("System says : " + session_msg);
        		<?php $session->clear_message(); ?>
    		}
		});
	</script>
</body>
</html>