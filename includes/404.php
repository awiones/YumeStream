<?php
$pageTitle = '404 - Page Not Found';
include 'header.php';
?>

<div class="container">
    <div style="text-align: center; padding: 5rem 0;">
        <h1 style="font-size: 6rem; margin-bottom: 1rem;">404</h1>
        <h2 style="font-size: 2rem; margin-bottom: 1rem; color: var(--text-secondary);">Page Not Found</h2>
        <p style="font-size: 1.25rem; color: var(--text-muted); margin-bottom: 2rem;">
            The page you're looking for seems to have wandered off into another dimension.
        </p>
        <a href="/home" class="btn-primary" style="display: inline-block;">Return Home</a>
    </div>
</div>

<?php include 'footer.php'; ?>