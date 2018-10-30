<?php

    # Définir mon nom de page
    $page = "Liste utilisateurs";

    require_once("inc/header_back.php");

    if(isset($_GET['a']) && isset($_GET['id']) && $_GET['a'] == "delete" && is_numeric($_GET['id']))
    {
        $req = "SELECT * FROM membre WHERE id_membre = :id";
        $result = $pdo->prepare($req);
        $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $result->execute();
        //debug($result);

        if($result->rowCount() == 1)
        {
            $membre = $result->fetch();
            
            //debug($produit);
            
            $delete_req = "DELETE FROM membre WHERE id_membre = $membre[id_membre]";
            
            $delete_result = $pdo->exec($delete_req);

            // debug($delete_result);
            
            if($delete_result)
            {
                header("location:liste_user.php?m=success");
            }
            else
            {
                header("location:liste_user.php?m=fail");  
            }
            
        }
        else 
        {
            header("location:liste_user.php?m=fail");    
        }
    }

    if(isset($_GET['m']) && !empty($_GET['m']))
    {
        switch($_GET['m'])
        {
            case "success":
            $msg .= "<div class='alert alert-success'>L'utilisateur a bien été supprimé.</div>";
            break;
            case "fail":
            $msg .= "<div class='alert alert-danger'>Une erreur est survenue, veuillez réessayer.</div>";
            break;
            case "update":
            $msg .= "<div class='alert alert-success'>L'utilisateur a bien été mis à jour.</div>";
            break;
            default:
            $msg .= "<div class='alert alert-warning'>A pas compris !</div>";
            break;
        }
    }


    # Je sélectionne tous mes résultats en BDD pour la table utilisateurs
    $result = $pdo->query('SELECT * FROM membre');
    $membres = $result->fetchAll();
    
    // debug($membres);
    
    $contenu .= "<div class='table-responsive'>";
    $contenu .= "<table class='table table-striped table-sm'>";
    $contenu .= "<thead class='thead-dark'><tr>";
    
    for($i= 0; $i < $result->columnCount(); $i++)
    {
        $colonne = $result->getColumnMeta($i);
        if($colonne["name"] == "mdp")
        {
            continue;
        }
        else
        {
            $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
        }
        
    
    }
    
    $contenu .= "<th colspan='2'>Actions</th>";
    $contenu .= "</tr></thead><tbody>";
    
    
    
        foreach($membres as $membre)
        {
           
            $contenu .= "<tr>";
            foreach ($membre as $key => $value) 
            {
                if($key == "mdp")
                {
                   continue;
                }
                else 
                {
                    $contenu .= "<td>" . $value . "</td>";  
                }
             
            }
            
    
            $contenu .= "<td><a href='modification_utilisateur.php?id=" . $membre['id_membre'] . "'><i class='fas fa-pen'></i></a></td>";
    
            $contenu .= "<td><a data-toggle='modal' data-target='#deleteModal" . $membre['id_membre'] . "'><i class='fas fa-trash-alt'></i></a></td>";
    
            # J'appelle ma modal de supression (fonction créée dans fonction.php)
            deleteModal($membre['id_membre'], $membre['pseudo'], "l'utilisateur");
    
            $contenu .= "</tr>";
        }
    
    $contenu .= "</tbody></table>";
    $contenu .= "</div>";
?>

    <?= $msg ?>
    <?= $contenu ?>


<?php require_once("inc/footer_back.php"); ?>