<?php
require "auth.php"; require "db.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action=$_POST["action"] ?? "";
    if ($action === "borrow") {
        $bookId=(int)($_POST["book_id"] ?? 0); $borrower=trim($_POST["borrower_name"] ?? "");
        if ($bookId < 1 || $borrower === "") { $message="Choose a book and enter the borrower's name."; }
        else {
            $stmt=$conn->prepare("SELECT copies FROM books WHERE id=?"); $stmt->bind_param("i",$bookId); $stmt->execute(); $book=$stmt->get_result()->fetch_assoc();
            if (!$book || $book["copies"] < 1) { $message="This book is not available right now."; }
            else {
                $conn->begin_transaction();
                $update=$conn->prepare("UPDATE books SET copies=copies-1 WHERE id=? AND copies>0"); $update->bind_param("i",$bookId); $update->execute();
                if ($update->affected_rows === 1) {
                    $insert=$conn->prepare("INSERT INTO loans (book_id,borrower_name,borrow_date,status) VALUES (?,?,CURDATE(),'Borrowed')"); $insert->bind_param("is",$bookId,$borrower);
                    if ($insert->execute()) { $conn->commit(); header("Location: loans.php"); exit; }
                }
                $conn->rollback(); $message="Could not record the borrowing. Please try again.";
            }
        }
    } elseif ($action === "return") {
        $loanId=(int)($_POST["loan_id"] ?? 0);
        $conn->begin_transaction();
        $stmt=$conn->prepare("SELECT book_id FROM loans WHERE id=? AND status='Borrowed'"); $stmt->bind_param("i",$loanId); $stmt->execute(); $loan=$stmt->get_result()->fetch_assoc();
        if ($loan) {
            $update=$conn->prepare("UPDATE loans SET status='Returned', return_date=CURDATE() WHERE id=?"); $update->bind_param("i",$loanId); $update->execute();
            $bookUpdate=$conn->prepare("UPDATE books SET copies=copies+1 WHERE id=?"); $bookUpdate->bind_param("i",$loan["book_id"]); $bookUpdate->execute();
            $conn->commit(); header("Location: loans.php"); exit;
        } else { $conn->rollback(); $message="This borrowing record is already returned or unavailable."; }
    }
}
$availableBooks=$conn->query("SELECT id,title,copies FROM books WHERE copies>0 ORDER BY title");
$loans=$conn->query("SELECT loans.*, books.title FROM loans JOIN books ON loans.book_id=books.id ORDER BY loans.id DESC");
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Borrowing | Library System</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="style.css"></head><body>
<?php include "nav.php"; ?><main class="container py-4"><h1 class="h2 mb-3">Borrowing and Returns</h1>
<?php if ($message): ?><div class="alert alert-warning"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
<div class="form-card mb-4"><h2 class="h5">Record a borrowed book</h2><form method="post" id="borrowForm"><input type="hidden" name="action" value="borrow"><label class="form-label" for="borrower_name">Borrower's full name *</label><input class="form-control mb-3" id="borrower_name" name="borrower_name" required>
<label class="form-label" for="book_id">Book available *</label><select class="form-select mb-3" id="book_id" name="book_id" required><option value="">Choose a book</option><?php while($book=$availableBooks->fetch_assoc()): ?><option value="<?php echo (int)$book["id"]; ?>"><?php echo htmlspecialchars($book["title"]); ?> (<?php echo (int)$book["copies"]; ?> available)</option><?php endwhile; ?></select><button class="btn btn-primary">Record Borrowing</button></form></div>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead><tr><th>Book</th><th>Borrower</th><th>Borrow date</th><th>Return date</th><th>Status</th><th>Action</th></tr></thead><tbody>
<?php if($loans->num_rows): while($loan=$loans->fetch_assoc()): ?><tr><td><?php echo htmlspecialchars($loan["title"]); ?></td><td><?php echo htmlspecialchars($loan["borrower_name"]); ?></td><td><?php echo htmlspecialchars($loan["borrow_date"]); ?></td><td><?php echo htmlspecialchars($loan["return_date"] ?? "—"); ?></td><td><span class="badge <?php echo $loan["status"]==="Borrowed" ? "text-bg-warning" : "text-bg-success"; ?>"><?php echo htmlspecialchars($loan["status"]); ?></span></td><td><?php if($loan["status"]==="Borrowed"): ?><form method="post"><input type="hidden" name="action" value="return"><input type="hidden" name="loan_id" value="<?php echo (int)$loan["id"]; ?>"><button class="btn btn-sm btn-outline-success">Mark Returned</button></form><?php else: ?>—<?php endif; ?></td></tr><?php endwhile; else: ?><tr><td colspan="6" class="text-center text-secondary py-4">No borrowing records yet.</td></tr><?php endif; ?>
</tbody></table></div></div></main><script src="script.js"></script></body></html>
