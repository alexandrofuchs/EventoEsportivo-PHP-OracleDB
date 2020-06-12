<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Evento Esportivo</title>
	<style>
		.principal{
			width: 60%;
			margin-right: auto;
			margin-bottom: auto;
			margin-top:  50px;
			margin-left: auto;	
			background:  #DCDCDC;
			border: 1;
			padding: 20px;
    		box-shadow: 0px 0px 10px 12px #8B8682;
		}
		body{
			margin-right: auto;
			margin-bottom: auto;
			margin-top:  auto;
			margin-left: auto;	
			background:  #F8F8FF;
			padding: 20px;
			font-family: calibri;
			font-size: 18px;
			
		}
		.espaco{ height: 15px; display: block;}
		input{ font-size: 15px; padding: 5px; }
		button{ font-size: 12px; padding: 2px; }
		.titulo{font-weight: bold;
				font-family: Times New Roman;
				font-size: 20px; 
				margin: 0 auto;}
		a:link {
			text-decoration:none;
			color: #4F4F4F;
			}
		a:visited {
			text-decoration:none;
			color: #4F4F4F;	
		}
		
	</style>
</head>
<body>
	<div class=principal>
		<?php 
			if(isset($_GET['p'])){

				$pagina = $_GET['p'].".php";
				if(is_file("conteudo/$pagina")){
					include("conteudo/$pagina");
				}else{
					include("conteudo/404.php");
				}
			}else{
				include("conteudo/inicial.php");
			}



		?>



	</div>
</body>
</html>