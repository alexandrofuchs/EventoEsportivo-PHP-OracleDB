<?php 

	ini_set ('display_errors', 0);
    error_reporting (0);
		

	include("classe/conexao.php");


	if(isset($_POST['confirmar'])){


		//REGISTRO DOS DADOS

		if(!isset($_SESSION))
			session_start();

		foreach ($_POST as $chave => $valor) {
			$_SESSION[$chave] = $valor;
		}

		//VALIDACAO DOS DADOS
		if(strlen($_SESSION['nome']) == 0)
			$erro[] = "Preencha o nome.";

		if(strlen($_SESSION['apelido']) == 0)
			$erro[] = "Preencha o apelido.";
		
		if(strlen($_SESSION['cpf']) == 0)
			$erro[] = "Preencha o CPF.";
		
		if($_SESSION['sexo'] != "M" && $_SESSION['sexo'] != "F")
			$erro[] = "Selecione o sexo.";
		
		if(strlen($_SESSION['dia']) == 0)
			$erro[] = "Selecione o dia.";
		
		if(strlen($_SESSION['mes']) == 0)
			$erro[] = "Selecione o mes.";
		
		if(strlen($_SESSION['ano']) == 0)
			$erro[] = "Selecione o ano.";

		if(strlen($_SESSION['modalidade']) == 0)
			$erro[] = "Selecione a modalidade.";




		//INSERÇAO NO BANCO

		if(!isset($erro)){
			$erro = 0;
		}

		$pkCount = (is_array($erro) ? count($erro) : 0);
		if($pkCount == 0){

			$dia = $_SESSION['dia'];
			$ano = $_SESSION['ano'];

			switch( $_SESSION['mes']){				
				case "1"  :    $mes = "JAN"; break;
				case "2"  :    $mes = "FEB"; break;
				case "3"  :    $mes = "MAR"; break;
				case "4"  :    $mes = "APR"; break;
				case "5"  :    $mes = "MAY"; break;
				case "6"  :    $mes = "JUN"; break;
				case "7"  :    $mes = "JUL"; break;
				case "8"  :    $mes = "AUG"; break;
				case "9"  :    $mes = "SEP"; break;
				case "10" :    $mes = "OCT"; break;
				case "11" :    $mes = "NOV"; break;
				case "12" :    $mes = "DEC"; break;
			}
			
			
			$nome           = $_SESSION['nome'];
			$nome           =  ucwords($nome);
			$apelido        = $_SESSION['apelido'];
			$apelido        = strtoupper($apelido);
			$sexo           = $_SESSION['sexo'];
			$cpf            = $_SESSION['cpf'];
			$telefone       = $_SESSION['telefone'];
			$dt_nascimento  = "$dia/$mes/$ano";
			$dt_inscricao   = date("d/M/Y");
			$modalidade     = $_SESSION['modalidade'];

		
  			$sql_code = oci_parse($ora_conexao, 'INSERT INTO TB_ATLETA
												 (COD_ATLETA,
												  COD_MODALIDADE,
												  NOME,
												  APELIDO,
												  SEXO,
												  CPF,
												  TELEFONE,
												  DT_NASCIMENTO,
												  DT_INSCRICAO)
												  VALUES
												  (SQ_ATLETA.NEXTVAL,
												  :cod_modalidade,
												  :nome,
												  :apelido,
												  :sexo,
												  :cpf,
												  :telefone,
												  :dt_nascimento,
												  :dt_inscricao)') or die("erro");

			oci_bind_by_name($sql_code, ":nome",           $nome         );
			oci_bind_by_name($sql_code, ":apelido",        $apelido		 );
			oci_bind_by_name($sql_code, ":sexo",           $sexo         );
			oci_bind_by_name($sql_code, ":cpf",            $cpf          );
			oci_bind_by_name($sql_code, ":telefone",       $telefone     );
			oci_bind_by_name($sql_code, ":dt_nascimento",  $dt_nascimento);
			oci_bind_by_name($sql_code, ":dt_inscricao",   $dt_inscricao );
			oci_bind_by_name($sql_code, ":cod_modalidade", $modalidade   );
			
		    $r = oci_execute($sql_code, OCI_NO_AUTO_COMMIT);
            	if (!$r) {
            		$e = oci_error($sql_code);  
            		print " Erro: " . htmlentities($e['message']);
            		oci_rollback($ora_conexao);
                }else{
                	oci_commit($ora_conexao);


                	$modalidade = $_SESSION['modalidade'];

                	$sql_atleta = oci_parse($ora_conexao, 'SELECT COD_ATLETA FROM TB_ATLETA WHERE COD_MODALIDADE = :cod_modalidade AND APELIDO = :apelido');
                	oci_bind_by_name($sql_atleta, ":apelido",        $apelido);
                	oci_bind_by_name($sql_atleta, ":cod_modalidade", $modalidade);
                	oci_execute($sql_atleta);
                	$l_atleta = oci_fetch_assoc($sql_atleta);

                	
					$codA = $l_atleta['COD_ATLETA'];
					$sql_prova = oci_parse($ora_conexao, 'SELECT COD_PROVA FROM TB_PROVA WHERE COD_MODALIDADE = :cod_modalidade') or die ("erro");
					oci_bind_by_name($sql_prova, ":cod_modalidade", $modalidade);
					oci_execute($sql_prova);

					while(($l_prova = oci_fetch_assoc($sql_prova)) != false){
							$codP = $l_prova['COD_PROVA'];
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


                    unset($_SESSION['nome'],
                          $_SESSION['apelido'],
                          $_SESSION['sexo'], 
                          $_SESSION['cpf'], 
                          $_SESSION['telefone'], 
                          $_SESSION['dt_nascimento'], 
                          $_SESSION['modalidade']);
			       echo "<script> location.href='index.php?p=inicial'; </script>";
		            
			
				}
		}
	}
?>


<html>
<head>
	<title>Cadastro de Atleta</title>
</head>
<body>


	<h1>Cadastrar Atleta</h1>
	<?php 
		if($pkCount > 0){
		  echo "<div class= 'erro'>";
	      foreach ($erro as $valor) {
	      	echo "$valor <br>";
		  }
		  echo "</div>"; 
	    }
	?>

	<script>
		function formatar(mascara, documento){
  			var i = documento.value.length;
  			var saida = mascara.substring(0,1);
  			var texto = mascara.substring(i)
 	
  			if (texto.substring(0,1) != saida){
            	documento.value += texto.substring(0,1);
  			}
  		}
	</script>

	<form action="index.php?p=cadastrar_atleta" method="POST"> 
    <table>

    <tr>
		<td>
			<label for="nome">Nome*</label>
		</td>
		<td>
			<input required type="text" value="<?php echo $_SESSION['nome'] ?>" name="nome">
		</td>
	</tr>
	<tr>
		<td>
			<label for="apelido">Apelido*</label>
		</td>
		<td>
			<input required type="text" value="<?php echo $_SESSION['apelido'] ?>" name="apelido">
		</td>
	</tr>
	<tr>
		<td>
			<label for="cpf">CPF*</label>
		</td>
		<td>
			<input required type="text" name="cpf" maxlength="14" value="<?php echo $_SESSION['cpf'] ?>" OnKeyPress="formatar('###.###.###-##', this)"  >
		</td>
	</tr>
	<tr>
		<td>
			<label for="sexo">Sexo*</label>
		</td>
		<td>
			<input type="radio" name="sexo" value="M"  <?php if($_SESSION['sexo'] == "M" ) { echo "CHECKED"; } ?>/> Masculino<br/>
			<input type="radio" name="sexo" value="F"  <?php if($_SESSION['sexo'] == "F" ) { echo "CHECKED"; } ?>/> Feminino<br/>
		</td>
	</tr>	
	<tr>
		<td>
			<label for="telefone">Telefone</label>
		</td>
		<td>
			<input type="text" name="telefone" maxlength="13" value="<?php echo $_SESSION['telefone'] ?>"  OnKeyPress="formatar('##-####-####', this)">
		</td>
	</tr>
	<tr>
		<td>
			<label for="dt_nascimento">Data de Nascimento*</label>
		</td>
		<td>
			<select name="dia">
				<option value=""></option>
				<?php for ($d=1;$d<32;$d++){ ?>
						<option value="<?php echo $d ?>" <?php if($_SESSION['dia'] == $d ) { echo "selected"; } ?>> <?php echo $d ?></option>
				<?php } ?>
			</select>
			<select name="mes">
				<option value=""></option>
				<?php for ($m=1;$m<13;$m++){ ?>
						<option value="<?php echo $m ?>" <?php if($_SESSION['mes'] == $m ) { echo "selected"; } ?>> <?php echo $m ?></option>
				<?php } ?>
			</select>
			<select name="ano">
				<option value=""></option>
				<?php for ($a=1990;$a<2001;$a++){ ?>
						<option value="<?php echo $a ?>" <?php if($_SESSION['ano'] == $a ) { echo "selected"; } ?>> <?php echo $a ?></option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<label for="modalidade">Modalidade*</label>
		</td>
		<td>
			<select name="modalidade">
				<option value="">Selecione</option>
			<?php
				$consulta_modalidade = oci_parse($ora_conexao, 'SELECT * FROM TB_MODALIDADE') or die("erro");
				oci_execute($consulta_modalidade);
				while( ($linha = oci_fetch_assoc($consulta_modalidade)) != false){ 
			?>
				<option value="<?php echo $linha['COD_MODALIDADE'] ?>" <?php if($_SESSION['modalidade'] == $linha['COD_MODALIDADE'] ){ echo "selected"; } ?> > <?php echo $linha['DESC_MODALIDADE'] ?>	</option>
				<?php 
				} 
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="confirmar" value="Cadastrar"></td>
	</tr>
	</table>
				
</form>
<p class="espaco"></p>
<a class="titulo" href="index.php?=inicial"> Voltar</a>

</body>
</html>
