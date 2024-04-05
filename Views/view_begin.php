<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title> Page de connexion</title>
		<link rel="stylesheet" href="Content/css/style.css"/>
	</head>
	<body>
		<nav>
      <a href="#"><img src="Content/img/logo_nav.png" alt="" /></a>
      <ul>
        <li><a href="?controller=dashboard">Tableau de bord</a></li>
        <li><a href="?controller=staffs">Personnel</a></li>

      </ul>
    	</nav>

  
		<div class="profil">
      <div id="gauche">
        <div class="info">
          <h4 id="prenom"><?php if(isset($_SESSION['user']['sess_prenom'])){echo htmlspecialchars($_SESSION['user']['sess_prenom']);}else{echo "Prenom";}?></h4>
          <h4 id="nom"><?php if(isset($_SESSION['user']['sess_nom'])){echo htmlspecialchars($_SESSION['user']['sess_nom']);}else{echo "Nom";}?></h4>
        </div>
        <h6><?php if(isset($_SESSION['user']['sess_role'])){echo htmlspecialchars($_SESSION['user']['sess_role']);}else{echo "";}?></h6>
      </div>
      <div id="droite">
        <img src="Content/img/profil.png" alt="" />
      </div>
		</div>
		

		<main>