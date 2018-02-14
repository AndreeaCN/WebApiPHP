<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>The Service Provider</title>
  <meta name="description" content="Description of Services">
  <meta name="author" content="">
  <style>
      body { background-color: lightblue;}
      #top	{ background: navy; padding: 50px;}
      .menu {text-decoration: none; font-size:150%; color: white; padding: 50px;}
	  .api { color: black; font-size: 100%;}
	  
  </style>
</head>
<body>
<nav id="top"> 
<a href="index.php" class="menu">Home</a>
<a href="index.php?file=json" class="menu">Read Json File</a>
<a href="index.php?file=xml" class="menu">Read XML File</a>
<a href="index.php?file=csv" class="menu">Read CSV File</a>
</nav>
<?php 
//dynamically change the content in the page depending which link has been pressed
if ( filter_input(INPUT_GET, "file") == "json")
 	include_once("processJSONdata.php");
if (filter_input(INPUT_GET, "file") == "xml")
	include_once("processXMLdata.php");
if (filter_input(INPUT_GET, "file") == "csv")
	include_once("processCSVdata.php");
else
	include_once("viewGeneral.php");
?>

</body>
</html>