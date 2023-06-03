<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="saran kaba kouyate" />
    <link rel="stylesheet" href="/public/css/index.css">
    <link rel="stylesheet" href="/public/css/style.css">
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
    <?php
    $filename = './data/articles.json';
    $articles = [];

    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
    }
    ?>

    <section class="container">
        <section class="article-container">
            <?php foreach ($articles as $item) : ?>
                <article class="articles">


                    <h2> <?= $item['title'] ?> </h2>
                    <div class="div-img" style='background-image: url(" <?= $item['image'] ?>" );'>

                    </div>

                </article>
            <?php endforeach; ?>
        </section>

    </section>

    <?php
    require_once('./requires/footer.php');
    ?>

</body>

</html>