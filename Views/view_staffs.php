<?php require_once "view_begin.php"; ?>
<div class="modal-container">
    <div class="overlay modal-trigger"></div>
    <div class="modal" id="ajouterForm"> 
        <button class="close-modal modal-trigger">X</button>
        <form action="?controller=staffs&action=add" method="post" class="form-modal">

        <div class="l1">
            <div class="inptcontent">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required>
            </div>
 

            <div class="inptcontent">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required>
                </div>
        
        </div>

        <div class="l2">
            <div class="inptcontent">
            <label for="prenom">E-mail</label>
            <input type="text" id="email" name="email" required> 
            </div>
        </div>

        <div class="l3">
            <div class="inptcontent">
            <label for="role" >Rôle</label>
            <select name="role" id="role" onchange="toggleDivs()">
                <option value="enseignant">Enseignant</option>
                <option value="equipedirection">Équipe de direction</option>
                <option value="secretaire">Secrétaire</option>
            </select>
            </div>
        </div>

        <div class="l4">
        <div class="inptcontent">
            <label for="discipline">Discipline</label>
            <select name="discipline">
                <option value="MATH">MATH</option>
                <option value="INFO-PROG">INFO-PROG</option>
                <option value="INFO-INDUSTRIEL">INFO-INDUSTRIEL</option>
                <option value="INFO-RESEAU">INFO-RESEAU</option>
                <option value="INFO-BUREAUTIQUE">INFO-BUREAUTIQUE</option>
                <option value="ECOGESTION">ECOGESTION</option>
                <option value="ELECTRONIQUE">ELECTRONIQUE</option>
                <option value="DROIT">DROIT</option>
                <option value="ANGLAIS">ANGLAIS</option>
                <option value="COMMUNICATION">COMMUNICATION</option>
                <option value="ESPAGNOL">ESPAGNOL</option>
            </select>
            </div>

        </div>


        <div class="l5">
        <div class="inptcontent">
            <label for="statut">Catégorie d'agent</label>
            <select name="statut">
            <option value="PR">PR</option>
            <option value="MCF">MCF</option>
            <option value="ESAS">ESAS</option>
            <option value="PAST">PAST</option>
            <option value="ATER">ATER</option>
            <option value="VAC">VAC</option>
            <option value="DOC">DOC</option>
            <option value="CDD">CDD</option>
            <option value="CDI">CDI</option>
            </select>
        </div>
        </div>
        <div class="modaladd">
        <input type="submit" value="Ajouter" >
        </div>
            
        </form>
    </div>

    
    <div class="modal" id="modifierForm"> 
    
        <button class="close-modal modal-trigger">X</button>
        <form action="?controller=staffs&action=modify" method="post" class="form-modal">

        <input type="hidden" name="id_personne" value="<?php if(isset($_GET['id_personne'])){echo $_GET['id_personne'];}?>">
        <input type="hidden" name="role" value="<?php if(isset($_GET['role'])){echo $_GET['role'];}?>">
        <div class="l1">
            <div class="inptcontent">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?php if(isset($_GET['nom'])){echo $_GET['nom'];}?>" required>
            </div>
            

            <div class="inptcontent">
            <label for="prenom">Prénom</label>
            
            <input type="text" id="prenom" name="prenom" value="<?php if(isset($_GET['prenom'])){echo $_GET['prenom'];}?>" required>
            
            </div>
        
        </div>

        <div class="l2">
            <div class="inptcontent">
            <label for="prenom">E-mail</label>
            <input type="text" id="email" name="email" value="<?php if(isset($_GET['email'])){echo $_GET['email'];}?>" required> 
            </div>
        </div>
        <div class="modaladd">
        <input type="submit" value="Modifier" >
        </div>
            
        </form>
        
    </div>

</div>

<div class="staffs">

<h1>Personnel</h1>

<div class="menu">
    <div class="gauche">
        <div>
            <button id="afficherFormulaire">Trier</button>
        </div>
        
        <form action="?controller=staffs&action=recherche" method="post">
            
            <input type="text" name="r_nom" placeholder="Chercher un enseignant" id="champsChercher" value="<?php if(isset($val_rech)){echo $val_rech;}?>">
            <button type="submit">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </form>
    </div>


    <div class="droite">
        <button id="ajouterEnseignant" class="modal-trigger">Ajouter</button>
    </div>
</div>



<form action="?controller=staffs&action=trier" method="post" id="formulaireTrier">

    <label for="role">Rôle:</label>
    <select name="role">
                <option value="enseignant">Enseignant</option>
                <option value="equipedirection">Équipe de direction</option>
                <option value="secretaire">Secrétaire</option>
                <option value="directeur">Directeur</option>
        </option>
    </select>


    

        <label for="order">Ordre:</label>
        <select name="order" id="order">
            <option value="asc" >Croissant</option>
            <option value="desc">Décroissant</option>
        </select>

        <button type="submit">
            <i class='fa-solid fa-check'></i>
        </button>
</form>

<?php if(isset($message)){ echo "<p id='message'>" . htmlspecialchars($message) . "</p>";}?>
<?php if(isset($new_mdp)){ echo "<p id='message'>Voici le mot de passe : " .htmlspecialchars($new_mdp). "</p>";}?>
<?php if (!empty($data['error_rech']) || isset($data['error_rech'])): ?>
    <?= "<p id='message'>" . htmlspecialchars($data['error_rech']). "</p>"?>
<?php else : ?>
    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>E-mail</th>
            <th>Rôle</th>
        </tr>

    <?php foreach ($users as $user): ?>
        <tr class="alternate-row">
            <td><?php if(isset($user['nom'])){echo $user['nom'];}?></td>
            <td><?php if(isset($user['prenom'])){echo $user['prenom'];}?></td>
            <td><?php if(isset($user['email'])){echo $user['email'];}?></td>
            <td><?php if(isset($user['role'])){echo $user['role'];}?>
            <a href="?controller=staffs&nom=<?php if(isset($user['nom'])){ echo $user['nom'];}?>&prenom=<?php if(isset($user['prenom'])){ echo $user['prenom'];}?>&email=<?php if(isset($user['email'])){echo $user['email'];}?>&id_personne=<?php if(isset($user['id_personne'])){echo $user['id_personne'];}?>&role=<?php if(isset($user['role'])){ echo $user['role'];}?>"><i class="fa-regular fa-pen-to-square"></i></a>
            <a href="?controller=staffs&action=del&id=<?php if(isset($user['id_personne'])){ echo $user['id_personne']; }?>&role=<?php if(isset($user['role'])){ echo $user['role'];}?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?');">
            <i class="fa-solid fa-xmark"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
</table>
  
</div>

<script src="Content/js/script.js"></script>

<?php require "view_end.php"; ?>