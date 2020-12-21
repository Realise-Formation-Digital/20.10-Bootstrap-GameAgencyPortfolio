<?php

//	Initialisation des variables
$error = array();
$user = '';
$email = '';
$password1 = '';
$password2 = '';
$mdpMin = 6;




if ((isSet($_POST['gestion'])) && ($_POST['gestion'] == 'Envoyer')) {
	
	
	//	Controle que le champ a bien �t� renseign�
	if ($_POST['user'] == '') { $error[] = 'Votre nom d&rsquo;utilisateur est vide'; }
	if ($_POST['email'] == '') { $error[] = 'Vous devez sp&eacute;cifier votre email'; }
	if ($_POST['password1'] == '') { $error[] = 'Mot de passe 1 vide'; }
	if ($_POST['password2'] == '') { $error[] = 'Mot de passe 2 vide'; }
	
	// Controle de la validit� de l'email
	if (preg_match_all('/[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z]{2,9}/',$_POST['email'],$tblSortie) == 0) {
		$error[] = 'Votre adresse email comporte des erreurs';
	}
	
	//	Control du mot de passe
	if (strlen($_POST['password1']) < $mdpMin) { $error[] = 'Votre mot de passe doit contenir plus de '.$mdpMin.' caract&egrave;res pour que votre inscription soit valide!'; }
	if ($_POST['password1'] != $_POST['password2']) { $error[] = 'Vos deux mots de passe sp&eacute;cifi&eacute; ne sont pas identiques'; }
	
	
	
	//	Recherche si le "user" est d�j� utilis�
	$find = 0;
	if (file_exists('users.csv')) {
		$fp = fopen('users.csv','r');
		while (($data = fgetcsv($fp)) !== FALSE) {
			$row = explode(';',$data[0]);
			if ($row[0] == $_POST['user']) { $find++; }
		} fclose($fp);
	}
	if ($find > 0) { $error[] = 'Utilisateur existe d&eacute;j&agrave;...'; }
	
	
	
	
	
	
	// Enregistrement des donn�es
	if (count($error) == 0) {
		
		
		//	Conversion des donn�es
		$mdp = md5($_POST['password1']);
		
		// Cr�ation d'une table comportant les donn�es envoy�es
		$liste = array($_POST['user'],$_POST['email'],$mdp);
		
		
		//	Ecriture dans le fichier CSV
		$fp = fopen('users.csv','a+',';');
		fputcsv($fp,$liste,';','"');
		fclose($fp);
		
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
          integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<nav>
    <div class="topnav">
        <a class="active" href="index.html">Home</a>
        <a href="news.html" target="news.html">News</a>
        <a href="contact.php">Contact</a>
        <div class="search-container">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#loginModal">Connexion</button>  
        </div>
    </div>
    <div style="padding-left:16px">
    </div>
</nav>
<section>
    <div class="jumbotron text-center">
        <h1>ADALT Agency</h1>
        <p>Votre Agence de Game développement multisupports</p>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <form method="POST">
                                <h3>Inscription</h3><br />
                                <?php
                                //	Affichage des erreurs de contr�le
                                if (count($error) > 0) { for ($i = 0; $i < count($error); $i++) { echo $error[$i].'<br />'; } }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Entrer votre nom d'utilisateur </label><input type="text" name="user" placeholder="Entrer votre nom" class="form-control" value="<?php if (isSet($_POST['user']) && ($_POST['user'] != '')) { echo $_POST['user']; } ?>" />
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Entrer votre e-mail </label><input type="text" name="email" class="form-control" placeholder="Entrer votre e-mail" value="<?php if (isSet($_POST['email']) && ($_POST['email'] != '')) { echo $_POST['email']; } ?>" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                 <label>Entrer votre mot de passe </label><input class="form-control" type="password" name="password1" value="" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Confirmer votre mot de passe </label><input class="form-control" type="password" name="password2" value="">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="submit" name="gestion" class="btn btn-info" value="Envoyer" />
                            </div>
                        </div>
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>

</section>

<!-- Modal Login-->
<form name="login" method="POST" action="login.php">
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header border-bottom-0">
        <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-title text-center">
          <h4>Login</h4>
        </div>
        <div class="d-flex flex-column text-center">
          <form>
            <div class="form-group">
              <input type="text" class="form-control" name="user" placeholder="Nom d'utilisateur...">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="pwd" placeholder="Mot de passe...">
            </div>
            <button type="submit" class="btn btn-info btn-block btn-round" name="gestion" value="Connexion">Connexion</button>
          </form>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <div class="signup-section">Pas encore membre? <a href="register.php" class="text-info"> S'inscrire</a>.</div>
      </div>
  </div>
</div>
</form>

    
<script src="script.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js"
        integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s"
        crossorigin="anonymous"></script>
<script src="js/formValidation.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
</body>
</html>