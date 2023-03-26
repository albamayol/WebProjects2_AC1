<?php
    //require_once 'user.php';
    //INICIALIZAMOS LAS VARIABLES
    $errorPasswd = null;
    $errorMail = null;

    //CONNECTION TO THE DATABASE
    try {
        $connection = new PDO('mysql:host=db;dbname=LSCat', 'root', 'admin');
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'PDO failed: ' . $e->getMessage();
    }
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
            $time = new DateTime('now');
            //$user = new User($email, $password, $time, $time);

            $statement = $connection->prepare("INSERT INTO Users (email, password, created_at, updated_at) VALUES (:value1, :value2, :value3, :value4)");

            $statement->bindParam(':value1', $email);
            $statement->bindParam(':value2', $password);
            $timeconv = $time->format('Y-m-d H:i:s');
            $statement->bindParam(':value3', $timeconv);
            $statement->bindParam(':value4', $timeconv);

            $statement->execute();

            header("Location: login.php");
            exit; // The exit statement is used to terminate the script execution after the header is sent to the browser. This is important because if you don't exit, the script will continue to execute and may output additional content that can interfere with the redirection.
        }
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
            <h4> <?php echo $errorPasswd;?> </h4>
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


