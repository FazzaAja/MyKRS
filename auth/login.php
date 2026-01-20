<?php

session_start();
require_once '../db/koneksi.php';

// Jika sudah login, redirect ke halaman sesuai role
if (isset($_SESSION['user'])) {
	if ($_SESSION['user']['role'] === 'admin') {
		header('Location: ../mahasiswa/mahasiswa.php');
		exit();
	} else if ($_SESSION['user']['role'] === 'mahasiswa') {
		header('Location: ../krs/krs.php?id=' . $_SESSION['user']['id_mhs']);
		exit();
	}
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = isset($_POST['username']) ? trim($_POST['username']) : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';
	$errors = [];
	if (empty($username) || !preg_match('/^[a-zA-Z0-9_.-]+$/', $username)) {
		$errors[] = 'Username hanya boleh huruf, angka, titik, underscore, dan minus.';
	}
	if (empty($password)) {
		$errors[] = 'Password tidak boleh kosong.';
	}
	if (count($errors) > 0) {
		$error = implode('<br>', $errors);
	} else {
		// Gunakan prepared statement
		$stmt = mysqli_prepare($con, "SELECT * FROM users WHERE username=? AND password=MD5(?)");
		mysqli_stmt_bind_param($stmt, "ss", $username, $password);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$user = mysqli_fetch_assoc($result);
		mysqli_stmt_close($stmt);
		if ($user) {
			$_SESSION['user'] = $user;
			// Jika mahasiswa, ambil id mahasiswa dari tabel mahasiswa
			if ($user['role'] === 'mahasiswa') {
				$stmt2 = mysqli_prepare($con, "SELECT id FROM mahasiswa WHERE nim=?");
				mysqli_stmt_bind_param($stmt2, "s", $username);
				mysqli_stmt_execute($stmt2);
				$result2 = mysqli_stmt_get_result($stmt2);
				$mhs = mysqli_fetch_assoc($result2);
				mysqli_stmt_close($stmt2);
				$_SESSION['user']['id_mhs'] = $mhs ? $mhs['id'] : null;
			}
			if ($user['role'] === 'admin') {
				header('Location: ../mahasiswa/mahasiswa.php');
			} else {
				header('Location: ../krs/krs.php?id=' . $_SESSION['user']['id_mhs']);
			}
			exit();
		} else {
			$error = 'Username atau password salah!';
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login MyKRS</title>
	<style>
		body {
			background: #f0f1f6;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Segoe UI', Arial, sans-serif;
		}
		.login-container {
			background: #fff;
			border-radius: 14px;
			box-shadow: 0 2px 16px rgba(0,0,0,0.06);
			padding: 32px 28px 28px 28px;
			width: 100%;
			max-width: 340px;
			display: flex;
			flex-direction: column;
			gap: 0.5rem;
		}
		.login-container h2 {
			margin-bottom: 18px;
			color: #444;
			font-weight: 500;
			font-size: 1.35rem;
			letter-spacing: 0.5px;
			text-align: center;
		}
		.login-container label {
			color: #888;
			font-size: 13px;
			margin-bottom: 4px;
			margin-top: 8px;
			display: block;
		}
		.login-container input[type="text"],
		.login-container input[type="password"] {
			width: 100%;
			padding: 9px 11px;
			border: 1px solid #e0e0e0;
			border-radius: 5px;
			background: #f7f7fa;
			margin-bottom: 8px;
			font-size: 15px;
			color: #333;
			transition: border 0.2s;
			box-sizing: border-box;
		}
		.login-container input[type="text"]:focus,
		.login-container input[type="password"]:focus {
			border: 1.5px solid #b0b3b8;
			outline: none;
			background: #fff;
		}
		.login-container button {
			width: 100%;
			padding: 10px 0;
			background: linear-gradient(90deg, #b0b3b8 60%, #e0e1e4 100%);
			color: #fff;
			border: none;
			border-radius: 5px;
			font-size: 15px;
			font-weight: 600;
			cursor: pointer;
			margin-top: 10px;
			box-shadow: 0 1px 4px rgba(176,179,184,0.08);
			transition: background 0.2s, box-shadow 0.2s;
		}
		.login-container button:hover {
			background: linear-gradient(90deg, #888a8d 60%, #b0b3b8 100%);
			box-shadow: 0 2px 8px rgba(176,179,184,0.13);
		}
		.login-container .error {
			color: #e74c3c;
			background: #fbeaea;
			border-radius: 5px;
			padding: 8px 10px;
			margin-bottom: 10px;
			text-align: center;
			font-size: 13px;
		}
	</style>
</head>
<body>
	<div class="login-container">
		<h2>Login MyKRS</h2>
		<?php if ($error) : ?>
			<div class="error"><?php echo $error; ?></div>
		<?php endif; ?>
		<form method="post">
			<label>Username</label>
			<input type="text" name="username">
			<label>Password</label>
			<input type="password" name="password">
			<button type="submit">Login</button>
		</form>
	</div>
</body>
</html>
