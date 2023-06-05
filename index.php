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
    $categorie = [];
    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $selected = $_GET['categorie'] ?? '';
    //echo $selected;


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
    function artcateg($acc, $elem)
    {
        if (isset($acc[$elem['category']])) {
            $acc[$elem['category']] = [...$acc[$elem['category']], $elem];
        } else {
            $acc[$elem['category']] = [$elem];
        }
        return $acc;
    }

    if (file_exists($filename)) {
        $articles = json_decode(file_get_contents($filename), true) ?? [];
        //array_map — Applique une fonction sur les éléments d'un tableau
        //array_map(myfunction, array1, array2, array3, ...)
        //myfunction, array1: sont obligatoires, myfunction est la fonction d callback
        // $categorie : permet d'afficher toutes les categories qui ont des articles
        $categorie = array_map("catchcategorie", $articles);


        //La fonction array_reduce() envoie les valeurs d'un tableau à une fonction définie par l'utilisateur et renvoie une chaîne.
        //syntaxe : array_reduce(array, myfunction, initial)
        //array : required,  myfunction: function of callback required
        //initial : optional 
        //$categ : à pour clé le nom de la categorie et comme valeur le nombre d'articles de cette categorie
        $categ = array_reduce($categorie, "displaycateg");

        //$articlecategorie : permet de creer un nouveau qui aura pour entete la categorie et tous autres element comme valeurs tel que le titre ...
        $articlecategorie = array_reduce($articles, "artcateg");
    }
    ?>

    <section class="container">
        <section class="display-category">
            <ul>
                <li><a href="/">Tous les articles(<?= count($articles) ?>)</a></li>
            </ul>
            <h2>Categorie</h2>

            <?php foreach ($categ as $key => $num) : ?>
                <li><a href="/?categorie=<?= $key ?>"><?= $key ?></a><span>(<?= $num ?>)</span></li>
            <?php endforeach; ?>
        </section>


        <section class="category-container">
            <?php if (!$selected) : ?>
                <?php foreach ($categ as $key => $num) : ?>
                    <h2 class="text-category"><?php echo $key; ?></h2>


                    <section class="article-container">


                        <?php foreach ($articlecategorie[$key] as $item) : ?>
                            <a href="/article.php?id=<?= $item['id'] ?>&ca=<?= $item['category'] ?>">
                                <article class="articles">


                                    <h2> <?= $item['title'] ?> </h2>
                                    <div class="div-img" style='background-image: url(" <?= $item['image'] ?>" );'>

                                    </div>

                                </article>
                            </a>

                        <?php endforeach; ?>

                    </section>

                <?php endforeach; ?>




            <?php else : ?>

                <h2 class="text-category"><?php echo $selected; ?></h2>


                <section class="article-container">


                    <?php foreach ($articlecategorie[$selected] as $item) : ?>
                        <a href="/article.php?id=<?= $item['id'] ?>&ca=<?= $item['category'] ?>">
                            <article class="articles">


                                <h2> <?= $item['title'] ?> </h2>
                                <div class="div-img" style='background-image: url(" <?= $item['image'] ?>" );'>

                                </div>

                            </article>
                        </a>

                    <?php endforeach; ?>

                </section>



            <?php endif; ?>
        </section>






    </section>

    <?php
    require_once('./requires/footer.php');
    ?>

</body>

</html>