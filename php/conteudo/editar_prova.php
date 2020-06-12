<?php 

	include("classe/conexao.php");

	$codigo = intval($_GET['codigo']);



	



	if(isset($_POST['confirmar'])){


		//REGISTRO DOS DADOS

		if(!isset($_SESSION))
			session_start();

		foreach ($_POST as $chave => $valor) {
			$_SESSION[$chave] = $valor;
		}

		//VALIDACAO DOS DADOS
		if(strlen($_SESSION['modalidade']) == 0)
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




			$sql_inserir = oci_parse($ora_conexao, 'UPDATE TB_PROVA SET 
													COD_MODALIDADE = :cod_modalidade, 
													DESC_PROVA = :desc_prova, 
													PONTOS = :pontos WHERE COD_PROVA = :codigo');
			oci_bind_by_name($sql_inserir, ":codigo", $codigo);
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
                unset($_SESSION['descricao'],
             	      $_SESSION['modalidade'],
                      $_SESSION['pontos']); 
                      
			       echo "<script> location.href='index.php?p=exibe_provas'; </script>";
		            }
		}
	}{

		$sql_buscar = oci_parse($ora_conexao, 'SELECT * FROM TB_PROVA WHERE COD_PROVA = :codigo') or die ("erro");

		oci_bind_by_name($sql_buscar, ":codigo", $codigo);

		oci_execute($sql_buscar);

		$linha_buscar = oci_fetch_assoc($sql_buscar);

		if(!isset($_SESSION))
			session_start();

		$_SESSION['descricao']  = $linha_buscar['DESC_PROVA'];
        $_SESSION['modalidade'] = $linha_buscar['COD_MODALIDADE'];
        $_SESSION['pontos']     = $linha_buscar['PONTOS'];




	}
?>


<html>
<head>
	<title>Cadastrar Prova</title>
</head>
<body>
<center>
<form action="index.php?p=editar_prova&codigo=<?php echo $codigo; ?>" method="POST">
	<table>
		<tr>
			<td>
				<label for="modalidade"> Modalidade</label>
			</td>
			<td>
				<select name="modalidade">
					<option>Selecione</option>
					<?php 
						$sql_code = oci_parse($ora_conexao, 'SELECT * FROM TB_MODALIDADE') or die ("erro");
						oci_execute($sql_code);
						while ( ($linha = oci_fetch_assoc($sql_code))  != false){ ?>
						<option value="<?php echo $linha['COD_MODALIDADE']  ?>" <?php if($_SESSION['modalidade'] == $linha['COD_MODALIDADE'] ){ echo "selected"; } ?> > <?php echo $linha['DESC_MODALIDADE']  ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="descricao">Descrição</label>
			</td>
			<td>	
				<input required type="text" value="<?php echo $_SESSION['descricao'] ?>" name="descricao">
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