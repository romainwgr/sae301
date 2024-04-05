<?php

class Controller_staffs extends Controller
{
    public function action_staffs()
    {
        $m = Model::getModel();
        $users = $m->getPersonne();

        $data = [
            'users' => $users,
            'matieres'=>[
                'Mathématiques'=>'MATH',
                'Informatique - Programmation'=>'INFO-PROG',
                'Informatique - Industriel'=>'INFO-INDUSTRIEL',
                'Informatique - Réseau'=>'INFO-RESEAU',
                'Informatique - Bureautique'=>'INFO-BUREAUTIQUE',
                'Ecogestion'=>'ECOGESTION',
                'Electronique'=>'ELECTRONIQUE',
                'Droit'=>'DROIT',
                'Anglais'=>'ANGLAIS',
                'Communication'=>'COMMUNICATION',
                'Espagnol' =>'ESPAGNOL'
            ],
            'roles'=> [
                'Tous les rôles' => 'personne',
                'Enseignant' => 'enseignant',
                'Secrétaire' => 'secretaire',
                'Directeur' => 'directeur',
                'Chef de département' => 'equipedirection'
            ],
            'categorie'=> [
                'PR',     
                'MCF',
                'ESAS',  
                'PAST',     
                'ATER',   
                'VAC',   
                'DOC',  
                'CDD',           
                'CDI'
            ]
        ];

        // Appel de la méthode render avec les données
        $this->render('staffs', $data);
    }

    /*public function action_recherche() {
        $m = Model::getModel();
        $recherche = '';
        if (isset($_POST['r_nom'])) {
            $recherche = $_POST['r_nom'];
            $recherche = ucfirst(strtolower($recherche));
        }
    
        // Débogage de la recherche
        echo "Recherche : " . $recherche . "<br>";
    
        $ids = $m->rechercheEnseignants($recherche);
    
        // Débogage des IDs retournés
        echo "<pre>IDs: "; print_r($ids); echo "</pre>";
    
        $users = [];
        foreach ($ids as $id) {
            $user = $m->getPersonneById($id);
            // Débogage de l'utilisateur récupéré
            echo "<pre>"; print_r($user); echo "</pre>";
    
            if ($user) {
                $users[] = $user;
            }
        }
    
        $data = [
            "users" => $users,
            "val_rech" => $recherche
        ];
    
        $this->render("staffs", $data);
    }
    */
    public function action_recherche() {
        $m = Model::getModel();
        $recherche = '';
        if (isset($_POST['r_nom'])) {
            $recherche = $_POST['r_nom'];
            $recherche = ucfirst(strtolower($recherche));
        }
        $resultat = $m->rechercheEnseignants($recherche);
        $ids = array_column($resultat, 'id_personne');
        $users = $m->getPersonnesByIds($ids);
    
        // Vérifier si $users est un message d'erreur
        $isError = is_string($users);
    
        $data = [
            "users" => $users,
            "val_rech" => $recherche,
            "error_rech" => $isError ? $users : null
        ];
    
        $this->render("staffs", $data);
    }
    
    public function action_trier(){
        $m = Model::getModel();
        // Récupération des valeurs entrées par l'utilisateur
        $role = $_POST['role'];
        $order = $_POST['order'];
        $ids = $m->getRole($role);
        $users=  $m->getPersonnesByIds($ids,$order);
        if(is_string($users)){
            $data =[
                'error_rech' => "Personne n'a ce rôle .."
            ];
            $this->render('staffs',$data);
            
            
        }else{
            $data = [
                'users' => $users
            ];
            $this->render('staffs',$data);
        }
        
    }

    public function action_default()
    {
        $this->action_staffs();
    }

