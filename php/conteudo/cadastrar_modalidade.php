<?php 
	include("classe/conexao.php");

	if(isset($_POST['confirmar'])){


		//REGISTRO DOS DADOS

		if(!isset($_SESSION))
			session_start();

		foreach ($_POST as $chave => $valor) {
			$_SESSION[$chave] = $valor;
		}

		//VALIDACAO DOS DADOS
		
		if(strlen($_SESSION['descricao']) == 0)
			$erro[] = "Preencha a descrição.";
		
		//INSERSAO NO BANCO

		
		if(!isset($erro)){
			$erro = 0;
		}

		$pkCount = (is_array($erro) ? count($erro) : 0);
		if($pkCount == 0){

			
			$desc_modalidade =  $_SESSION['descricao'];
			$desc_modalidade = strtoupper($desc_modalidade);

			$sql_inserir = oci_parse($ora_conexao, 'INSERT INTO TB_MODALIDADE(COD_MODALIDADE, DESC_MODALIDADE) 
													VALUES(SQ_MODALIDADE.NEXTVAL, :desc_modalidade)');

			oci_bind_by_name($sql_inserir, ":desc_modalidade", $desc_modalidade);
			
		    $r = oci_execute($sql_inserir, OCI_NO_AUTO_COMMIT);
           		if (!$r) {
            		$e = oci_error($sql_inserir);  
            		print " Erro: " . htmlentities($e['message']);
            		oci_rollback($ora_conexao);
             	}else{
               		oci_commit($ora_conexao);
               		unset($_SESSION['descricao']); 
                      
			       echo "<script> location.href='index.php?p=exibe_provas'; </script>";
		            }
		}
	}
?>

<html>
<head>
	<title> Cadastrar Modalidade</title>
</head>
<body>

	<form action="index.php?p=cadastrar_modalidade" method="POST"> 
		<table class="titulo">
			<tr>
				<td>
					<label for="descricao">Nome/Descrição Modalidade: </label>
				</td>
				<td>
					<input type="text" name="descricao" size=50>
				</td>
				<td><input type="submit" name="confirmar" value="Cadastrar"></td>
			</tr>
		</table>
	</form>
	<table class="titulo">
		<tr>
			<td> <a href="index.php?p=exibe_provas"> Voltar </a> </td> 
		</tr>
	</table>	

</body>
</html>