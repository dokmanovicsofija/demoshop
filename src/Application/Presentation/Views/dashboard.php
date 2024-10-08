<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/src/Application/Presentation/Public/css/dashboard.css">
</head>
<body>
<div class="container">
    <aside class="sideMenu">
        <ul>
            <li data-section="admin/dashboard" class="active">Dashboard</li>
            <li data-section="admin/products">Products</li>
            <li data-section="admin/categories">Product Categories</li>
        </ul>
    </aside>
    <main id="content" class="content">
    </main>
</div>
<script src="/src/Application/Presentation/Public/js/router.js"></script>
<script src="/src/Application/Presentation/Public/js/categories.js"></script>
<script src="/src/Application/Presentation/Public/js/products.js"></script>
<script src="/src/Application/Presentation/Public/js/main.js"></script>
<script src="/src/Application/Presentation/Public/js/dashboard.js"></script>
<script src="/src/Application/Presentation/Public/js/ajax.js"></script>
</body>
</html>