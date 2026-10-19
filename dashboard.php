<?php
require "auth.php";
require "db.php";
$bookCount = $conn->query("SELECT COUNT(*) AS total FROM books")->fetch_assoc()["total"];
$loanCount = $conn->query("SELECT COUNT(*) AS total FROM loans WHERE status = 'Borrowed'")->fetch_assoc()["total"];
$memberCount = $conn->query("SELECT COUNT(DISTINCT borrower_name) AS total FROM loans")->fetch_assoc()["total"];
?>
<!doctype html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard | Simple Library System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css">
</head><body>
<?php include "nav.php"; ?>
<main class="container py-4">
<div class="welcome-panel mb-4"><h1 class="h2">Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>!</h1><p class="mb-0">Keep books and borrowing records organized in one place.</p></div>
<div class="row g-3">
<div class="col-md-4"><div class="stat-card"><span>Books in library</span><strong><?php echo $bookCount; ?></strong><a href="books.php">View books →</a></div></div>
<div class="col-md-4"><div class="stat-card"><span>Books currently borrowed</span><strong><?php echo $loanCount; ?></strong><a href="loans.php">View loans →</a></div></div>
<div class="col-md-4"><div class="stat-card"><span>Borrowers recorded</span><strong><?php echo $memberCount; ?></strong><a href="loans.php">View records →</a></div></div>
</div>
<div class="card border-0 shadow-sm mt-4"><div class="card-body"><h2 class="h5">Quick actions</h2><a class="btn btn-primary me-2 mb-2" href="book_form.php">Add a book</a><a class="btn btn-outline-primary mb-2" href="loans.php">Borrow / return books</a></div></div>
</main></body></html>
