<?php
require_once('../../private/initialize.php');

require_login();

$in_stock = Analytics::get_stock_value() ?? '0.00';

$sale_total = Analytics::get_total_sale() ?? '0.00';

$wholesale_value = Analytics::get_wholesale_value() ?? '0.00';

$retail_value = Analytics::get_reatail_value()?? '0.00';

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mini Cart | Admin Dashboard</title>

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

	<!-- Chart.js -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

	<!-- Local external style sheet -->
	<link rel="stylesheet" href="assets/CSS/style_index.css">
	<link rel="stylesheet" href="assets/CSS/extra_style_index.css">
    <style>
        #low-stock {
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
	<div id="status-area"></div>
	<div class="create-order p-3">
		<div style="text-align: right;font-size: 20px;">
        	<span style="float: left;"><h5 style="padding-left: 44vw;">New Order</h5></span>
        	<span style="cursor: pointer;padding-right: 1vw;" onclick="$('.create-order').css('display','none');">
            	<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            </span>
        </div><br>
        <div class="search-prod">
        	<div class="row">
        		<div class="col-sm-3" style="text-align: right;padding-top: 0.4vw;">
        			<strong>Enter product name to search :</strong>
        		</div>
        		<div class="col-sm-6">
        			<div id="search-input" contenteditable=""></div>
        		</div>
        		<div class="col-sm-1 text-center">
        			<strong>Or</strong>
        		</div>
        		<div class="col-sm-2 text-center">
        			<button class="btn btn-primary" onclick="add_manual_order();">
        				Add Manually <i class="far fa-plus-square"></i>
        			</button>
        		</div>
        	</div>
        </div><hr>
        <div class="row">
        	<div class="col-sm-5">
        		<div class="text-center">
        			<h5>Available Product details</h5><hr>
        		</div>
        		<div id="available-products"></div>
        	</div>
        	<div class="col-sm-7">
        		<div class="text-center">
        			<h5>Order form</h5><hr>
        		</div>
        		<div class="the-order p-3" style="">
        			<form action="services/new_order.php" method="post" id="create-order">
        				<div class="row">
        					<div class="col-sm">
        						<strong>Customer details</strong>
        					</div>
        					<div class="col-sm" style="text-align: right;">
        						<i class="fas fa-angle-up" style="cursor: pointer;" id="collapse-cust" onclick="toggle_collapse_cast_details();"></i>
        					</div>
        				</div><hr style="margin-top: .1rem;">
        				<div id="cust-details">
        					<div class="row">
        						<div class="col-4">
        							<label for="customer-name"><strong>Customer Name : </strong><sup class="text-danger">*</sup></label>
        						</div>
        						<div class="col-8">
        							<input type="text" name="order[customer_name]" class="form-control" required="" placeholder="Enter customer name">
        						</div>
        					</div><br>
        					<div class="row">
        						<div class="col">
        							<label for="phone"><strong>Customer mobile no. </strong><sup class="text-danger">*</sup></label><br>
        							<input type="number" name="order[customer_mobile]" required="" class="form-control" maxlength="10" minlength="10" placeholder="Enter customer phone number">
        						</div>
        						<div class="col">
        							<label for="email"><strong>Customer email ID (if any).</strong></label><br>
        							<input type="email" name="order[customer_email]" class="form-control" placeholder="Enter customer email id">
        						</div>
        					</div><br>
        					<label for="address"><strong>Enter Customer address</strong></label><br>
        					<div class="row">
        						<div class="col">
        							<label for="house">House no./ Flat no./ Street/ Landmark <sup class="text-danger">*</sup></label>
        							<input type="text" name="order[address][hfsl]" class="form-control"><br>
        							<label for="district">Enter district <sup class="text-danger">*</sup></label>
        							<input type="text" name="order[address][district]" class="form-control" required="">
        						</div>
        						<div class="col">
        							<label for="area">Area / village / Town <sup class="text-danger">*</sup></label>
        							<input type="text" name="order[address][avt]" class="form-control" required=""><br>
        							<label for="state">Enter pin/zip code <sup class="text-danger">*</sup></label>
        							<input type="number" name="order[address][zip]" maxlength="6" class="form-control" required="">
        						</div>
        					</div><br>
        					<div class="row">
        						<div class="col">
        							<label for="state">Enter state name <sup class="text-danger">*</sup></label>
        							<input type="text" name="order[address][state]" class="form-control" required="">
        						</div>
        						<div class="col">
        							<label for="area">Country <sup class="text-danger">*</sup></label>
        							<input type="text" name="order[address][country]" class="form-control" required="">
        						</div>
        					</div>
        				</div>
        				<div class="row">
        					<div class="col-sm"><strong>All orders</strong></div>
        				</div>
        				<hr style="margin-top: .2rem;">
        				<div id="all-orders"></div>
                        <br>
                        <div class="row">
                            <div class="col-sm">
                                <label for="discount"><i class="fas fa-rupee-sign"></i> Add Discount (if any)</label>
                            </div>
                            <div class="col-sm">
                                <input type="number" name="order[discount]" class="form-control" id="discount-inp" min="0" step="0.01" placeholder="0.00" value="0.00">
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-8">
                                <h3 id="total-show" class="text-success"></h3>
                            </div>
                            <div class="col-sm-4 text-center">
                                <button class="btn btn-primary" id="total-calc-btn">Total</button>
                            </div>
                        </div>
                        <br>
        				<div class="text-center">
        					<input type="submit" name="submit" class="btn btn-primary" value="Place order" id="btn-place-order">
        				</div>
        			</form>
        		</div>
        	</div>
        </div>
	</div>
	<div class="final-bill p-3">
		
	</div>
	<?php 
		include("core/menu.php"); 
		include("core/header.php");
	?>

	<div class="container-fluid">
		<br>
		<div class="row">
			<div class="col-sm-3">
				<div class="text-center">
					<h2><i class="fas fa-tachometer-alt text-primary"></i> Dashboard</h2>
				</div><br>
				<div>
					<h3 id="date-today" class="p-3"></h3>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="text-center">
					<h4>Control Pannel <i class="fas fa-gamepad"></i></h4>
				</div><br>
				<div class="row">
					<div class="col text-center">
						<button class="btn btn-primary" id="new-order">
							New Order
						</button>
					</div>
					<div class="col text-center">
						<button class="btn btn-warning" onclick="window.location.href='order.php';">
							Search order
						</button>
					</div>
					<div class="col text-center">
						<button class="btn btn-secondary" onclick="window.location.href='products.php';">
							Products
						</button>
					</div>
					<div class="col text-center">
						<button class="btn btn-dark" onclick="window.location.href='report.php';">
							Reports
						</button>
					</div>
				</div><br>
				<div class="row">
					<div class="col-sm-6 text-center">
						<h4>Last 7-days sale</h4>
						<canvas id="myChart1" width="auto" height="250"></canvas>
					</div>
					<div class="col-sm-6">
                        <div class="row p-3">
                            <div class="col-sm">
                                <h4>Total stock Value</h4>
                                <h5><i class="fas fa-rupee-sign"></i> <?php echo $in_stock; ?></h5>
                            </div>
                            <div class="col-sm">
                                <h4>Total sale</h4>
                                <h5><i class="fas fa-rupee-sign"></i> <?php echo $sale_total; ?></h5>
                            </div>
                        </div><br>
						<div class="row p-3">
                            <div class="col-sm">
                                <h4>Total Retail Value of the stock</h4>
                                <h5><i class="fas fa-rupee-sign"></i> <?php echo $retail_value; ?></h5>
                            </div>
                            <div class="col-sm">
                                <h4>Total Wholesale Value of the stock</h4>
                                <h5><i class="fas fa-rupee-sign"></i> <?php echo $wholesale_value; ?></h5>
                            </div>
                        </div>  
                        <br>
                        <div class="bg-light p-3" id="low-stock">
                            <h2>
                                <i class="fas fa-exclamation-triangle text-danger" style="font-size: 40px;"></i>
                                Low stock detected !! Go to <a href="products.php">product</a> section to check out.
                            </h2>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="assets/JS/index_js.js"></script>
	<script src="assets/JS/common_js.js"></script>
	<script type="text/javascript">
        $(window).resize(function(){
            $('.create-order').css('max-height', $(window).height()-25);
        });
		$(document).ready(function() {
            $('.create-order').css('max-height', $(window).height()-24);
			var d = new Date();
			var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
			var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
			var date_elem = document.getElementById('date-today');
			date_elem.innerHTML = '';
			date_elem.innerHTML += days[d.getDay()]+`&nbsp;<i class="far fa-calendar-alt"></i><br>`+d.getDate()+` - `+months[d.getMonth()]+` - `+d.getFullYear();
    		$('[data-toggle="tooltip"]').tooltip();
    		var session_msg = '<?php echo $session->message(); ?>';
    		if (session_msg != '') {
        		var elem = document.getElementById('status-area');
        		elem.innerHTML = '';
        		elem.innerHTML += `<div class="alert alert-warning alert-dismissible fade show">
        								<button type="button" class="close" data-dismiss="alert">&times;</button>
        							<strong>System says : </strong> `+session_msg+`</div>`;
        		<?php $session->clear_message(); ?>
    		}
            get_last_7ds_analytics();
            check_stock();
		});
	</script>
</body>
</html>