<?php
require "auth.php"; require "db.php";
if (($_SESSION["role"] ?? "") !== "admin") { http_response_code(403); die("Only an administrator can delete books."); }
if ($_SERVER["REQUEST_METHOD"] !== "POST") { header("Location: books.php"); exit; }
$id=(int)($_POST["id"] ?? 0);
$stmt=$conn->prepare("DELETE FROM books WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute();
header("Location: books.php"); exit;
?>
