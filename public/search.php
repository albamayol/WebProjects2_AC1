<!DOCTYPE html>
<html>
<head>
    <title>Page Title</title>
</head>
<body>
<h4>SEARCH</h4>

<section class="recyclerView">
    <ul class="catsList" id="Lista" v-if="!success">
        <li class="listCat" v-for="amigo in amigos" :key="amigo.id">
            <div class="cat">
                <RouterLink :to="{ path: `/profilestranger/${amigo.id} `}">
                    <button id = "catButton" class="catButton">
                        <img :src="amigo.image" alt="Foto Cat"/>
                        <h2 class="nameCat" id = "nameCat"> {{amigo.name}} </h2>
                    </button>
                </RouterLink>
            </div>
        </li>
    </ul>
</section>

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
<?php

?>
