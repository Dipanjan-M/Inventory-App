	<nav class="navbar navbar-expand-sm bg-dark navbar-dark text-light">
  		<!-- Brand/logo -->
  		<span style="font-size:30px;cursor:pointer;padding-right: 2vw;" onclick="openNav()">
  			<i class="fa fa-bars"></i>
  		</span>
  		<span class="navbar-brand"> <i class="fas fa-stethoscope"></i> Piya Motors</span>
  		
  		<!-- Links -->
  		<ul class="navbar-nav" style="position: absolute;right: 1vw;">
    		<li class="nav-item">
      			<span class="nav-link text-light"> Welcome <?php echo $session->get_full_name(); ?></span>
    		</li>
    		<!-- <li class="nav-item">
    			<a href="settings.php" class="nav-link"> <i class="fas fa-user-plus" data-toggle="tooltip" title="Add new admin"></i> </a>
    		</li> -->
    		<li class="nav-item">
      			<a class="nav-link text-light" href="../logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
    		</li>
  		</ul>
	</nav>