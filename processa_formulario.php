<?php
$user = $_POST['user'];
$password = $_POST['password'];
$host = $_POST['host'];
$port = $_POST['port'];
$database = $_POST['database'];
$destino = 'temp.sql';
$data_hora = date('d/m/Y H:i:s');

$conn = mysqli_connect($host . ':' . $port, $user, $password, $database);

if (!$conn) {
  // Se ocorrer um erro de conexão, redirecione o usuário para a página anterior
  header('Location: index.php?erro=conexao');
  exit;
}

// if (!$conn) {
//  die("Falha na conexão: " . mysqli_connect_error());
// }

if (file_exists($destino)) {
   unlink($destino); // apaga o arquivo se ele já existe
}

$sql = "SELECT versioN()";
$resultado = mysqli_query($conn, $sql);

// Verifica se houve algum erro na execução do SELECT
if (!$resultado) {
    die('Erro na execução do SELECT: ' . mysqli_error($conn));
}

// Exibe os resultados
$row = mysqli_fetch_row($resultado);
$arquivo = fopen($destino, "w");
fwrite($arquivo, "###### Autor: marcio28costa@hotmail.com | exportação de usúarios v.1 | PHP 7.5 ########\n");
fwrite($arquivo, "###### Versão do MySQL: " . $row[0] . " ########\n");
fwrite($arquivo, "###### host: " . $host . " | porta: ".$port. " ########\n");
fwrite($arquivo, "###### Geração da consulta: " . $data_hora . " ########\n");
fclose($arquivo);

if ((strpos($row[0], 'MariaDB') !== false) or ( floatval(substr($row[0], 0 , 3 )) < 5.7 )) {
  $sql = "SELECT CONCAT('`', USER, '`@`', HOST, '`') AS user_host FROM mysql.user WHERE user not in ('mysql.sys', 'mysql.session')";
  $users = mysqli_query($conn, $sql);
    
  if(mysqli_num_rows($users) > 0){
    while($row = mysqli_fetch_assoc($users)){
      $user_host = $row['user_host'];
      $sql2 = "SHOW GRANTS FOR " . $user_host;
      $users2 = mysqli_query($conn, $sql2);
    
        if(mysqli_num_rows($users2) > 0){
          while($row2 = mysqli_fetch_row($users2)){
            $arquivo = fopen($destino, "a");
            fwrite($arquivo, $row2[0] . ";\n" );
            fclose($arquivo);
            }
        } 
    }
  } 
  
}  elseif (floatval(substr($row[0], 0 , 3 )) >= 5.7 ){
      $sql = "SELECT CONCAT('`', USER, '`@`', HOST, '`') AS user_host FROM mysql.user WHERE user not in ('mysql.sys', 'mysql.session')";
        $users = mysqli_query($conn, $sql);
        // pegando o create user
        if(mysqli_num_rows($users) > 0){
          while($row = mysqli_fetch_assoc($users)){
            $user_host = $row['user_host'];
            $sql2 = "SHOW CREATE USER " . $user_host;
            $users2 = mysqli_query($conn, $sql2);
    
            if(mysqli_num_rows($users2) > 0){
              while($row2 = mysqli_fetch_row($users2)){
                $arquivo = fopen($destino, "a");
                fwrite($arquivo, $row2[0] . ";\n" );
                fclose($arquivo);
              }
            }
    
          // pegando os grants
          $sql3 = "SHOW GRANTS FOR " . $user_host;
          $users3 = mysqli_query($conn, $sql3); 
    
          if($users3 !== false && mysqli_num_rows($users3) > 0){
            while($row3 = mysqli_fetch_row($users3)){
              $arquivo = fopen($destino, "a");
              fwrite($arquivo, $row3[0] . ";\n" );
              fclose($arquivo);
            }
          }
        }
      } 
} else  {
  echo "0 Resultados";
}

$nome_arquivo = 'temp.sql';
$caminho_arquivo = './' . $nome_arquivo;

// Define o cabeçalho para iniciar o download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($nome_arquivo).'"');
header('Content-Length: ' . filesize($caminho_arquivo));

// Lê e exibe o conteúdo do arquivo
readfile($caminho_arquivo);
?>