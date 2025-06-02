<?php
// Include database connection
include('../dbconnection.php');

// Set the number of questions per page
$results_per_page = 10; 

// Get the current page or set a default
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = $_GET['page'];
} else {
    $current_page = 1;
}

// Calculate the offset for the query
$offset = ($current_page - 1) * $results_per_page;

// Get the total number of questions
$total_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM question_bank");
$total_row = mysqli_fetch_assoc($total_query);
$total_questions = $total_row['total'];

// Calculate the total pages
$total_pages = ceil($total_questions / $results_per_page);

// Fetch the questions for the current page
$query3 = mysqli_query($conn, "SELECT * FROM question_bank LIMIT $offset, $results_per_page");

?>

<!-- Display pagination links -->
<div class="d-flex justify-content-center mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1 ?>">Previous</a>
                </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1 ?>">Next</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
