// Small client-side checks to help prevent accidental empty submissions.
document.addEventListener("DOMContentLoaded", function () {
    const bookForm = document.getElementById("bookForm");
    if (bookForm) {
        bookForm.addEventListener("submit", function (event) {
            const title = document.getElementById("title").value.trim();
            const author = document.getElementById("author").value.trim();
            const copies = Number(document.getElementById("copies").value);
            if (!title || !author || copies < 0 || !Number.isInteger(copies)) {
                event.preventDefault();
                alert("Please enter a title, author, and a whole number of copies (0 or more).");
            }
        });
    }
});
