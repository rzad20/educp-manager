<?php require BASE_PATH . '/app/views/layouts/header.php'; ?>
<h1>Login</h1>
<form method="post" action="index.php?page=login_submit">
    <?= Csrf::field() ?>
    <label>Username <input type="text" name="username" required></label>
    <label>Password <input type="password" name="password" required></label>
    <button type="submit">Masuk</button>
</form>
<?php require BASE_PATH . '/app/views/layouts/footer.php'; ?>
