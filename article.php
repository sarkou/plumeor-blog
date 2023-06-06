<?php
$filename = './data/articles.json';
$articles = [];
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
$mycategoryname = $_GET['ca'];


function catchcategorie($tab)
{
    foreach ($tab as $key => $value) {
        if ($key === "category") {
            return $tab['category'];
        }
    }
    return $tab;
}
function displaycateg($acc, $current)
{
    if (isset($acc[$current])) {
        $acc[$current]++;
    } else {
        $acc[$current] = 1;
    }
    return $acc;
}

if (!$id) {
    header('Location: /');
} else {
    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        $articleIndex = array_search($id, array_column($articles, 'id'));
        $myarticle = $articles[$articleIndex];

        $categorie = array_map("catchcategorie", $articles);
        $categ = array_reduce($categorie, "displaycateg");
    }
}

?>






<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="saran kaba kouyate" />
    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/article.css">
    <title>PlumeOr | Accueil</title>
</head>

<body>
    <header class="myheader">
        <h1>
            <a href="/">PlumeOr</a>
        </h1>
        <ul>

            <li class="myactive"><a href="./addarticle.php">Ajouter Article</a></li>

        </ul>


    </header>




    <section class="main-myarticles">


        <section class="mycategory">
            <ul>
                <li><a href="/">Tous les articles(<?= count($articles) ?>)</a></li>
            </ul>
            <h2>Categorie</h2>

            <?php foreach ($categ as $key => $num) : ?>
                <li><a href="/?categorie=<?= $key ?>"><?= $key ?></a><span>(<?= $num ?>)</span></li>
            <?php endforeach; ?>
        </section>

        <section class="mycenter">
            <article class="myarticles">

                <?php if (isset($_GET['ca'])) : ?>
                    <p class="mycategname">Catégorie :<a href="/?categorie=<?= $mycategoryname ?>"> <?= $mycategoryname ?> </a></p>


                    <h1 class="article-title"><?= $myarticle['title'] ?></h1>
                    <div class="img-article-div" style="background-image:url(<?= $myarticle['image'] ?>)"></div>

                    <p class="article-content"><?= $myarticle['content'] ?></p>
                    <div class="my-button">
                        <a href="/addarticle.php?id=<?= $myarticle['id'] ?>" class="btn btn-modifier">Modifier</a>
                        <a href="/deletearticle.php?id=<?= $myarticle['id'] ?>&ca=<?= $myarticle['category'] ?>" class="btn btn-delete">Supprimer</a>
                    </div>

                <?php endif ?>
            </article>
        </section>














    </section>

    <?php
    require_once('./requires/footer.php');
    ?>

</body>

</html>