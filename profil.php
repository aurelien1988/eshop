<?php

    # Règles SEO
    $page = "Mon profil";
    $seo_description = "Regardez votre profil qui est sublime, magnifique, vous êtes une star !";

    require_once("inc/header.php");

    if(!userConnect())
    {
        header("location:connexion.php");
        exit(); // die() fonctionne aussi
    }

    //debug($);

     //debug($_SESSION, 2);
    foreach($_SESSION['user'] as $key => $value)
    {
        $info[$key] = htmlspecialchars($value); # nous vérifions que les informations à afficher ne comporte pas d'injections et ne perturberont pas notre service
    }

<<<<<<< HEAD
     //debug($info);
=======
     debug($info);
>>>>>>> 7f3c956ada09d912e657e04c12850621d9879790


     if(isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "delete" && is_numeric($_GET['id'])) # la fonction is_numeric() me permet de vérifier que le paramètre rentré est bien un chiffre
     {
         $req = "SELECT * FROM membre WHERE id_membre = :id";
         $result = $pdo->prepare($req);
         $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
         $result->execute();
         // debug($result);
 
         if($result->rowCount() == 1)
         {
             $membre = $result->fetch();
             
             //debug($produit);
             
             $delete_req = "DELETE FROM membre WHERE id_membre = $membre[id_membre]";
             
             $delete_result = $pdo->exec($delete_req);
 
             // debug($delete_result);
             
             if($delete_result)
             {
                 
                 header("location:index.php?m=success");
                 unset($_SESSION['user']);
             }
             else
             {
                 header("location:index.php?m=fail");  
             }
             
         }
         else 
         {
             header("location:index.php?m=fail");    
         }
     }
     
     if(isset($_GET['m']) && !empty($_GET['m']))
     {
         switch($_GET['m'])
         {
             case "success":
             $msg .= "<div class='alert alert-success'>Votre profil a bien été supprimé.</div>";
             break;
             case "fail":
             $msg .= "<div class='alert alert-danger'>Une erreur est survenue, veuillez réessayer.</div>";
             break;
             default:
             $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
             break;
         }
     }

   

?>

    <div class="starter-template">
        <h1><?= $page ?></h1>
        <div class="card">


<?php 
        $result= $pdo-> prepare("SELECT photo FROM membre WHERE pseudo=:pseudo");
        $result->bindValue(':pseudo',$info['pseudo'],PDO::PARAM_STR);
        if($result->execute())
        {
        
            $a =$result ->fetch();
            //debug($a);
            }
        
            ?>
            <img class="card-img-top img-thumbnail rounded mx-auto d-block" src='<?=URL. "assets/uploads/user/".$a['photo']?>' alt="Card image cap" style="width:25%;">
            <div class="card-body">
                <h5 class="card-title">Bonjour <?= $info['pseudo'] ?></h5>
                <p class="card-text">Nous sommes râvi de vous revoir sur notre plateforme.</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Prénom: <?= $info['prenom'] ?></li>
                <li class="list-group-item">Nom: <?= $info['nom'] ?></li>
                <li class="list-group-item">Email: <?= $info['email'] ?></li>

                <li class="list-group-item">Civilité: <?php switch($info['civilite']){case "m": echo "homme"; break; case "f": echo "femme"; break; default: echo "Non défini"; break;} ?></li>
                
                <li class="list-group-item">Adresse: <?= $info['adresse'] ?></li>
                <li class="list-group-item">Code postal: <?= $info['code_postal'] ?></li>
                <li class="list-group-item">Ville: <?= $info['ville'] ?></li>
            </ul>
            <div class="card-body">
                <a href="#" class="card-link">Action 1</a>
                <a href="#" class="card-link">Action 2</a>
            </div>
        </div>
    </div>


    <!-- bouton pour modifier le profil -->

    <form action="modif_profil.php" method="get">
   <button type="submit" class="btn btn-primary">Modifier vos information</button>
   </form>
   <br>
    <!-- bouton pour supprimer le profil -->
    <!-- <form action="index.php" method="post">
    <button type="valider" class="btn btn-primary">Suppression profil</button>
    </form> -->


    <a data-toggle='modal' class="btn btn-primary" data-target="#deleteModal<?=$info['id_membre']?> ">Suppression compte</a>
    
    <?php deleteModal($info['id_membre'], $info['prenom'], $info['nom']);
    ?>


<?php require_once("inc/footer.php"); ?>