<?php
const ERROR_REQUIRED = 'Veuillez renseigner ce champ'; // le message d'erreur quand le champ est vide 
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL = 'L\'image doit être une URL valide';

// on met en place le chemin du fichier ou on va enregistrer les articles
$filename = './data/articles.json';
if (file_exists($filename)) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
}

// on initialise nos premières erreurs: ce sont les éléments sont vides
$errors = [
    'title' => '',
    'image' => '',
    'category' => '',
    'content' => '',
];

//filter_input_array : Récupère plusieurs valeurs externes et les filtre
// filter_input_array le premier parametre est obligatoire il concerne le type
//ce type peut etre : Une constante parmi INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER ou INPUT_ENV.
// un deuxieme parametre non obligatoire qui peut être un tableau
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

    // emptyfunc() est la fonction de callback
    //cette fonction ne retourne que les elements qui ne sont pas vides
    function emptyfunc($myitem)
    {
        return (!empty($myitem));
    }
    //array_filter — Filtre les éléments d'un tableau grâce à une fonction de rappel
    //array_filter Returns the filtered array
    //syntaxe : array_filter(array, callbackfunction, flag)
    //array : required ,  callbackfunction, flag : optional

    if (empty(array_filter($errors, "emptyfunc"))) {
        //if (empty(array_filter($errors))) { //façon simplifiée d'écrire sans fonction de callback et ça marche parfaitement
        $articles = [...$articles, [
            'title' => $title,
            'image' => $image,
            'category' => $category,
            'content' => $content,
            'id' => time()
        ]];

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
<title>ajouter article</title>
</head>

<body>
    <?php
    require_once('./requires/header.php');
    ?>


    <section class="maincontainer">

        <form class="myform ombre" action="./addarticle.php" method="post">
            <h2 class="center">Écrire un article</h2>
            <section class="p10 ">
                <label for="title">Titre</label>
                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                </br>
                <input type="text" name="title" id="title" placeholder="titre de votre article" value="<?php if (isset($title)) {
                                                                                                            echo $title;
                                                                                                        } ?>" />

                <?php if ($errors['title']) : ?>
                    <p class="text-error"><?= $errors['title'] ?></p>
                <?php endif; ?>



            </section>

            <section class="p10">
                <label for="image">Image</label>
                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                </br>
                <input type="text" name="image" id="image" placeholder="url de votre image" value="<?php if (isset($image)) {
                                                                                                        echo $image;
                                                                                                    } ?>" />

                <?php if ($errors['image']) : ?>
                    <p class="text-error"><?= $errors['image'] ?></p>
                <?php endif; ?>

            </section>

            <section class="p10">
                <label for="category">Catégorie</label>

                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                <br>
                <select name="category" id="category">
                    <option value="technologie">technologie</option>
                    <option value="sante">Santé</option>
                    <option value="histoire">Histoire</option>
                    <option value="societe">Société</option>
                    <option value="politique">Politique</option>
                    <option value="economie">Économie</option>
                    <option value="environnement">environnement</option>
                    <option value="divers">Faits divers</option>
                </select>

                <?php if ($errors['category']) : ?>
                    <p class="text-error"><?= $errors['category'] ?></p>
                <?php endif; ?>


            </section>

            <section class="p10">
                <label for="content">Contenu</label>
                <span class="text-error" message-required="ce champ est obligatoire">*</span>
                </br>
                <textarea name="content" id="content" cols="30" rows="10" placeholder="écrivez votre article ici"><?php if (isset($category)) {
                                                                                                                        echo $category;
                                                                                                                    } ?></textarea>

                <?php

                if ($errors['content']) {
                    echo '<p class="text-error">' . $errors['content'] . '</p>';
                }
                ?>
            </section>


            <section class="p10">
                <button class="btn btn-danger" type="button">Annuler</button>
                <button class="btn btn-primary" type="submit" name="mon">Sauvegarder</button>
            </section>

        </form>


    </section>

    <?php
    require_once('./requires/footer.php');
    ?>
</body>

</html>