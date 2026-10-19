<nav class="navbar navbar-expand-lg navbar-dark site-nav">
<div class="container"><a class="navbar-brand fw-bold" href="dashboard.php">📚 Library System</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="mainNav"><div class="navbar-nav me-auto"><a class="nav-link" href="dashboard.php">Dashboard</a><a class="nav-link" href="books.php">Books</a><a class="nav-link" href="loans.php">Borrowing</a><?php if (($_SESSION["role"] ?? "") === "admin"): ?><a class="nav-link" href="users.php">Users</a><?php endif; ?></div><span class="navbar-text me-3"><?php echo htmlspecialchars($_SESSION["name"] ?? ""); ?></span><a class="btn btn-light btn-sm" href="logout.php">Log out</a></div>
</div></nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
