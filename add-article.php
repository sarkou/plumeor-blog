<?php
const ERROR_REQUIRED = 'Veuillez renseigner ce champ'; // le message d'erreur quand le champ est vide 
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL = 'L\'image doit être une URL valide';

// on met en place le chemin du fichier ou on va enregistrer les articles
$filename = './data/articles.json';

// on initialise nos premières erreurs: ce sont les éléments sont vides
$errors = [
    'titre' => '',
    'image' => '',
    'categorie' => '',
    'contenu' => '',
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
    $image = $_POST['myimg'] ?? '';
    $category = $_POST['myselect'] ?? '';
    $content = $_POST['content'] ?? '';



    if (!$title) {
        $errors['titre'] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 5) {
        $errors['titre'] = ERROR_TITLE_TOO_SHORT;
    }

    if (!$image) {
        $errors['image'] = ERROR_REQUIRED;
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors['image'] = ERROR_IMAGE_URL;
    }

    if (!$category) {
        $errors['categorie'] = ERROR_REQUIRED;
    }

    if (!$content) {
        $errors['contenu'] = ERROR_REQUIRED;
    } elseif (mb_strlen($content) < 50) {
        $errors['contenu'] = ERROR_CONTENT_TOO_SHORT;
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
                <label for="titre">Titre</label>
                <input type="text" name="title" id="titre" placeholder="titre de votre article" value="<?php if (isset($title)) {
                                                                                                            echo $title;
                                                                                                        } ?>" />
                <span class="text-error">*</span>
                <?php if ($errors['titre']) : ?>
                    <p class="text-error"><?= $errors['titre'] ?></p>
                <?php endif; ?>



            </section>

            <section class="p10">
                <label for="image">Image</label>
                <input type="text" name="myimg" id="image" placeholder="url de votre image" value="<?php if (isset($imagee)) {
                                                                                                        echo $image;
                                                                                                    } ?>" />
                <span class="text-error">*</span>
                <?php if ($errors['image']) : ?>
                    <p class="text-error"><?= $errors['image'] ?></p>
                <?php endif; ?>

            </section>

            <section class="p10">
                <label for="categorie">Catégorie</label>
                <select name="myselect" id="categorie">
                    <option value="technologie">technologie</option>
                    <option value="sante">Santé</option>
                    <option value="histoire">Histoire</option>
                    <option value="societe">Société</option>
                    <option value="politique">Politique</option>
                    <option value="economie">Économie</option>
                    <option value="environnement">environnement</option>
                    <option value="divers">Faits divers</option>
                </select>
                <span class="text-error">*</span>
                <?php if ($errors['categorie']) : ?>
                    <p class="text-error"><?= $errors['categorie'] ?></p>
                <?php endif; ?>


            </section>

            <section class="p10">
                <label for="contenu">Contenu</label>
                <span></span>
                <textarea name="content" id="contenu" cols="30" rows="10" placeholder="écrivez votre article ici"></textarea>
                <span class="text-error">*</span>
                <?php

                if ($errors['contenu']) {
                    echo '<p class="text-error">' . $errors['contenu'] . '</p>';
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