<?php

	include("classe/conexao.php");

	$codigo = intval($_GET['atleta']);

	$sql_code = oci_parse($ora_conexao, 'DELETE FROM TB_ATLETA WHERE COD_ATLETA = :codigo') or die ("erro");

	oci_bind_by_name($sql_code, ":codigo", $codigo);

							$sql_delete = oci_parse($ora_conexao, 'DELETE FROM TB_ATLETA_PROVA WHERE COD_ATLETA = :cod_atleta ') or die ("erro");
                			//oci_bind_by_name($sql_delete, ":cod_prova", $codP);
                			oci_bind_by_name($sql_delete, ":cod_atleta", $codigo);
                			$r_delete = oci_execute($sql_delete, OCI_NO_AUTO_COMMIT);
                			if (!$r_delete) {
            					$e_delete = oci_error($sql_delete);  
            					print " Erro: " . htmlentities($e_delete['message']);
            					oci_rollback($ora_conexao);
              				  }else{ 
              				  		oci_commit($ora_conexao);            	
	
									$r= oci_execute($sql_code);
									if (!$r) {
        								$e = oci_error($sql_code);  
        								print " Erro: " . htmlentities($e['message']);
          								oci_rollback($ora_conexao);
           								echo "<script> 
            	  							alert('Erro ao deletar.');
            	  							location.href='index.php?p=inicial';
            							  </script>"; 
							}else{
								oci_commit($ora_conexao);
								echo "<script>
									 location.href='index.php?p=inicial';
				 					</script>";
							}}

?>
 