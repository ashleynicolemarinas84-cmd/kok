<?php
require "auth.php"; require "db.php";
$id = (int)($_GET["id"] ?? 0); $title = ""; $author = ""; $isbn = ""; $copies = "1"; $message = "";
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM books WHERE id=?"); $stmt->bind_param("i", $id); $stmt->execute(); $book = $stmt->get_result()->fetch_assoc();
    if (!$book) { die("Book not found."); }
    $title=$book["title"]; $author=$book["author"]; $isbn=$book["isbn"]; $copies=$book["copies"];
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title=trim($_POST["title"] ?? ""); $author=trim($_POST["author"] ?? ""); $isbn=trim($_POST["isbn"] ?? ""); $copies=(int)($_POST["copies"] ?? 0);
    if ($title === "" || $author === "" || $copies < 0) { $message="Enter a title, author, and a valid number of copies."; }
    elseif ($id > 0) {
        $stmt=$conn->prepare("UPDATE books SET title=?, author=?, isbn=?, copies=? WHERE id=?"); $stmt->bind_param("sssii",$title,$author,$isbn,$copies,$id);
        if ($stmt->execute()) { header("Location: books.php"); exit; } else { $message="Could not update the book. Check whether the ISBN is already used."; }
    } else {
        $stmt=$conn->prepare("INSERT INTO books (title,author,isbn,copies) VALUES (?,?,?,?)"); $stmt->bind_param("sssi",$title,$author,$isbn,$copies);
        if ($stmt->execute()) { header("Location: books.php"); exit; } else { $message="Could not save the book. The ISBN may already exist."; }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?php echo $id ? "Edit" : "Add"; ?> Book | Library System</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<?php include "nav.php"; ?><main class="container py-4"><div class="form-card"><h1 class="h2"><?php echo $id ? "Edit Book" : "Add a Book"; ?></h1><p class="text-secondary">Fields marked * are required.</p>
<?php if ($message): ?><div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
<form method="post" id="bookForm"><label class="form-label" for="title">Book title *</label><input class="form-control mb-3" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
<label class="form-label" for="author">Author *</label><input class="form-control mb-3" id="author" name="author" value="<?php echo htmlspecialchars($author); ?>" required>
<label class="form-label" for="isbn">ISBN (optional)</label><input class="form-control mb-3" id="isbn" name="isbn" value="<?php echo htmlspecialchars($isbn); ?>">
<label class="form-label" for="copies">Number of copies *</label><input class="form-control mb-3" id="copies" type="number" min="0" name="copies" value="<?php echo (int)$copies; ?>" required>
<button class="btn btn-primary" type="submit">Save Book</button> <a class="btn btn-outline-secondary" href="books.php">Cancel</a></form></div></main><script src="script.js"></script></body></html>
