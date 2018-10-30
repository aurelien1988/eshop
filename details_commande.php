<?php

    # Définir mon nom de page
    $page = "Récapitulatifs de la commande";

    require_once("inc/header_back.php");
    $req = "SELECT * FROM detail_commande WHERE id_commande = :id";
        $result = $pdo->prepare($req);
        $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $result->execute();

    ?>

    <?= $msg ?>
    <?= $contenu ?>


<?php require_once("inc/footer_back.php"); ?>