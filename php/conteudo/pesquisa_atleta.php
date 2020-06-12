<?php 

	include("classe/conexao.php");


	if(isset($_POST['pesquisar'])){

		if(!isset($_SESSION))
			session_start();


		foreach ($_POST as $chave => $valor) {
			$_SESSION[$chave] = $valor;
		}

		$sexo['M'] = "Masculino";
		$sexo['F'] = "Feminino";


		$nome_atleta = $_SESSION['nome'];
		$nome_atleta =  ucwords($nome_atleta);
		$nome_atleta = "%".$nome_atleta."%";

		
		
		$sql_pesquisa = oci_parse($ora_conexao, 'SELECT * FROM TB_ATLETA WHERE NOME LIKE :nome ORDER BY NOME') or die("erro");

		oci_bind_by_name($sql_pesquisa, ":nome", $nome_atleta);

		oci_execute($sql_pesquisa);
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
		<?php if ( ($linha = oci_fetch_assoc($sql_pesquisa)) != false) {?>
			<table border=2 cellpadding=8 bgcolor="#E6E8FA" cellspacing="0">
				<tr class=titulo>
					<td>NOME</td>
					<td>APELIDO</td>
					<td>SEXO</td>
					<td>OPÇÕES</td>	
				</tr>
				<?php do { ?>
				<tr>
					<td><?php echo $linha['NOME']; ?></td>
					<td><?php echo $linha['APELIDO']; ?></td>
					<td><?php echo $sexo[$linha['SEXO']];?></td>
					<td>
						<a href="index.php?p=ver_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>"><button>Dados</button></a>
						<a href="index.php?p=editar_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>"><button>Editar</button></a>
						<a href="javascript: if(confirm('Realmente deseja excluir: <?php echo $linha['NOME'];?> ?')) location.href='index.php?p=deletar_atleta&atleta=<?php echo $linha['COD_ATLETA']; ?>'"><button>Deletar</button></a></td>
				</tr>
			<?php } while( ($linha = oci_fetch_assoc($sql_pesquisa)) != false); ?>
		    </table>
		
<?php
		

			}else{
				echo "<h3><br> Não Encontrado</h3>";
			}
	}
?>

<p class="espaco"></p>
<a class="titulo" href="index.php?=inicial"> Ver tudo</a>

</body>
</html>