
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	
	<!--Import Google Icon Font-->
	<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<!--Import materialize.css-->
	<link type="text/css" rel="stylesheet" href="style/materialize.min.css"  media="screen,projection"/>
	
	<link type="text/css" rel="stylesheet" href="style/style.css">
	
</head>
<body class="twitter-login">
	
	<section class="container center" id="login-container">
		<div class="triangle"></div>
		
		<div class="row">
			<div id="login-card" class="card col sm12 m8">
				<div class="card-content">
					<span class="card-title"> Authenticate at twitter.com below to use GlittR! </span>
					<p>Opens in new window... soon</p>
				</div>
				<!-- button to redirect to twitter -->
				<div class="card-action">
					<form action="php/twitter_login.php">
						<button class="btn waves-effect waves-light" type="submit">Sing in to Twitter</button>
					</form>
				</div>
			</div>
		</div>
	</section>
	
	
	<!-- jQuery. Only need it here for materialize js -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- materialize js -->
	<script type="text/javascript" src="script/materialize.min.js"></script>
</body>
</html>
