<?php
	
	include("classe/conexao.php");

	$codigo = $_GET['codigo'];




	

	$sql_busca = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA_PROVA WHERE COD_PROVA = :cod_prova') or die ("erro");

	oci_bind_by_name($sql_busca, ":cod_prova", $codigo);

	oci_execute($sql_busca);


	$sql_prova = oci_parse($ora_conexao, 'SELECT DESC_PROVA FROM TB_PROVA WHERE COD_PROVA = :cod_prova') or die ("erro");
	oci_bind_by_name($sql_prova, ":cod_prova", $codigo);
	oci_execute($sql_prova);

	$prova = oci_fetch_assoc($sql_prova);

?>
<!DOCTYPE html>
<html>
<head>
		
	<center><h3><?php echo $prova['DESC_PROVA']  ?></h3></center>

</head>	
<body>
		
		
	
			<ul style="list-style-type:none;">
			
				 <li><p class="titulo"> MASCULINO</p></li>
				 	<ul style="list-style-type:none;">
				 		<?php
						$sql_busca = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA_PROVA WHERE COD_PROVA = :cod_prova') or die ("erro");
						oci_bind_by_name($sql_busca, ":cod_prova", $codigo);
						oci_execute($sql_busca);
						while( ($linha = oci_fetch_assoc($sql_busca)) != false) { ?>
							<?php
							$cod_atleta = $linha['COD_ATLETA'];
							$sexo = "M";
							$sql_atleta = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA WHERE COD_ATLETA = :cod_atleta and SEXO = :sexo ') or die("erro");
							oci_bind_by_name($sql_atleta, ":cod_atleta", $cod_atleta);
							oci_bind_by_name($sql_atleta, ":sexo", $sexo);
							oci_execute($sql_atleta);
							if ( ($atleta = oci_fetch_assoc($sql_atleta)) != false) { ?>
							<li>
							<?php
							$sexo_atleta['M'] = "MASCULINO";
							$sexo_atleta['F'] = "FEMININO";
				
						 	 ?>
								
							<a href="index.php?p=ver_atleta&atleta=<?php echo $atleta['COD_ATLETA']; ?>"> <?php echo $atleta['APELIDO']; ?> </a> 
							</li>
						<?php }} ?>
				 	</ul>
			
		</ul>
	
		 <ul style="list-style-type:none;">
			
				 <li><p class="titulo"> FEMININO</p></li>
				 	<ul style="list-style-type:none;">
				 		<?php
						$sql_busca = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA_PROVA WHERE COD_PROVA = :cod_prova') or die ("erro");
						oci_bind_by_name($sql_busca, ":cod_prova", $codigo);
						oci_execute($sql_busca);
						while( ($linha = oci_fetch_assoc($sql_busca)) != false) { ?>
							<?php
							$cod_atleta = $linha['COD_ATLETA'];
							$sexo = "F";
							$sql_atleta = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA WHERE COD_ATLETA = :cod_atleta and SEXO = :sexo ') or die("erro");
							oci_bind_by_name($sql_atleta, ":cod_atleta", $cod_atleta);
							oci_bind_by_name($sql_atleta, ":sexo", $sexo);
							oci_execute($sql_atleta);
							if ( ($atleta = oci_fetch_assoc($sql_atleta)) != false) { ?>
							<li>
							<?php
							$sexo_atleta['M'] = "MASCULINO";
							$sexo_atleta['F'] = "FEMININO";
				
						 	?>
								
							<a href="index.php?p=ver_atleta&atleta=<?php echo $atleta['COD_ATLETA']; ?>"> <?php echo $atleta['APELIDO']; ?> </a> 
							</li>
						<?php }} ?>
				 	</ul>
			
		</ul>
	
	
	



<a class="titulo" href="index.php?p=exibe_provas"> Voltar</a>
</body>
</html>