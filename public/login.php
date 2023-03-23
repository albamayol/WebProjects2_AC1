<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>
    <h4>LOG IN</h4>
    <form action="login.php" method="post" class="form">
        <div class="email_input">
            <input type="email" class="email" id="email" placeholder="something@example.com">
        </div>
        <div class="password_input">
            <input type="password" class="password" id="password" placeholder="Password">
        </div>
        <button type="submit">Log in</button>
    </form>
</body>
</html>
<style scoped>
    body {
        background: aliceblue;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>

<?php

?>
