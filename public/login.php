<?php
    require_once 'user.php';

    session_start();
    //INICIALIZAMOS LAS VARIABLES
    $errorPasswd = null;
    $errorMail = null;
    $errorUserNotFound = null;
    $connection = null;

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
    if (!empty($_POST["email"]) && !empty($_POST["password"])) {   //$_POST es un objecte que rarament serà empty del tot (internament té els seus flags, etc)
        $email = $_POST['email'];
        $password = $_POST['password'];
        //check email
        checkEmail($email, $errorMail);
        //check password
        checkPasswd($password, $errorPasswd);

        if (is_null($errorMail) && is_null($errorPasswd)) { //NO hi ha hagut cap error a l'hora de fer sign in
            //If all the data is correct, you will have to check if the user exists in the database to log him in (**you need to start a session for the user**).
            //If the user is not found, you need to display an error in the form.
            try {
                $statement = $connection->prepare('SELECT * FROM Users WHERE email = :email');
                $statement->bindParam(':email', $email, PDO::PARAM_STR);
                $statement->execute();
                $response = $statement->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo 'PDO failed: ' . $e->getMessage();
            }

            if ($response !== false && $password === $response['password']) {
                // Fetch and work with the data
                $user_id = $response['user_id'];
                $_SESSION['user'] = $user_id;
                header("Location: search.php");
                exit;
            } else {
                $errorUserNotFound = 'This user does not exist. Try again';
            }

            /*if ($statement->rowCount() > 0) {
                // Fetch and work with the data
                echo 'all good';
            } else {
                echo 'not in the database';
                // Handle the case where there are no rows returned
            }*/
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
    <h4>LOG IN</h4>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
        <div class="email_input">
            <input type="email" name="email" class="email" id="email" placeholder="something@example.com">
            <h4> <?php echo $errorMail;?> </h4>
        </div>
        <div class="password_input">
            <input type="password" name="password" class="password" id="password" placeholder="Password">
            <h4> <?php echo $errorPasswd;?> </h4>
        </div>
        <button type="submit">Log in</button>
        <h4> <?php echo $errorUserNotFound;?></h4>
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


