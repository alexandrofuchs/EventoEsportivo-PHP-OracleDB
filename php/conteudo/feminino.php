<?php
	include("classe/conexao.php");


	$sql_consulta = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA WHERE SEXO = :sexo ORDER BY NOME') or die("erro");

	$sexo_atleta = "F";

	oci_bind_by_name($sql_consulta, ":sexo", $sexo_atleta);

	oci_execute($sql_consulta);


	$sexo['M'] = "MASCULINO";
	$sexo['F'] = "FEMININO";

	
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
<center>
<table>
	<tr class="titulo">
		<td><a href="index.php?p=cadastrar_atleta">|  Cadastrar Atleta  |</a></td>
		<td><a href="index.php?p=exibe_provas">|  Tabela de Provas  |</a></td>
	</tr>
</table>

<h1>Atletas</h1>

<table>
	<tr class="titulo">
		<td> <a href="index.php?p=inicial">|  Visão Geral  |</a></td>
	</tr>
</table>
</head>


<h3>Atletas Femininas</h3>
<body>
<center>
<table border=2 cellpadding=8 bgcolor="#E6E8FA" cellspacing="0">
	<tr class=titulo>
		<td> NOME</td>
		<td> APELIDO</td>
		<td> SEXO</td>
		<td> OPÇÕES</td>
	</tr>
	<tr>
		<?php while( ($linha = oci_fetch_array($sql_consulta)) != false ) { ?>
			<td> <?php echo $linha['NOME'] ?></td>
			<td> <?php echo $linha['APELIDO'] ?></td>
			<td> <?php echo $sexo[$linha['SEXO']] ?></td>
			<td>
				<a href="index.php?p=ver_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>"><button>Dados</button></a>
				<a href="index.php?p=editar_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>"><button>Editar</button></a>
				<a href="javascript: if(confirm('Realmente deseja excluir: <?php echo $linha['NOME'];?> ?')) location.href='index.php?p=deletar_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>'"><button>Deletar</button></a>
			</td>
	</tr>
<?php } ?>
</table>
</center>
</body>
</html>