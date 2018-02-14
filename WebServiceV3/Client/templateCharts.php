<!DOCTYPE html>  
<html lang="en">  
    <head>
	    <title>Smart Home Graphs</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
          <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
                google.charts.load('current', {packages: ['corechart', 'line']});  //load the Line chart package             
				       </script>
       
    </head>
    <body>
	    <div id="page-wrapper">
	   	<?php include_once("controller.php"); ?>	
		<header id="header">
						<h1><a href="index.php">Smart Homes</a></h1>
						<nav id="nav">
							<ul class="actions">
										<li><a href="index.php" class="button special">Back</a></li>
										
									</ul>
						</nav>
		</header>

		<section class="wrapper style1 special">
      
	    <div> 
		   <h1>Data in a Line Chart</h1>
	   </div>
         <div id="chart_div" class="inner"></div>
		
		<div> 
		<script>
		   google.charts.setOnLoadCallback(drawBackgroundColor);
		   //create a function that draws the chart
				function drawBackgroundColor() {
					// the whole data table is formatted by the API, brought in here as variable from controller.php
					  var data = new google.visualization.DataTable(<?=$newdata?>);
                     // set a few options for the chart
					  var options = {
							hAxis: {
							  title: 'Time'
							},
							vAxis: {
							  title: 'Readings'
							},
							backgroundColor: '#f1f8e9',
							width: 1200,
							height: 600
						  };
					// draw the chart in the div with the ID chart_div
					 var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                      chart.draw(data, options);
                }
		</script>
	   </div>
      
		</section>
		
			<footer id="footer">
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
							<li><a href="#" class="icon fa-envelope-o"><span class="label">Email</span></a></li>
						</ul>
						
					</footer>

		</div>
    </body>
</html> 