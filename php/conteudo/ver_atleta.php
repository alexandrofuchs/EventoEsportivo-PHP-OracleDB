<?php 
	include("classe/conexao.php");

	$cod_atleta = $_GET['atleta'];

	$sql_consulta = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA WHERE COD_ATLETA = :cod_atleta');

	oci_bind_by_name($sql_consulta, ":cod_atleta", $cod_atleta);

	oci_execute($sql_consulta);

	$linha = oci_fetch_assoc($sql_consulta);


	$sexo_atleta['M'] = "Masculino";
	$sexo_atleta['F'] = "Feminino"; 

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	
</head>

<body>
<H4>
<center>

<table cellspacing="0" cellpadding="5" bgcolor="#FFFFFF">
	<tr>
		<td>NOME:</td>
		<td> <?php echo $linha['NOME']  ?> </td>
	</tr>
	<tr>
		<td> APELIDO:</td>
		<td> <?php echo $linha['APELIDO'] ?> </td>
	</tr>
	<tr>
		<td>SEXO:</td>
		<td>	 <?php echo $sexo_atleta[$linha['SEXO']]  ?></td>
	</tr>
	<tr>
		<td>CPF:</td>
		<td><?php echo $linha['CPF']?></td>
	</tr>
	<tr>
		<td>TELEFONE:</td>
		<td><?php echo $linha['TELEFONE']?></td>
	</tr>
	<tr>
		<td>DATA DE NASCIMENTO:</td> 
		<td><?php echo $linha['DT_NASCIMENTO']?></td>
	</tr>
	<tr>
		<td> DATA DE INSCRIÇÃO:</td>
		<td><?php echo $linha['DT_INSCRICAO']?></td>
	</tr>
	<tr>
		<td>MODALIDADE:</td>
		<td><?php
			$consulta_modalidade = oci_parse($ora_conexao, 'SELECT DESC_MODALIDADE FROM TB_MODALIDADE WHERE TB_MODALIDADE.COD_MODALIDADE = :COD_MODALIDADE');
			oci_bind_by_name($consulta_modalidade, ":COD_MODALIDADE", $linha['COD_MODALIDADE']);
			oci_execute($consulta_modalidade);
			$modalidade = oci_fetch_assoc($consulta_modalidade);
			echo $modalidade['DESC_MODALIDADE']; ?></td>
	</tr>
	<tr>
		<td>PROVAS:</td>
		<td>
			<?php 
				$sql_busca = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA_PROVA WHERE COD_ATLETA = :cod_atleta') or die("erro");
				oci_bind_by_name($sql_busca, ":cod_atleta", $cod_atleta);
				oci_execute($sql_busca);
				while ( ($prova = oci_fetch_assoc($sql_busca)) != false){
				    $desc_prova = oci_parse($ora_conexao, 'SELECT DESC_PROVA FROM TB_PROVA WHERE COD_PROVA = :cod_prova ') or die("erro");
				    $cod_prova = $prova['COD_PROVA'];
				    oci_bind_by_name($desc_prova, ":cod_prova", $cod_prova);
				    oci_execute($desc_prova);
				    $descricao = oci_fetch_assoc($desc_prova);
				    echo $descricao['DESC_PROVA']."<br>";
				}
			?>
		</td>
	</tr>

</table>

<p class="espaco"></p>
<a class="titulo" href="index.php?p=inicial"> Voltar</a>
</center>
</H4>
</body>
</html>