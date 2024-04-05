<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title> Page de connexion</title>
		<link rel="stylesheet" href="Content/css/style_connection.css"/>
	</head>
	<body>

<div class="connection">
<div class="maincontainer">
    <img class="logo" src="Content/img/logo.png" alt="logo">
        <div class="form-container">
            <h2>Connexion</h2>
            <form action="?controller=connection&action=connection"method="post"class="form-login">
                <div class="input-container">
                    <div class="id-container">
                        <label>Identifiant</label><br>
                        <input type="text" name="identifiant"><br>
                    </div>

                    <div class="mdp-container">
                        <label>Mot de passe</label><br>
                        <input type="password" name="mdp">
                    </div>
                    
                </div>

                <p class="mdp-oublie">Vous avez oublié votre mot de passe ?</p>
                <p><?php if(isset($error)){echo $error;}?></p>
                <div class="button-container">
                    <button type="submit" class="connecter">Se connecter</button>
                </div>

            </form>
        </div>
</div>
</div>


<?php require "view_end.php"; ?>