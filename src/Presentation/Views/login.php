<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="/src/Presentation/Public/css/login.css">
</head>
<body>
<div class="login-container">
    <h1>Welcome</h1>
    <p>To demo shop administration!</p>
    <form id="loginForm" action="/admin" method="POST">
        <div class="input-group">
            <label for="username">user name</label>
            <input type="text" id="username" name="username" >
        </div>
        <div class="input-group">
            <label for="password">password</label>
            <input type="password" id="password" name="password" >
        </div>
        <div class="input-group">
            <input type="checkbox" id="keepLoggedIn" name="keepLoggedIn">
            <label for="keepLoggedIn">keep me logged in</label>
        </div>
        <button type="submit">Log in</button>
        <?php if (isset($errorMessage)): ?>
            <p class="error-message" id="errorMessage" style="display: block;">
                <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php endif; ?>
    </form>
</div>
</body>
</html>