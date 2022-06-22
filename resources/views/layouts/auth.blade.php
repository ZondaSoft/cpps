<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="/css/stylelogin.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<title>CPPS (acceso)</title>
</head>
<body>
	<div class="container" id="container">
		<div class="form-container log-in-container">
            
			@yield('dataform')
            
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-right">
					@yield('logo')
					@yield('info')
                    <p>En caso de tener problemas en el acceso notificar via email a zondasoftware@gmail.com o al cel. +54 3875082142.</p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
