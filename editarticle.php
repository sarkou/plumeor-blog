<?php
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL = 'L\'image doit être une url valide';
$filename = './data/articles.json';
$errors = [
    'title' => '',
    'image' => '',
    'category' => '',
    'content' => '',
];
$category = '';

if (file_exists($filename)) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
}

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
if ($id) {
    $articleIndex = array_search($id, array_column($articles, 'id'));
    $article = $articles[$articleIndex];
    $title = $article['title'];
    $image = $article['image'];
    $category = $article['category'];
    $content = $article['content'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_POST = filter_input_array(INPUT_POST, [
        'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'image' => FILTER_SANITIZE_URL,
        'category' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'content' => [
            'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);
    $title = $_POST['title'] ?? '';
    $image = $_POST['image'] ?? '';
    $category = $_POST['category'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!$title) {
        $errors['title'] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 5) {
        $errors['title'] = ERROR_TITLE_TOO_SHORT;
    }

    if (!$image) {
        $errors['image'] = ERROR_REQUIRED;
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = ERROR_IMAGE_URL;
    }

    if (!$category) {
        $errors['category'] = ERROR_REQUIRED;
    }

    if (!$content) {
        $errors['content'] = ERROR_REQUIRED;
    } elseif (mb_strlen($content) < 50) {
        $errors['content'] = ERROR_CONTENT_TOO_SHORT;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
        if ($id) {
            $articles[$articleIndex]['title'] = $title;
            $articles[$articleIndex]['image'] = $image;
            $articles[$articleIndex]['category'] = $category;
            $articles[$articleIndex]['content'] = $content;
        } else {
            $articles = [...$articles, [
                'title' => $title,
                'image' => $image,
                'category' => $category,
                'content' => $content,
                'id' => time()
            ]];
        }
        file_put_contents($filename, json_encode($articles));
        header('Location: /');
    }
}

?>









<?php
require_once('./requires/head.php');
?>

<link rel="stylesheet" href="/public/css/index.css ">
<link rel="stylesheet" href="/public/css/addarticle.css ">
<title>Modifier l'article</title>
</head>

<body>
    <?php
    require_once('./requires/header.php');
    ?>


    <section class="maincontainer">

        <form class="myform ombre" action="./editarticle.php<?= $id ? "?id=$id" : '' ?>" method="post">
            <h2 class="center">Modifier un article</h2>
            <section class="p10 ">
                <label for="title">Titre</label>
                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                </br>
                <input type="text" name="title" id="title" placeholder="titre de votre article" value="<?= $title ?? '' ?>" />

                <?php if ($errors['title']) : ?>
                    <p class="text-error"><?= $errors['title'] ?></p>
                <?php endif; ?>



            </section>

            <section class="p10">
                <label for="image">Image</label>
                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                </br>
                <input type="text" name="image" id="image" placeholder="url de votre image" value="<?= $image ?? '' ?>" />



                <?php if ($errors['image']) : ?>
                    <p class="text-error"><?= $errors['image'] ?></p>
                <?php endif; ?>

            </section>

            <section class="p10">
                <label for="category">Catégorie</label>

                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                <br>
                <select name="category" id="category">

                    <option <?= $category === 'technologie' ? 'selected' : '' ?>value="technologie">technologie</option>


                    <option <?= $category === 'sante' ? 'selected' : '' ?>value="sante">Santé</option>

                    <option <?= $category === 'histoire' ? 'selected' : '' ?>value="histoire">Histoire</option>
                    <option <?= $category === 'societe' ? 'selected' : '' ?>value="societe">Société</option>
                    <option <?= $category === 'politique' ? 'selected' : '' ?>value="politique">Politique</option>
                    <option <?= $category === 'economie' ? 'selected' : '' ?> value="economie">Économie</option>
                    <option <?= $category === 'environnement' ? 'selected' : '' ?>value="environnement">environnement</option>
                    <option <?= $category === 'divers' ? 'selected' : '' ?> value="divers">Faits divers</option>
                </select>

                <?php if ($errors['category']) : ?>
                    <p class="text-error"><?= $errors['category'] ?></p>
                <?php endif; ?>


            </section>

            <section class="p10">
                <label for="content">Contenu</label>
                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                </br>
                <textarea name="content" id="content" cols="30" rows="10" placeholder="écrivez votre article ici"><?= $content ?? '' ?></textarea>



                <?php

                if ($errors['content']) {
                    echo '<p class="text-error">' . $errors['content'] . '</p>';
                }
                ?>
            </section>


            <section class="p10">
                <a href="/" class="btn btn-danger">Annuler</a>
                <button class="btn btn-primary" type="submit" name="mon">Sauvegarder</button>
            </section>

        </form>


    </section>

    <?php
    require_once('./requires/footer.php');
    ?>
</body>

</html>