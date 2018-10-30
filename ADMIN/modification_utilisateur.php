<?php
    # Définir mon nom de page
    $page = "Modification des utilisateurs";

    require_once("inc/header_back.php");
    if($_POST)
    {

        //debug($_POST, 2);

        # Je vérifie le pseudo
        if(!empty($_POST['pseudo']))
        {
            $pseudo_verif = preg_match("#^[a-zA-Z0-9-._]{3,20}$#", $_POST['pseudo']);
            # Ici, nous allons utiliser une expression régulière (REGEX). Une REGEX nous permet de vérifier une condition.
            # la fonction preg_match() nous permet de vérifier si une variable respecte la REGEX rentrée. Elle prend 2 arguments : REGEX + le résultat à vérifier. Elle nous retourne un TRUE/FALSE

            if(!$pseudo_verif) # équivaut à dire $pseudo_verif est FALSE
            {
                $msg .= "<div class='alert alert-danger'>Votre pseudo doit contenir des lettres (minuscules ou majuscules), un chiffre et doit posséder entre 3 et 20 caractères. Vous pouvez utiliser un caractère spécial ('-', '.', '_'). Veuillez réessayer !</div>";
            }

        }
        else 
        {
            $msg .= "<div class='alert alert-danger'>Veuillez rentrer un pseudo.</div>";
        }
        # Je vérifie l'email
        if(!empty($_POST['email']))
        {
            $email_verif = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
            # la fonction filter_var() me permet de vérifier un résultat (email, URL ...). Elle prend 2 arguments : le résultat à vérifier + la méthode. Nous avons un retour un BOOL (TRUE/FALSE)

            $email_interdits = [
                'mailinator.com',
                'yopmail.com',
                'mail.com'
            ];

            $email_domain = explode('@', $_POST['email']); # On utilise la function explode() pour exploser un résultat en 2 partie selon le caractère choisit. Elle prend 2 arguments : le caractère ciblé, le résultat à analyser 

            // debug($email_domain);
            
            if(!$email_verif || in_array($email_domain[1], $email_interdits))
            # la fonction in_array() nous permet de vérifier que le résultat ciblé fait bien partie de l'ARRAY ciblé. Elle prends 2 arguments: le résultat à vérifier + le tableau ciblé
            {
                $msg .= "<div class='alert alert-danger'>Veuillez rentrer un email valide.</div>";
            }

        }
        else 
        {
            $msg .= "<div class='alert alert-danger'>Veuillez rentrer un email.</div>";
        }

        # Je vérifie que la civilité est valide
        if(!isset($_POST['civilite']) || ($_POST['civilite'] != "m" && $_POST['civilite'] != "f" && $_POST['civilite'] != "o"))
        {
            $msg .= "<div class='alert alert-danger'>Veuillez rentrer votre civilité.</div>";
        }

        if(empty($msg))
        {
            // check si le pseudo est dispo
            $result = $pdo->prepare("SELECT pseudo FROM membre WHERE pseudo = :pseudo");
            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->execute();

            if($result->rowCount() == 1)
            {
                $msg .= "<div class='alert alert-danger'>Le pseudo $_POST[pseudo] est déjà pris, veuillez en choisir un autre.</div>";
            }
            else 
            {
                $result = $pdo->prepare("UPDATE membre SET pseudo=:pseudo, nom=:nom, prenom=:prenom, email=:email, civilite=:civilite, ville=:ville, code_postal=:code_postal, adresse=:adresse, statut=:statut WHERE id_membre=:id_membre");
                
                
            }

            $result->bindValue(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
            $result->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $result->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
            $result->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $result->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $result->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
            $result->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
            $result->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
            $result->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
            $result->bindValue(':statut', $_POST['statut'], PDO::PARAM_INT);

            if($result->execute())
            {
                //$msg .= "<div class='alert alert-success'>La modification a bien été enregistrée bien enregistré.</div>";

                header("location:liste_user.php");
            }
        }
    }
    if($_GET)
        {
    
           if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
            {
                $req = "SELECT * FROM membre WHERE id_membre = :id";
    
                $result = $pdo->prepare($req);
                $result->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                $result->execute();
    
                if($result->rowCount() == 1)
                {
                    $modif_membre = $result->fetch();
    
                   
                }
                else 
                {
                    $msg .= "<div class='alert alert-danger'>Aucune correspondance en base de donnée.</div>";
                }
            }
            else 
            {
                $msg .= "<div class='alert alert-danger'>Aucune correspondance en base de donnée.</div>";

            }
        }
    # Je souhaite conserver les valeurs rentrées par l'utilisateur durant le processus de rechargement de la page

    $id_membre = (isset($modif_membre['id_membre'])) ? $modif_membre['id_membre'] : '';
    $pseudo = (isset($modif_membre['pseudo'])) ? $modif_membre['pseudo'] : '';
    $prenom = (isset($modif_membre['prenom'])) ? $modif_membre['prenom'] : '';
    $nom = (isset($modif_membre['nom'])) ? $modif_membre['nom'] : '';
    $email = (isset($modif_membre['email'])) ? $modif_membre['email'] : '';
    $adresse = (isset($modif_membre['adresse'])) ? $modif_membre['adresse'] : '';
    $code_postal = (isset($modif_membre['code_postal'])) ? $modif_membre['code_postal'] : '';
    $ville = (isset($modif_membre['ville'])) ? $modif_membre['ville'] : '';
    $civilite = (isset($modif_membre['civilite'])) ? $modif_membre['civilite'] : '';
    $statut = (isset($modif_membre['statut'])) ? $modif_membre['statut'] : '';
?>

        <div class="starter-template">
            <h1><?= $page ?></h1>
            <form action="" method="post">
                <?= $msg ?>
                <input type="hidden" name="id_membre" value="<?=$id_membre?>">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" placeholder="Modifier le pseudo ..." name="pseudo" required value="<?= $pseudo ?>">
                    
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" placeholder="Modifier le prénom ..." name="prenom" value="<?= $prenom ?>">
                </div>
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" placeholder="Modifier le nom ..." name="nom" value="<?= $nom ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Modifier le email ..." name="email" value="<?= $email ?>">
                </div>
                <div class="form-group">
                    <label for="civilite">Civilité</label>
                    <select class="form-control" id="civilite" name="civilite">
                        <option value="f" <?php if($civilite == 'f'){echo 'selected';} ?> >Femme</option>
                        <option value="m" <?php if ($civilite == 'm') {echo 'selected';} ?> >Homme</option>
                        <option value="o" <?php if ($civilite == 'o') {echo 'selected';} ?> >Je ne souhaite pas le préciser</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <input type="text" class="form-control" id="adresse" placeholder="Modifier l'adresse ..." name="adresse" value="<?= $adresse ?>">
                </div>
                <div class="form-group">
                    <label for="code_postal">Code postal</label>
                    <input type="text" class="form-control" id="code_postal" placeholder="Modifier le code postal ..." name="code_postal" value="<?= $code_postal ?>">
                </div>
                <div class="form-group">
                    <label for="ville">Ville</label>
                    <input type="text" class="form-control" id="ville" placeholder="Modifier la ville ..." name="ville" value="<?= $ville ?>">
                </div>
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select class="form-control" id="statut" name="statut">
                        <option value="0" <?php if ($statut == 'm') {echo 'selected';} ?> >Utilisateur</option>
                        <option value="1" <?php if($statut == '1'){echo 'selected';} ?> >Administrateur</option>
                        
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Modification</button>
            </form>
        </div>
        



</form>

<?php require_once("inc/footer_back.php"); ?>