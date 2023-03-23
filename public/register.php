<?php
    require_once 'user.php';
    //INICIALIZAMOS LAS VARIABLES
    $errorPasswd = null;
    $errorMail = null;
    //session_start + comprobar si existe o no la sesion
    //FUNCIONES
    function checkPasswd($password, &$errorPasswd): void {
        if (strlen($password) < 7) {
            $errorPasswd = $errorPasswd . ' Your password must be 7 characters or longer.';
        }
        if (preg_match('/[a-z]/', $password) === 0) {   //puede devolver 'false' en caso de error, por eso es recomendable comparar también el type
            $errorPasswd = $errorPasswd . ' Your password must contain at least one Non capital letter.';
        }
        if (preg_match('/[A-Z]/', $password) === 0) {   //puede devolver 'false' en caso de error, por eso es recomendable comparar también el type
            $errorPasswd = $errorPasswd . ' Your password must contain at least one capital letter.';
        }
        if (preg_match('/[0-9]/', $password) === 0) {   //puede devolver 'false' en caso de error, por eso es recomendable comparar también el type
            $errorPasswd = $errorPasswd . ' Your password must contain at least one number.';
        }
    }

    function checkEmail($email, &$errorMail): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMail = "Invalid email format";
        }
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST)) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        //check email
        checkEmail($email, $errorMail);
        //check password
        checkPasswd($password, $errorPasswd);

        if (is_null($errorMail) || is_null($errorPasswd)) { //NO hi ha hagut cap error a l'hora de fer sign in
            header("Location: login.php");
            exit; // The exit statement is used to terminate the script execution after the header is sent to the browser. This is important because if you don't exit, the script will continue to execute and may output additional content that can interfere with the redirection.
        }
        
        $user = new User($email, $password);
        echo 'The username is: ' . $user->getName();
        echo 'The password is: ' . $user->getPassword();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>
    <h4>REGISTER</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
        <div class="email_input">
            <input type="email" name="email" class="email" placeholder="something@example.com">
            <h4> <?php echo $errorMail;?> </h4>
        </div>
        <div class="password_input">
            <input type="password" name="password" class="password" placeholder="Password">
            <?php echo '<h4 id="errors" class="errors" name="errors" value="'. $errorPasswd. '"></h4>'; ?>
        </div>
        <button type="submit">Register</button>
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
    .errors {
        background: aquamarine;
    }

</style>


