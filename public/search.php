<?php
    require_once 'api.php';
    session_start();

    //VARIABLES
    $connection = null;

    function displayGallery($images) :void {
        echo '<div class="gallery" style="display: flex; flex-wrap: wrap;">';
        foreach ($images as $image) {
            echo '<div style="flex: 1 0 20%; margin: 5px;">';
            echo '<img src="' . $image['url'] . '" width="400" height="auto">';
            echo '<h5>Anchura: ' . $image['width'] . ' pixels</h5>';
            echo '<h5>Altura: ' . $image['height'] . ' pixels</h5>';
            echo '</div>';
        }
    }

    //If users try to manually access to this page without being logged in, they should be redirected to the login page.
    if (!isset($_SERVER['HTTP_REFERER']) || empty($_SERVER['HTTP_REFERER'] || !isset($_SESSION['user_id']))) {
        // User manually entered the page
        //le redirigimos a login.php
        header("Location: login.php");
        exit;
    }


    //CONNECTION TO THE DATABASE
    try {
        $connection = new PDO('mysql:host=db;dbname=LSCat', 'root', 'admin');
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'PDO failed: ' . $e->getMessage();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['query'])) {
            $query = $_POST['query'];

            //////////insert BBDD//////////
            //cogemos el user_id de la session
            $user_id = $_SESSION['user'];

            //primer insertem la info a la taula Search
            $statement = $connection->prepare("INSERT INTO Search (query, timestamp) VALUES (:value1, :value2)");
            $statement->bindParam(':value1', $query);
            $time = new DateTime('now');
            $timestamp = $time->format('Y-m-d H:i:s');
            $statement->bindParam(':value2', $timestamp);
            $statement->execute();

            //segon, agafem el cat_id de la taula
            $statement = $connection->prepare('SELECT Search.cat_search_id FROM Search ORDER BY Search.cat_search_id DESC LIMIT 1');
            $statement->execute();
            $response = $statement->fetch(PDO::FETCH_ASSOC);
            $cat_id = $response['cat_search_id'];


            //tercer, insertem la info a la taula UserSearch
            $statement = $connection->prepare("INSERT INTO UserHistory (user_id, cat_search_id) VALUES (:value1, :value2)");
            $statement->bindParam(':value1', $user_id);
            $statement->bindParam(':value2', $cat_id);
            $statement->execute();

            ///////////API CALL////////////
            $api = new api("live_vlhGWzxNSmGnZED6PSXbvcfLPCTubsUxK8ZQQhhC3debp7r0VXVpFV581eXqAPDn");
            $images = $api->getImages($query);

        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>
<h4>SEARCH</h4>

<section class="Catsform">
    <form action="exit.php" method="post">
        <button type="submit" name="logout" class="logout">Log Out</button>
    </form>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post" class="form">
        <input type="search" name="query" class="query" id="query" placeholder="input">
        <button type="submit">Search</button>
    </form>
</section>

<section class="recyclerView">
    <ul class="catsList" id="Lista">
        <?php
            if (!empty($images)) {
                displayGallery($images);
            } else {
                echo 'This breed does not exist. Try again';
            }
        ?>
    </ul>
</section>

<!--<section class="recyclerView">
    <ul class="catsList" id="Lista" v-if="!success">
        <li class="listCat" v-for="amigo in amigos" :key="amigo.id">
            <div class="cat">
                <img src="" alt="Foto Cat">
                <h5 class="heightCat" id="heightCat"></h5>
                <h5 class="weightCat" id="weightCat"></h5>
                <RouterLink :to="{ path: `/profilestranger/${amigo.id} `}">
                    <button id = "catButton" class="catButton">
                        <img :src="amigo.image" alt="Foto Cat"/>
                        <h2 class="nameCat" id = "nameCat"> {{amigo.name}} </h2>
                    </button>
                </RouterLink>
            </div>
        </li>
    </ul>
</section>-->

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

</style>

