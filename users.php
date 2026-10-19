<?php
require "auth.php"; require "db.php";
if (($_SESSION["role"] ?? "") !== "admin") { http_response_code(403); die("Only an administrator can manage users."); }
$message="";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $name=trim($_POST["name"] ?? ""); $email=trim($_POST["email"] ?? ""); $password=$_POST["password"] ?? ""; $role=$_POST["role"] ?? "staff";
    if ($name==="" || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<8 || !in_array($role,["admin","staff"],true)) { $message="Enter a name, valid email, password of at least 8 characters, and role."; }
    else {
        $hash=password_hash($password,PASSWORD_DEFAULT);
        $stmt=$conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)"); $stmt->bind_param("ssss",$name,$email,$hash,$role);
        $message=$stmt->execute() ? "User added successfully." : "Could not add user. The email may already exist.";
    }
}
$users=$conn->query("SELECT id,name,email,role FROM users ORDER BY name");
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Users | Library System</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body><?php include "nav.php"; ?><main class="container py-4"><h1 class="h2">User Accounts</h1><div class="form-card mb-4"><h2 class="h5">Add a user</h2><?php if($message): ?><div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div><?php endif; ?><form method="post"><label class="form-label">Full name</label><input class="form-control mb-3" name="name" required><label class="form-label">Email</label><input class="form-control mb-3" name="email" type="email" required><label class="form-label">Password (at least 8 characters)</label><input class="form-control mb-3" name="password" type="password" minlength="8" required><label class="form-label">Role</label><select class="form-select mb-3" name="role"><option value="staff">Staff</option><option value="admin">Admin</option></select><button class="btn btn-primary">Add User</button></form></div><div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table mb-0"><thead><tr><th>Name</th><th>Email</th><th>Role</th></tr></thead><tbody><?php while($user=$users->fetch_assoc()): ?><tr><td><?php echo htmlspecialchars($user["name"]); ?></td><td><?php echo htmlspecialchars($user["email"]); ?></td><td><?php echo htmlspecialchars($user["role"]); ?></td></tr><?php endwhile; ?></tbody></table></div></div></main></body></html>
