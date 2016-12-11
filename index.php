<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<!--Let browser know website is optimized for mobile-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>TwittR</title>
	
	
	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="style/materialize.min.css"  media="screen,projection"/>
	
	
</head>
<body>
	<?php
		session_start();
		echo($_SESSION["oauth_token"]);
	?>
	
	<div ng-app="app" ng-controller="appController">
		<p>Name : <input type="text" ng-model="name"></p>
		<h1>Hello {{name}}</h1>
	</div>
	
	
	<!-- angular.js -->
	<script src="script/angular.min.js"></script>
	<!-- my angular scripts -->
	<!-- app -->
	<script src="script/app.js"></script>
	<!-- controller -->
	<script src="script/appController.js"></script>
	
	<!-- jQuery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- materialize js -->
	<script type="text/javascript" src="script/materialize.min.js"></script>
</body>
</html>