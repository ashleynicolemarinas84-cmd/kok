<?php
session_start();
require "db.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Incorrect email or password.";
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | Simple Library System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
<main class="container">
<div class="login-card">
<div class="brand-icon">📚</div>
<h1 class="h3 text-center">Simple Library System</h1>
<p class="text-center text-secondary">Sign in to manage the library</p>
<?php if ($error !== ""): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<form method="post" id="loginForm">
<label class="form-label" for="email">Email address</label>
<input class="form-control mb-3" type="email" id="email" name="email" required>
<label class="form-label" for="password">Password</label>
<input class="form-control mb-3" type="password" id="password" name="password" required>
<button class="btn btn-primary w-100" type="submit">Log In</button>
</form>
<p class="small text-secondary mt-3 mb-0">For class demonstration. Change default credentials before public use.</p>
</div>
</main>
<script src="script.js"></script>
</body></html>
