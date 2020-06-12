<?php
	include("classe/conexao.php");

	
 ?>

<head>
<center>
	<p class=espaco></p>
	<a href="index.php?p=cadastrar_prova" class="titulo" >| Cadastrar Prova |</a>
	<a href="index.php?p=cadastrar_modalidade" class="titulo">| Cadastrar Modalidade |</a>				
				

	
	<center><h1>PROVAS</h1></center>

	<form action="index.php?p=exibe_provas" method="POST">
		<select  name="modalidade">
			<option value="">< Por Modalidade ></option>
			<?php
				$sql_busca = oci_parse($ora_conexao, 'SELECT * FROM TB_MODALIDADE');
				oci_execute($sql_busca);
				while( ($linha = oci_fetch_assoc($sql_busca)) != false ){ ?>
					<option value="<?php echo $linha['COD_MODALIDADE'] ?>"> <?php echo $linha['DESC_MODALIDADE'] ?></option>
			<?php
				}
			?>
		</select>
		<input type="submit" name="confirmar" value="buscar"></center>
	</form>

</center>
</head>	
<body>
	<?php  
		if(isset($_POST['confirmar'])){

		

			if(!isset($_SESSION))
				session_start();

			foreach ($_POST as $chave => $valor) {
				$_SESSION[$chave] = $valor;
			}

			if($_SESSION['modalidade'] == NULL){
				echo "<script> 
            	  	location.href='index.php?p=exibe_provas';
            	  </script>";
			}

			
			$sql_busca = oci_parse($ora_conexao, 'SELECT * FROM TB_MODALIDADE') or die("erro");
				oci_execute($sql_busca);
				while( ($linha = oci_fetch_assoc($sql_busca)) != false ) {
					if($linha['COD_MODALIDADE'] == $_SESSION['modalidade']){ ?>
						<center>
							<p class="espaco"></p>
							<a class="titulo" href="index.php?p=exibe_provas"> | Todas as Modalidades |</a>
							<p class="espaco"></p>
							<table border=2 cellpadding=8 bgcolor="#E6E8FA" cellspacing="0">
								<tr class=titulo>
									<td><center>DESCRICAO</center></td>
									<td><center>OPÇÕES</center></td>
								</tr>
								<?php 
									$codigo = $linha['COD_MODALIDADE'];
									$sql_modalidade = oci_parse($ora_conexao, 'SELECT * FROM TB_PROVA WHERE COD_MODALIDADE = :cod_modalidade ORDER BY DESC_PROVA ') or die("erro");
									oci_bind_by_name($sql_modalidade, ":cod_modalidade", $codigo);
 									oci_execute($sql_modalidade);
 								 	while (($row = oci_fetch_assoc($sql_modalidade)) != false ) {
								?>
								<tr>
									<td><?php  echo $row['DESC_PROVA']; ?></td>
									<td><a href="index.php?p=ver_atleta_por_provas&codigo=<?php echo $row['COD_PROVA'] ?>"><button>Ver Atletas</button></a>
		   								<a href="index.php?p=editar_prova&codigo=<?php echo $row['COD_PROVA'] ?>"><button>Editar</button></a>
										<a href="javascript: if(confirm('Realmente deseja excluir: <?php echo $row['DESC_PROVA'];?> ?')) location.href='index.php?p=deletar_prova&codigo=<?php echo $row['COD_PROVA']; ?>'"><button>Deletar</button></a>
									</td>
								</tr>
								<?php
							    	}
					} 
			}
					 		?>
				</table>
			</center>

	
	
	<?php 
		}else{
	?>					
			<center>
				<p class="espaco"></p>
				<table border=2 cellpadding=8 bgcolor="#E6E8FA" cellspacing="0">
	
					<tr class=titulo>
						<td><center>DESCRICAO</center></td>
		
						<td><center>OPÇÕES</center></td>
					</tr>
					<tr>
						<?php 
							$stid = oci_parse($ora_conexao, 'SELECT * FROM TB_PROVA ORDER BY DESC_PROVA');
   							 oci_execute($stid);
   							 while (($row = oci_fetch_assoc($stid)) != false) { 
						?>

						<td><?php  echo $row['DESC_PROVA']; ?></td>
		
						<td><a href="index.php?p=ver_atleta_por_provas&codigo=<?php echo $row['COD_PROVA'] ?>"><button>Ver Atletas</button></a>
		   					<a href="index.php?p=editar_prova&codigo=<?php echo $row['COD_PROVA'] ?>"><button>Editar</button></a>
							<a href="javascript: if(confirm('Realmente deseja excluir: <?php echo $row['DESC_PROVA'];?> ?')) location.href='index.php?p=deletar_prova&codigo=<?php echo $row['COD_PROVA']; ?>'"><button>Deletar</button></a> 
						</td>
					</tr>
					<?php
						 } 
					 ?>
				</table>
			</center>



	<?php
		}
	?>
<p class=espaco></p>
<a class="titulo" href="index.php?p=inicial"> < Voltar </a>

</body>
</html>