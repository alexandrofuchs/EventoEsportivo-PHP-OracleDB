<?php 

	ini_set ('display_errors', 0);
    error_reporting (0);



	include("classe/conexao.php");

	$sql_code = oci_parse($ora_conexao, 'SELECT * FROM TB_MODALIDADE') or die ("erro");
	oci_execute($sql_code);



	if(isset($_POST['confirmar'])){


		//REGISTRO DOS DADOS

		if(!isset($_SESSION))
			session_start();

		foreach ($_POST as $chave => $valor) {
			$_SESSION[$chave] = $valor;
		}

		//VALIDACAO DOS DADOS
		if($_SESSION['modalidade'] == 0)
			$erro[] = "Selecione a modalidade.";

		if(strlen($_SESSION['descricao']) == 0)
			$erro[] = "Preencha a descrição.";
		
				
		//INSERSAO NO BANCO

		if(!isset($erro)){
			$erro = 0;
		}

		$pkCount = (is_array($erro) ? count($erro) : 0);
		if($pkCount == 0){

			
			$descprova  	= $_SESSION['descricao'];
			$descprova      = strtoupper($descprova);
 			$codmodalidade  = $_SESSION['modalidade'];
			$pontos         = $_SESSION['pontos'];




			$sql_inserir = oci_parse($ora_conexao, 'INSERT INTO TB_PROVA (COD_PROVA, COD_MODALIDADE, DESC_PROVA, PONTOS) 
													VALUES(SQ_PROVA.NEXTVAL, :cod_modalidade, :desc_prova, :pontos)');

			oci_bind_by_name($sql_inserir, ":desc_prova", $descprova);
			oci_bind_by_name($sql_inserir, ":cod_modalidade", $codmodalidade);
			oci_bind_by_name($sql_inserir, ":pontos", $pontos);

			$r = oci_execute($sql_inserir, OCI_NO_AUTO_COMMIT);
            if (!$r) {
            	$e = oci_error($sql_inserir);  
            	print " Erro: " . htmlentities($e['message']);
            	oci_rollback($ora_conexao);
            }else{
               	oci_commit($ora_conexao);

            	$modalidade = $_SESSION['modalidade'];

                $sql_atleta = oci_parse($ora_conexao, 'SELECT COD_ATLETA FROM TB_ATLETA WHERE COD_MODALIDADE = :cod_modalidade' ) or die("erro");
                
                oci_bind_by_name($sql_atleta, ":cod_modalidade", $modalidade);
                oci_execute($sql_atleta);
               
                	
				
				$sql_prova = oci_parse($ora_conexao, 'SELECT COD_PROVA FROM TB_PROVA WHERE COD_MODALIDADE = :cod_modalidade AND DESC_PROVA = :desc_prova') or die ("erro");
				oci_bind_by_name($sql_prova, ":cod_modalidade", $modalidade);
				oci_bind_by_name($sql_prova, ":desc_prova",     $descprova);
				oci_execute($sql_prova);
				$l_prova = oci_fetch_assoc($sql_prova);
				$codP = $l_prova['COD_PROVA'];

				while(($l_atleta = oci_fetch_assoc($sql_atleta)) != false){
						$codA = $l_atleta['COD_ATLETA'];
						$sql_atl_prv = oci_parse($ora_conexao, 'INSERT INTO TB_ATLETA_PROVA (COD_ATLETA_PROVA, COD_ATLETA, COD_PROVA)
														VALUES (SQ_ATLETA_PROVA.NEXTVAL, :cod_atleta, :cod_prova)') or die("erro");
						oci_bind_by_name($sql_atl_prv, ":cod_atleta", $codA);
						oci_bind_by_name($sql_atl_prv, ":cod_prova",  $codP);
						$r_insert= oci_execute($sql_atl_prv);
						if (!$r_insert) {
           					$e_insert = oci_error($sql_atl_prv);  
           					print " Erro: " . htmlentities($e_insert['message']);
           					oci_rollback($ora_conexao);
           			   }else{
               				oci_commit($ora_conexao);
							}
					}


	                  unset($_SESSION['descricao'],
             	            $_SESSION['modalidade'],
                            $_SESSION['pontos']); 
                      
			           echo "<script> location.href='index.php?p=exibe_provas'; </script>";
		            }
		} 
		if($pkCount > 0){
		  echo "<div class= 'erro'>";
	      foreach ($erro as $valor) {
	      	echo "$valor <br>";
		  }
		  echo "</div>"; 
	    }
	  }
	
	
?>


<html>
<head>
	<title>Cadastrar Prova</title>
	

</head>
<body>
<center>
<form action="index.php?p=cadastrar_prova" method="POST">
	<table>
		<tr>
			<td>
				<label for="modalidade"> Modalidade</label>
			</td>
			<td>
				<select name="modalidade">
					<option value="0">Selecione</option>
					<?php while ( ($linha = oci_fetch_assoc($sql_code))  != false){ ?>
						<option value="<?php echo $linha['COD_MODALIDADE']  ?>"> <?php echo $linha['DESC_MODALIDADE']  ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="descricao">Descrição</label>
			</td>
			<td>	
				<input type="text" value="<?php echo $_SESSION['descricao'] ?>" name="descricao" size="50">
			</td>
		</tr>
		<tr>
			<td>
				<label for="pontos">Pontos</label>
			</td>
			<td>
				<input type="number" value="<?php echo $_SESSION['pontos'] ?>" name="pontos" min="0">
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td>
				<input type="submit" value="Cadastrar" name="confirmar">
			</td>
		</tr>
	</table>
</form>
</center>
<a href="index.php?p=exibe_provas"> Voltar</a>
</body>
</html>