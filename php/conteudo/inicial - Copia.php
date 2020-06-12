<?php 

include("classe/conexao.php");


$consulta_atletas = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA ORDER BY NOME') or die("erro");

oci_execute($consulta_atletas);
	$sexo['M'] = "MASCULINO";
	$sexo['F'] = "FEMININO";

 ?>

<html>
<head>
	<title></title>


<center>
<table>
	<tr class="titulo">
		<td><a href="index.php?p=cadastrar_atleta"> Cadastrar Atleta</a></td>
		<td><a href="index.php?p=exibe_provas"> Ver Tabela de Provas</a></td>
	</tr>
</table>

<h1>Atletas</h1>

<table>
	<tr class="titulo">
		<td> <a href="index.php?p=masculino">Masculino</a></td>
		<td> <a href="index.php?p=feminino">Feminino</a></td>
		<td> <a href="">Ver por Modalidade</a></td>
	</tr>
</table>
</head>
<body>
<p class="espaco"></p>
<table border=2 cellpadding=8 bgcolor="#E6E8FA" cellspacing="0">
	<tr class=titulo>
		<td>Nome</td>

		<td>Apelido</td>

		<td>Sexo</td>

		<td>Data de Cadastro</td>

		<td>Modalidade</td>	

		<td>Opções</td>	
	</tr>
		<?php 
			while( ( $linha = oci_fetch_assoc($consulta_atletas)) != false){
		?>
	<tr>
		<td><?php echo $linha['NOME']; ?></td>

		<td><?php echo $linha['APELIDO']; ?></td>

		<td><?php echo $sexo[$linha['SEXO']];?></td>

		<td><?php echo $linha['DT_INSCRICAO'];?></td>

		<td><?php
			$consulta_modalidade = oci_parse($ora_conexao, 'SELECT DESC_MODALIDADE FROM TB_MODALIDADE WHERE TB_MODALIDADE.COD_MODALIDADE = :COD_MODALIDADE');
			oci_bind_by_name($consulta_modalidade, ":COD_MODALIDADE", $linha['COD_MODALIDADE']);
			oci_execute($consulta_modalidade);
			$modalidade = oci_fetch_assoc($consulta_modalidade);
			echo $modalidade['DESC_MODALIDADE'];
		?></td>

		<td>
			<a href="index.php?p=editar_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>"><button>Editar</button></a>
			<a href="javascript: if(confirm('Realmente deseja excluir: <?php echo $linha['NOME'];?> ?')) location.href='index.php?p=deletar_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>'"><button>Deletar</button></a>
		</td>	
	</tr>
	<?php 
		}
	?>

</table>
</center>
</body>
</html>

