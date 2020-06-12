<?php

$ora_user = "trabalhoBD";
$ora_senha = "utfpr";

$ora_bd = "localhost/xe";


if($ora_conexao = oci_new_connect($ora_user, $ora_senha, $ora_bd)){
	echo "Bando de Dados: Conectado";
}else{
	echo "Banco de Dados: Erro";
}


?>