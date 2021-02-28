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
	<title>Mini Cart | Reports</title>

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

  <!-- W3 css -->
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
  <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-colors-metro.css">

	<!-- Fontawesome 5.7.0 -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

	<!-- Local external style sheet -->
	<link rel="stylesheet" href="assets/CSS/style_index.css">
	<style>
		#general-report {
			display: none;
		}

		#report-on-demand {
			display: none;
		}

		.big-btn-gen-report {
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

		.big-btn-gen-report:hover {
			color: #fff;
		}

		.big-btn-report-on-demand {
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

		.big-btn-report-on-demand:hover {
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
					<h2><i class="fas fa-flag-checkered text-danger"></i> Reports</h2>
				</div><br>
				<div class="text-center">
          			<button class="btn big-btn-gen-report" onclick="get_gen_report();">
            			<h6>Sale summery <i class="fas fa-clipboard-list"></i></h6>
          			</button>
        		</div><br>
        		<div class="text-center">
         			<button class="btn big-btn-report-on-demand" onclick="open_search_order();">
         				<h6>Get report <i class="fas fa-search"></i></h6>
         			</button>
        		</div>
			</div>
			<div class="col-sm-9">
				<div class="text-center">
					<!-- <h3 class="display-4">Inventory Reports</h3> -->
					<div class="text-left p-3" id="general-report">
						<div style="text-align: right;font-size: 20px;">
        			<span style="float: left;"><h4 style="padding-left: 30vw;">Sale summery </h4></span>
        			<span style="cursor: pointer;" onclick="$('#general-report').css('display','none');">
              	<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            	</span>
          	</div><br>
            <div class="row p-3">
              <div class="col p-3 w3-metro-teal">
                <h4>Total Stock Cost Value</h4>
                <h5><i class="fas fa-rupee-sign"></i> <?php echo $in_stock; ?></h5>
              </div>
              <div class="col p-3 w3-metro-light-blue">
                <h4>Total sale inc. wholesale & retail both</h4>
                <h5><i class="fas fa-rupee-sign"></i> <?php echo $sale_total; ?></h5>
              </div>
              <div class="col p-3 w3-metro-dark-purple">
                <h4>Total Retail Value of the stock</h4>
                <h5><i class="fas fa-rupee-sign"></i> <?php echo $retail_value; ?></h5>
              </div>
              <div class="col p-3 w3-metro-blue">
                <h4>Total Wholesale Value of the stock</h4>
                <h5><i class="fas fa-rupee-sign"></i> <?php echo $wholesale_value; ?></h5>
              </div>
            </div>
					  
            
					</div>
					<!-- Report on demand -->
					<div class="p-3" id="report-on-demand">
						<div style="text-align: right;font-size: 20px;">
        			 		<span style="float: left;"><h4 style="padding-left: 30vw;">Get report by date</h4></span>
        					<span style="cursor: pointer;" onclick="$('#report-on-demand').css('display','none');">
              					<i class="fas fa-times text-danger" data-toggle="tooltip" data-placement="left" title="close"></i>
            				</span>
          				</div><br>
          				<form action="services/get_report_by_date.php" method="post" id="get-report-form">
          					<div class="row">
          						<div class="col-sm">
          							<label for="date"><strong>Enter the date to view report</strong></label>
          						</div>
          						<div class="col-sm">
          							<input type="date" name="date" class="form-control" required="">
          						</div>
          						<div class="col-sm text-center">
          							<input type="submit" class="btn btn-primary" name="submit" value="Search">
          						</div>
          					</div><br>
          					<div class="result-area">
                      <div class="text-center" id="date-for-report">
                        
                      </div>
          						<table width="100%" border="1" id="report-tbl">
          							
          						</table>
          					</div>
          				</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="assets/JS/report_js.js"></script>
	<script src="assets/JS/common_js.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			get_gen_report();
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