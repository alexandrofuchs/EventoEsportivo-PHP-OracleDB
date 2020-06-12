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
		<td><a href="index.php?p=cadastrar_atleta"> |  Cadastrar Atleta  |</a></td>
		<td><a href="index.php?p=exibe_provas"> |  Tabela de Provas  |</a></td>
	</tr>
</table>

<h1>Atletas</h1>

<table>
	<tr class="titulo">
		<td> <a href="index.php?p=masculino">|  Masculino  |</a></td>
		<td> <a href="index.php?p=feminino">|  Feminino  |</a></td>

	</tr>
</table>
<form action="index.php?p=pesquisa_atleta" method="POST">
	<input required type="text" name="nome" value="">
	<input type="submit" name="pesquisar" value="Pesquisar">
</form>
</head>
<body>
<p class="espaco"></p>
<table border=2 cellpadding=8 bgcolor="#E6E8FA" cellspacing="0">
	
	<tr class=titulo>
		<td>NOME</td>

		<td>APELIDO</td>

		<td>SEXO</td>

		<td>OPÇÕES</td>	
	</tr>
	
		<?php 
			while( ( $linha = oci_fetch_assoc($consulta_atletas)) != false){
		?>
	<tr>
		<td><?php echo $linha['NOME']; ?></td>

		<td><?php echo $linha['APELIDO']; ?></td>

		<td><?php echo $sexo[$linha['SEXO']];?></td>

		<td>
			<a href="index.php?p=ver_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>"><button>Dados</button></a>
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

