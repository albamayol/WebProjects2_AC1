<?php
require_once 'user.php';
//INICIALIZAMOS LAS VARIABLES
$errorPasswd = null;
$errorMail = null;

//FUNCIONES
function checkPasswd($password, &$errorPasswd): void {
    if (strlen($password) < 7) {
        $errorPasswd = $errorPasswd . ' Your password must be 7 characters or longer. <br>';
    }
    if (preg_match('/[a-z]/', $password) === 0) {   //puede devolver 'false' en caso de error, por eso es recomendable comparar también el type
        $errorPasswd = $errorPasswd . ' Your password must contain at least one Non capital letter.<br>';
    }
    if (preg_match('/[A-Z]/', $password) === 0) {   //puede devolver 'false' en caso de error, por eso es recomendable comparar también el type
        $errorPasswd = $errorPasswd . ' Your password must contain at least one capital letter.<br>';
    }
    if (preg_match('/[0-9]/', $password) === 0) {   //puede devolver 'false' en caso de error, por eso es recomendable comparar también el type
        $errorPasswd = $errorPasswd . ' Your password must contain at least one number.<br>';
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

        if (is_null($errorMail) && is_null($errorPasswd)) { //NO hi ha hagut cap error a l'hora de fer sign in
            //If all the data is correct, you will have to check if the user exists in the database to log him in (**you need to start a session for the user**).
            //If the user is not found, you need to display an error in the form.
            //session_start + comprobar si existe o no la sesion
            /*session_start();
            if (!isset($_SESSION['counter'])) {
                $_SESSION['counter'] = 1;
            } else {
                $_SESSION['counter']++;
            }*/
            echo 'all good';
        }

        $user = new User($email, $password);
        //TODO BORRAR ESTOS echo's
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
    <h4>LOG IN</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
        <div class="email_input">
            <input type="email" class="email" id="email" placeholder="something@example.com">
            <h4> <?php echo $errorMail;?> </h4>
        </div>
        <div class="password_input">
            <input type="password" class="password" id="password" placeholder="Password">
            <h4> <?php echo $errorPasswd;?> </h4>
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
    .form > h4 {
        text-align: center;
        /*text-justify: inter-word;*/
    }
    .email_input {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .password_input {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

</style>


