<?php
require "auth.php"; require "db.php";
$search = trim($_GET["search"] ?? "");
if ($search !== "") {
    $like = "%".$search."%";
    $stmt = $conn->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ? ORDER BY title");
    $stmt->bind_param("sss", $like, $like, $like); $stmt->execute(); $books = $stmt->get_result();
} else { $books = $conn->query("SELECT * FROM books ORDER BY title"); }
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Books | Library System</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<?php include "nav.php"; ?><main class="container py-4">
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><h1 class="h2 mb-0">Book Collection</h1><a class="btn btn-primary" href="book_form.php">+ Add Book</a></div>
<form class="card card-body border-0 shadow-sm mb-3" method="get"><div class="row g-2"><div class="col-sm"><input class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search title, author, or ISBN"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button> <a class="btn btn-outline-secondary" href="books.php">Clear</a></div></div></form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Title</th><th>Author</th><th>ISBN</th><th>Copies</th><th>Actions</th></tr></thead><tbody>
<?php if ($books->num_rows): while ($book = $books->fetch_assoc()): ?>
<tr><td><?php echo htmlspecialchars($book["title"]); ?></td><td><?php echo htmlspecialchars($book["author"]); ?></td><td><?php echo htmlspecialchars($book["isbn"]); ?></td><td><?php echo (int)$book["copies"]; ?></td><td class="text-nowrap"><a class="btn btn-sm btn-outline-primary" href="book_form.php?id=<?php echo (int)$book["id"]; ?>">Edit</a>
<?php if (($_SESSION["role"] ?? "") === "admin"): ?><form class="d-inline" method="post" action="delete_book.php" onsubmit="return confirm('Delete this book?');"><input type="hidden" name="id" value="<?php echo (int)$book["id"]; ?>"><button class="btn btn-sm btn-outline-danger">Delete</button></form><?php endif; ?></td></tr>
<?php endwhile; else: ?><tr><td colspan="5" class="text-center text-secondary py-4">No books found. Add your first book.</td></tr><?php endif; ?>
</tbody></table></div></div></main></body></html>
