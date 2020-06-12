<?php 


	include("classe/conexao.php");

	$cod_atleta = intval($_GET['atleta']);
	
	
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
				$nome           = ucwords($nome);
				$apelido        = $_SESSION['apelido'];
				$apelido        = strtoupper($apelido);
				$sexo           = $_SESSION['sexo'];
				$cpf            = $_SESSION['cpf'];
				$telefone       = $_SESSION['telefone'];
				$dt_nascimento  = "$dia/$mes/$ano";
				$modalidade     = $_SESSION['modalidade'];
				
		
  				$sql_update = oci_parse($ora_conexao, 'UPDATE TB_ATLETA SET
  												 NOME     		= :nome,
  												 APELIDO  		= :apelido,
  												 SEXO     		= :sexo,
  												 CPF      		= :cpf,
  												 TELEFONE 		= :telefone,
  												 DT_NASCIMENTO  = :dt_nascimento,
  												 COD_MODALIDADE = :cod_modalidade 
  												 WHERE COD_ATLETA = :cod_atleta');

				oci_bind_by_name($sql_update, ":cod_atleta",     $cod_atleta  );
				oci_bind_by_name($sql_update, ":nome",           $nome         );
				oci_bind_by_name($sql_update, ":apelido",        $apelido	   );
				oci_bind_by_name($sql_update, ":sexo",           $sexo         );
				oci_bind_by_name($sql_update, ":cpf",            $cpf          );
				oci_bind_by_name($sql_update, ":telefone",       $telefone     );
				oci_bind_by_name($sql_update, ":dt_nascimento",  $dt_nascimento);
				oci_bind_by_name($sql_update, ":cod_modalidade", $modalidade   );
			
		    	$r = oci_execute($sql_update, OCI_NO_AUTO_COMMIT);
            	if (!$r) {
            		$e = oci_error($sql_update);  
            		print " Erro: " . htmlentities($e['message']);
            		oci_rollback($ora_conexao);
                }else{
                	oci_commit($ora_conexao);
                	oci_free_statement($sql_update);

               			            	

                	$sql_prova = oci_parse($ora_conexao, 'SELECT TB_ATLETA.COD_ATLETA, TB_PROVA.COD_MODALIDADE, TB_PROVA.COD_PROVA FROM TB_ATLETA, TB_PROVA WHERE TB_ATLETA.COD_MODALIDADE = TB_PROVA.COD_MODALIDADE AND TB_ATLETA.COD_ATLETA = :cod_atleta') or die("erro");
                
                	oci_bind_by_name($sql_prova, ":cod_atleta", $cod_atleta);
                	oci_execute($sql_prova);


                	
                	$sql_delete = oci_parse($ora_conexao, 'DELETE FROM TB_ATLETA_PROVA WHERE COD_ATLETA = :cod_atleta ') or die ("erro");
                			//oci_bind_by_name($sql_delete, ":cod_prova", $codP);
                			oci_bind_by_name($sql_delete, ":cod_atleta", $cod_atleta);
                			$r_delete = oci_execute($sql_delete, OCI_NO_AUTO_COMMIT);
                			if (!$r_delete) {
            					$e_delete = oci_error($sql_delete);  
            					print " Erro: " . htmlentities($e_delete['message']);
            					oci_rollback($ora_conexao);
              				  }else{             	

                					while ( ($l_prova = oci_fetch_assoc($sql_prova)) != false){

                						$codP = $l_prova['COD_PROVA'];
          						
              		                	               		

                  				  		$sql_insere = oci_parse($ora_conexao, 'INSERT INTO TB_ATLETA_PROVA (COD_ATLETA_PROVA, COD_ATLETA, COD_PROVA)
              				  											   VALUES(SQ_ATLETA_PROVA.NEXTVAL, :cod_atleta, :cod_prova)') or die ("erro");
              				  			oci_bind_by_name($sql_insere, ":cod_prova",  $codP);
                						oci_bind_by_name($sql_insere, ":cod_atleta", $cod_atleta);
                						$r_insere = oci_execute($sql_insere, OCI_NO_AUTO_COMMIT);
                						if(!$r_insere){
            								$e_insere = oci_error($sql_insere);  
            								print " Erro: " . htmlentities($e_insere['message']);
            								oci_rollback($ora_conexao);
            							}else{
            								oci_commit($ora_conexao);
                          				}
                	
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
    		if($pkCount > 0){
		 		 echo "<div class= 'erro'>";
	     		 foreach ($erro as $valor) {
	      			echo "$valor <br>";
		  		 }
				 echo "</div>"; 
			 }
		}
	}else{

			$sql_buscar = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA WHERE COD_ATLETA = :cod_atleta') or die("erro");

			oci_bind_by_name($sql_buscar, ":cod_atleta" , $cod_atleta);

			oci_execute($sql_buscar);
           	
		    $linha = oci_fetch_assoc($sql_buscar);

		    

			$data = explode('-', $linha['DT_NASCIMENTO']);

			switch( $data[1]){				
				case "01"  :    $_SESSION['dia'] = "1"; break;
				case "02"  :    $_SESSION['dia'] = "2"; break;
				case "03"  :    $_SESSION['dia'] = "3"; break;
				case "04"  :    $_SESSION['dia'] = "4"; break;
				case "05"  :    $_SESSION['dia'] = "5"; break;
				case "06"  :    $_SESSION['dia'] = "6"; break;
				case "07"  :    $_SESSION['dia'] = "7"; break;
				case "08"  :    $_SESSION['dia'] = "8"; break;
				case "09"  :    $_SESSION['dia'] = "9"; break;
			default        :    $_SESSION['dia'] = $data[0];
			}

			switch( $data[1]){				
				case "JAN"  :    $_SESSION['mes'] = 1; break;
				case "FEB"  :    $_SESSION['mes'] = 2; break;
				case "MAR"  :    $_SESSION['mes'] = 3; break;
				case "APR"  :    $_SESSION['mes'] = 4; break;
				case "MAY"  :    $_SESSION['mes'] = 5; break;
				case "JUN"  :    $_SESSION['mes'] = 6; break;
				case "JUL"  :    $_SESSION['mes'] = 7; break;
				case "AUG"  :    $_SESSION['mes'] = 8; break;
				case "SEP"  :    $_SESSION['mes'] = 9; break;
				case "OCT"  :    $_SESSION['mes'] = 10; break;
				case "NOV"  :    $_SESSION['mes'] = 11; break;
				case "DEC"  :    $_SESSION['mes'] = 12; break;
			}
			
			switch( $data[2]){				
				case "90"  :    $_SESSION['ano'] = "1990"; break;
				case "91"  :    $_SESSION['ano'] = "1991"; break;
				case "92"  :    $_SESSION['ano'] = "1992"; break;
				case "93"  :    $_SESSION['ano'] = "1993"; break;
				case "94"  :    $_SESSION['ano'] = "1994"; break;
				case "95"  :    $_SESSION['ano'] = "1995"; break;
				case "96"  :    $_SESSION['ano'] = "1996"; break;
				case "97"  :    $_SESSION['ano'] = "1997"; break;
				case "98"  :    $_SESSION['ano'] = "1998"; break;
				case "99"  :    $_SESSION['ano'] = "1999"; break;
				case "00"  :    $_SESSION['ano'] = "2000"; break;
				
			}

			if(!isset($_SESSION))
			session_start();



			$_SESSION['nome']          = $linha['NOME'];
            $_SESSION['apelido']       = $linha['APELIDO'];
            $_SESSION['sexo']          = $linha['SEXO'];
            $_SESSION['cpf']           = $linha['CPF']; 
            $_SESSION['telefone']      = $linha['TELEFONE'];
            $_SESSION['modalidade']    = $linha['COD_MODALIDADE'];
                                         $linha['COD_MODALIDADE'];

   		 }
	
?>


<html>
<head>
	<title>Alterar Dados</title>
</head>
<body>
	<h1>Alterar Dados</h1>
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
		
    <form action="index.php?p=editar_atleta&atleta=<?php echo $cod_atleta ?>" method="POST"> 
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
			<input type="radio" name="sexo" value="M"  <?php if($_SESSION['sexo'] == "M" ) { echo "CHECKED"; } ?> /> Masculino<br/>
			<input type="radio" name="sexo" value="F"  <?php if($_SESSION['sexo'] == "F" ) { echo "CHECKED"; } ?> /> Feminino<br/>
		</td>
	</tr>	
	<tr>
		<td>
			<label for="telefone">Telefone</label>
		</td>
		<td>
			<input type="text" name="telefone" maxlength="13" value="<?php echo $_SESSION['telefone']?> "OnKeyPress="formatar('##-####-####', this)">
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
		<td><input type="submit" name="confirmar" value="Confirmar"></td>
	</tr>
	</table>
</form>
<p class="espaco"></p>
<a class="titulo" href="index.php?=inicial"> Voltar</a>
</body>
</html>
