<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="saran kaba kouyate" />
    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/deletearticle.css">
    <title>PlumeOr | Suppression Article</title>
</head>

<?php
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$myid = $_GET['id'] ?? '';
$mycat = $_GET['ca'] ?? '';
$yesdelete = $_GET['supprimer'] ?? '';
$filename = './data/articles.json';
$articles = [];
?>










<?php if (($myid) && ($yesdelete)) : ?>

    <?php



    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        // array_search : cherche la  clé de $myid dans le tableau 
        // syntaxe  : array_search(value, array, strict)
        // value, array : required
        //strict : optionnal
        $articleIndex = array_search($myid, array_column($articles, 'id'));
        //La fonction array_column() renvoie les valeurs d'une seule colonne dans le tableau d'entrée.
        //array_column(array, column_key, index_key)
        //array, column_key : required
        //index_key : optionnal



        array_splice($articles, $articleIndex, 1);
        //array_splice(array, start, length, array)
        //array, start : rrequired
        //length, array : optionnal
        file_put_contents($filename, json_encode($articles));
        header('Location: /');
    }
    ?>


<?php endif; ?>





<body>
    <section class="main-delete">
        <header class="myheader low-opacity">
            <h1>
                <a href="/">PlumeOr</a>
            </h1>
            <ul>

                <li class="myactive"><a href="./addarticle.php">Ajouter Article</a></li>

            </ul>


        </header>


        <section class="message-delete">
            <section class="message-delete-center">
                <h1 class="text-delete">Êtes-vous sûr de vouloir supprimer cet article?</h1>
                <p class="paragraphe-delete">Cette action est irreversible</p>


                <section class="mybutton">
                    <a class="btn btn-danger " href="/deletearticle.php?id=<?= $myid ?>&supprimer=oui">OUI je supprime</a>

                    <a class="btn myretour" href="/article.php?id=<?= $myid ?>&ca=<?= $mycat ?>">NON retour à l'article</a>
                </section>
            </section>


        </section>
















        <footer class="low-opacity">
            <p> PlumeOr 2023 - Tous droits réservés</p>
        </footer>

    </section>
</body>

</html>