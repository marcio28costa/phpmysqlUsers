<!DOCTYPE html>
<html>
<head>
	<title>Formulário PHP e Bootstrap</title>
	<!-- Adicione os arquivos CSS e JS do Bootstrap -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<?php
		if (isset($_GET['erro']) && $_GET['erro'] == 'conexao') {
			echo '<span style="color: red;">Erro de conexão com o banco de dados. </span>';
		}else {
			echo '';
		}
		?>

		<h2>Formulário verificação/exportação dos usúarios do Bando de Dados</h2>
		<form method="post" action="processa_formulario.php">
			<div class="form-group">
				<label for="user">Usuário:</label>
				<input type="text" class="form-control" id="user" name="user" required>
			</div>
			<div class="form-group">
				<label for="password">Senha:</label>
				<input type="password" class="form-control" id="password" name="password" required>
			</div>
			<div class="form-group">
				<label for="host">Host:</label>
				<input type="text" class="form-control" id="host" name="host" required>
			</div>
			<div class="form-group">
				<label for="port">Porta:</label>
				<input type="text" class="form-control" id="port" name="port" required>
			</div>
			<div class="form-group">
				<label for="database">Banco de dados:</label>
				<input type="text" class="form-control" id="database" name="database" value='mysql' required>
			</div>
			<button type="submit" class="btn btn-primary">Gerar Grants</button>
		</form>
	</div>
</body>
</html>