    public function action_add()
    {
        $m = Model::getModel();
        if(isset($_SESSION['user']['sess_role'])&&
            $_SESSION['user']['sess_role'] =='Enseignant' ||
            $_SESSION['user']['sess_role'] =='Secretaire'
        )
        {

            $data = [
                'message'=>"Vous n'avez pas les droits requis",
                'users' => $m->getPersonne(), 
            ];
            $this->render('staffs',$data);
        }
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $categorie= $_POST['statut'];
        $discipline = $_POST['discipline'];
        $role=$_POST['role'];
        if(
            isset($nom,$prenom,$email) &&
            !empty($nom) &&
            !empty($prenom) &&
            !empty($email) &&
            preg_match("/^[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+([' -]?[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+)*$/", $prenom) &&
            preg_match("/^[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+([' -]?[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+)*$/", $nom) &&
            preg_match("#^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$#", $email)

        )
        {
        
            

            $prenom=ucfirst(strtolower($prenom));
            $nom= ucfirst(strtolower($nom));

            // Converti en ASCII le prenom et nom
            // gonçalves -> goncalves
            
            $prenom = iconv('UTF-8', 'ASCII//TRANSLIT', $prenom);
            $nom = iconv('UTF-8', 'ASCII//TRANSLIT', $nom);        

            //Générer un mot de passe

            $mdp = "";
            $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            for ($i = 0; $i < 10; $i++)
            {
                $mdp .= $caracteres[random_int(0, strlen($caracteres) - 1)];
            }      
        
            $mdp2 = password_hash($mdp,PASSWORD_DEFAULT);
            
            $count= $m->getLastId();
            $id= $count +1;
            if ($m->add_person($id, $nom, $prenom, $email, $mdp2)) {
                if($role == 'secretaire'){
                    $m->ajouterRole($id,$role);
                    
                }
                else{
                    $idDiscipline=$m->getDiscipline($discipline);
                    $idCategorie=$m->getCategorie($categorie);
                    $m->ajouterEnseignant($id,$idDiscipline,$idCategorie,2024);
                    if($role !='enseignant'){
                        $m->ajouterRole($id,$role);
                    }
                }
                $data= [
              
                    "new_nom" => $nom,
                    "new_prenom" => $prenom,
                    "new_mail" => $email,
                    "new_mdp" =>$mdp,
                    "users" =>$m->getPersonne()
                ];
              
                $this->render('staffs',$data);
                
            } else {
                
                $data=[
                    'message'=>'Erreur de création de personne'
                ];
              
                $this->render('staffs',$data);
            }
        }
        else{
            $data=[
                'message'=>'Erreur de création de personne'
            ];
          
            $this->render('staffs',$data);
        }
        
    }
    public function action_del()
    {
        $m = Model::getModel();
        if(isset($_SESSION['user']['sess_role'])&&
            $_SESSION['user']['sess_role'] =='Enseignant' ||
            $_SESSION['user']['sess_role'] =='Secretaire'
        )
        {

            $data = [
                'message'=>"Vous n'avez pas les droits requis",
                'users' => $m->getPersonne(), 
            ];
            $this->render('staffs',$data);
        }
        $id=$_GET['id'];
        $role = $_GET['role'];
        
        if ($role == 'secretaire'){
            $m->supprimerSecretaire($id);
            $message = "La personne a bien été supprimée";
        }
        else{
            $message=$m->supprimerEnseignant($id); 
        }        
        $data = [
            "users" => $m->getPersonne(),
            "message" => $message
        ];
        
        $this->render('staffs',$data);
    }
    
    public function action_modify()
    {
        $m = Model::getModel();
        $m = Model::getModel();
        $id_personne = $_POST['id_personne'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        
        if(isset($_SESSION['user']['sess_role'])&&
            $_SESSION['user']['sess_role'] =='Enseignant' ||
            $_SESSION['user']['sess_role'] =='Secretaire' ||
            $_SESSION['user']['sess_role'] == 'Equipe de Direction'
        )
        {
            $data = [
                'message'=>"Vous n'avez pas les droits requis",
                'users' => $m->getPersonne(), 
            ];
            $this->render('staffs',$data);
        }
        if(
            isset($nom,$prenom,$email,$id_personne) &&
            !empty($nom) &&
            !empty($prenom) &&
            !empty($email) &&
            preg_match("/^[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+([' -]?[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+)*$/", $prenom) &&
            preg_match("/^[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+([' -]?[a-zA-ZéèêëàâäôöûüçÉÈÊËÀÂÄÔÖÛÜÇ]+)*$/", $nom) &&
            preg_match("#^[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$#", $email)

        ){
            $a= $m->modifyUser($id_personne,$nom,$prenom,$email);
            if($a){
                $data = [
                    "users" => $m->getPersonne(),
                    'message' => "La personne à bien été modifiée."
                    ];
                $this->render('staffs',$data);
            }
            else{
                
                $data = [
                    'message' => "La personne n'a pas pu être modifiée.. Veuillez réessayer",
                    'users' => $m->getPersonne()
                ];
                $this->render('staffs',$data);
            }
            
        }
        else{
            $data = [
                'message' => 'Erreur dans la modification',
                'users' => $m->getPersonne()
            ];
            $this->render('staffs',$data);
        }
        

    }
}
