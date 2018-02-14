<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html ng-app> <!-- The Angular App is started at this point-->
	<head>
		<title>Welcome to Smart Home</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		
		<!--Load the Angular.JS-->
		<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.0.5/angular.min.js"></script>
		</head>
	<body class="landing">
	<?php include_once("controller.php"); ?>

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				
				<!-- Banner -->
					<section id="banner">
						<div class="inner">
							<h2>Welcome to Smart Homes</h2>
							<p>A service built for your needs<br />
							 Keep your energy usage under control</p>
							<ul class="actions">
								<li><a href="#" class="button special">Login</a></li>
							</ul>
						</div>
						<a href="#one" class="more scrolly">Search Data:</a>
					</section>
				

				<!-- FORM for creating the parameters for the HTTP request -->
					<section id="one" class="wrapper style3 special">
					    <div class="inner">
						<!-- form submits to the separate templateCharts file-->
							<form name="myForm" action="controller.php" method="POST">	 
								<div class="row uniform">
									<div class="6u 12u$(xsmall)">
									    <div class="select-wrapper">										  
										   <label for ="apid">Select the set of data you would like to view:  </label>	
										   <!-- create an angular model of the first parameter, this  allows for certain parts of the form to be hidden-->
										   <!-- the required part ensures form is not submitted without a selection being made first-->
												 <select ng-model="action" name="action" id="demo-category" required>												 
													  <option value ="">--Option--</option>
													  <option value="get_apartment">Apartment Readings</option>
													  <option value="get_room">Room Readings</option>
												  </select>												
									     </div>
									</div>
									<div class="6u 12u$(xsmall)">
									    <div class="select-wrapper">
												<label for ="apid">Select Apartment:  </label>
                                             <!-- Set another angular model that sets 2 cases for the Room select drop down, ensures the correct room ids are selected for the apartment-->												
												<select name="apartment_id" id="apid" ng-model="rid" required>	
												      <option value="">--Select Apartment--</option>
													  <option value="1">Apartment 1</option>
													  <option value="2">Apartment 2</option>
												</select>
									     </div>
									</div>
									
									<div class="12u$">
									            <label for="intv">Select the time range for your readings:</label>
												<input type="radio" id="intv_all" value="all" name="intv" checked>
												<label for="intv_all">All</label>
									</div>
                                    <div class="4u 12u$(small)">
												<input type="radio" id="intv_day" value="1 DAY" name="intv">
												<label for="intv_day">1 Day</label>
									</div>
									<div class="4u 12u$(small)">
												<input type="radio" id="intv_week" value="1 WEEK" name="intv">
												<label for="intv_week">1 Week</label>
									</div>
									<div class="4u 12u$(small)">
												<input type="radio" id="intv_month" value="1 MONTH" name="intv">
												<label for="intv_month">1 Month</label>
									</div>	
 									<!-- use an angular switch statement that determines what part of the form will be showed based on the value selected for "action"--->
									<div class="12u$" ng-switch="action">
									          <!-- for apartment values-->
												<div ng-switch-when="get_apartment">
												    <p>Select Meter Readings:</p>	
                                                     <div class="select-wrapper">							 
															<select name="readings" id="readings" >								     
																<option value ="">--Meter Reading--</option>
																<option value ="all">All Meters</option>
																<option value ="eg">Electricity and Gas</option>
																<option value ="hcw">Hot and Cold Water</option>																
															</select>
													 </div>												
												</div>
												<!-- for room values-->
												<div ng-switch-when="get_room" class="12u$">	
												      <!-- Switch select box for Apartment 1 or Apartment 2-->
													  <div ng-switch="rid">
													         <p>Select the Room</p>
															 <div class="select-wrapper" ng-switch-when="1">							 
																	<select name="room_id" id="roomid" ng-required="rid">								     
																		<option value ="">--Room--</option>
																		<option value ="100">Living</option>
																		<option value ="101">Bedroom 1</option>
																		<option value ="102">Bedroom 2</option>
																		<option value ="103">Kitchen</option>
																	</select>
															 </div>
															<div class="select-wrapper"  ng-switch-when="2">															    
																 <select name="room_id" id="roomid" ng-required="rid">			
																		 <option value ="">--Room--</option>
																		<option value ="200">Living</option>
																		<option value ="201">Bedroom 1</option>
																		<option value ="202">Bedroom 2</option>
																		<option value ="203">Kitchen</option>
																  </select>
															</div>
													</div>
													<div>
															<p>Select Which Room Values you would like to view</p>
															<div>
																<input type="radio" id="values-all" value="all" name="values" checked>
																<label for="values-all">All</label>
															
															
																<input type="radio" id="values-ht" value="ht" name="values"/> 
																<label for="values-ht">Humidity and Temperature</label>
																														
																<input type="radio" id="values-sco" value="sco" name="values"/>
																<label for="values-sco">CO2</label>
													         </div>
                                                     </div>													
											   </div>
									 </div>
									 <div class="12u$">
												<ul name="submits" class="actions">
													<li><button type="submit" name="format" value="json" class="special">View Data Graph</input></li>
													<li><button type="submit" name="format" value="xml" class="special"/>View data as XML</li>
													<li><input type="reset" value="Reset" /></li>
												</ul>
												<script>
												
												
									</div>
								</div>
							</form>
						</div>
					</section>
               
				<!-- CTA -->
					<section id="cta" class="wrapper style4">
						<div class="inner">
							
						</div>
					</section>

				<!-- Footer -->
					<footer id="footer">
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
							<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
						</ul>
						<ul class="copyright">
							<li>&copy; Untitled</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
						</ul>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>