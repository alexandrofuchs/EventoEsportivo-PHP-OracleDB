<?php

	include("classe/conexao.php");

	$codigo = intval($_GET['codigo']);

	$sql_code = oci_parse($ora_conexao, 'DELETE FROM TB_PROVA WHERE COD_PROVA = :codigo') or die ("erro");

	oci_bind_by_name($sql_code, ":codigo", $codigo);
	
	$r= oci_execute($sql_code);
		if (!$r) {
        	$e = oci_error($sql_code);  
        	print " Erro: " . htmlentities($e['message']);
            oci_rollback($ora_conexao);
            echo "<script> 
            	  	alert('Erro ao deletar.');
            	  	location.href='index.php?p=exibe_provas';
            	  </script>"; 

		}else{
			oci_commit($ora_conexao);
			echo "<script>
					 location.href='index.php?p=exibe_provas';
				 </script>";
		}

?>
