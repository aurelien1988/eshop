<?php

    # Définir mon nom de page
    $page = "Historique des commandes";

    require_once("inc/header_back.php");

    //$result = $pdo->query('SELECT * FROM detail_commande');
    //$details = $result->fetchAll();

    $result = $pdo->query('SELECT d.id_detail_commande,c.id_commande,d.quantite, c.date_enregistrement, c.montant, 
    p.reference,p.titre, p.photo, m.prenom, m.nom,m.code_postal,m.adresse,c.etat AS"Statut commande" FROM commande c , detail_commande d, produit p,
    membre m WHERE 
    c.id_commande = d.id_commande 
    AND d.id_produit = p.id_produit AND c.id_membre = m.id_membre ORDER BY c.date_enregistrement');
    $details = $result->fetchAll();

    $contenu .= "<div class='table-responsive'>";
    $contenu .= "<table class='table table-striped table-sm'>";
    $contenu .= "<thead class='thead-dark'><tr>";
    
    for($i= 0; $i < $result->columnCount(); $i++)
    {

        
        $colonne = $result->getColumnMeta($i);
        //debug($colonne['name']);
        $contenu .= "<th scope='col'>" . ucfirst(str_replace('_', ' ', $colonne['name'])) . "</th>";
    
    }
    
    $contenu .= "<th colspan='2'>Action</th>";
    $contenu .= "</tr></thead><tbody>";
    
    
    foreach($details as $detail)
    {
    
            $contenu .= "<tr>";
            foreach ($detail as $key => $value) 
            {
                
                    $contenu .= "<td>" . $value . "</td>";  
                   
            }

            $contenu .= "<td><a href='?id=".$value['id_membre']. "'><i class='fas fa-pen'></i></a></td>";
    }

    {
        ////if($_GET)
       // if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))

      //  {
      //      $req = "SELECT etat FROM commande WHERE id_membre = :id";

     //       $result = $pdo->prepare($req);
      //      $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
      //      $result->execute();

      //      "<form action="" method="post">
      //     <div class="form-group">
       //       <label for=""></label>
       //       <select class="form-control" name="" id="">
        //        <option></option>
        //        <option></option>
        //        <option></option>
        //      </select>
        //    </div>

         //}
    }
    
    
    
    


    $contenu .= "</tr>";
    $contenu .= "</tbody>";
    $contenu .= "</table>";
    $contenu .= "</div>";

    ?>



    <?=$contenu ?>




    <?php require_once("inc/footer_back.php"); ?>





    
    