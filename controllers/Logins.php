<?php
class Logins extends App{
    protected $erreur=null;
    protected $onLine;
    protected $idUser;

    public function __construct(){
        $this->loadModel("Login");
      
        $this->idUser=!empty($_SESSION['idUser'])?$_SESSION['idUser']:NULL;

        if($this->idUser!==NULL){
            $this->onLine=$this->Login->getUserOnline($this->idUser);
        }
        
    }
    
    public function index(){
        $this->render("login");
    }

    public function seConnecter(){
        $remember=$this->rememberMe();

        if($remember){
            
            $this->username=isset($_COOKIE['userName'])?$_COOKIE['userName']:NULL;
            $this->password=isset($_COOKIE['password'])?$_COOKIE['password']:NULL;        

            $this->login($this->username,$this->password);
        }
        else{

            if(isset($_POST['btnConnection'])){
                if(!empty($_POST['userName']) && !empty($_POST['password'])){

                    $this->username=$_POST['userName'];
                    $this->password=sha1($_POST['password']);

                    if( isset($_POST['rememberMe'])){
                        setcookie('userName', $this->username , time() + 30*24*3600, null, null, false,true);
                        setcookie('password', $this->password, time() + 30*24*3600, null, null, false,true);
                    } 
                    
                    $this->login($this->username,$this->password);

                }else{
                   $this->erreur="Remplissez tous les champs svp !!!";
                }
            }
        }
        
        $this->index();
        
    }
    public function login($username, $password){
        $this->username=$username;
        $this->password=$password;

        $compte=$this->Login->getCompte($this->username,$this->password);
        if(isset($compte['@idUser'])){
            if($compte['@etatCompte']=='active'){
              
                $_SESSION['idUser']=$compte['@idUser'];
                $_SESSION['roleUser']=$compte['@roleUser'];
                $_SESSION['idIns']=$compte['@idIns'];
                $_SESSION['userName']=$compte['@userName'];
                $_SESSION['idComp']=$compte['@idComp'];
                $_SESSION['idForm']=$compte['@idForm'];
                $_SESSION['idProm']=$compte['@idProm'];
                $_SESSION['photoUser']=$compte['@photoUser'];
                
                header('location:../Actualites');
                exit;
            }
            elseif($compte['@etatCompte']=='desable'){
                $this->erreur="Le compte n'est pas activé";
                $this->cleanCookie();
                $idComp=$_SESSION['idComp']=$compte['@idComp'];
                $this->render('passwordConf',compact('idComp'));
            }
            elseif($compte['@etatCompte']=='bloque'){
                $this->cleanCookie();
                $this->erreur="Veuillez contacter l'administrateur, votre compte a connu un problème";
                $this->render("login");
            }
            else{
                $this->cleanCookie();
                $this->erreur="Probleme inconnu, Réessayez";
            }
        }
        else{
            $this->cleanCookie();
            $this->erreur="Nom d'utilisateur ou mot de passe incorrect !!! ";

        }
                $this->render("login");
    }

    public function forgetPassWord(){
        if(isset($_POST['checkCompte'])){
            if(!empty($_POST['gmail'])){
                $this->gmail=$_POST['gmail'];
                $idCompt=$this->Login->getIdCompt($this->gmail);
                if(isset($idCompt['@idCompt'])){
                    $_SESSION['idCompt']=$idCompt['@idCompt'];
                    $codeConfirme=!empty($idCompt['@codeConfirme'])? $idCompt['@codeConfirme']:NULL;
                    $lien="Logins/setPassword/". $this->vicha($idCompt['@idCompt']);
                    $this->sendMail($this->gmail,$lien);
                    $this->render('passwordConf');
                }
                else{
                    $this->erreur="Adresse gmail incorrecte !!!";
                    $this->render('forgetPassword');
                }
            }         
            else{
                $this->erreur="Completez votre Adresse G-mail";
                $this->render('forgetPassword');
            }
          
        }
        else{
            $this->render('forgetPassword',['']);

        }
    }
    public function setPassword($idComp=null){
        if(isset($_POST)){
            if(!empty($_POST['password']) && !empty($_POST['password2'])){
                if(strlen($_POST['password'])>6){
                    if($_POST['password']===$_POST['password2']){
                        $this->idCompt=!empty($idComp)? $this->vichuwa($idComp) : NULL;
                        $this->password=sha1($_POST['password']);
                        $compte=$this->Login->setPassword($this->idCompt,$this->password);
                        $this->seConnecter();
                    }
                    else{
                        $this->erreur="Les mots de passe ne correspondent pas";
                        // $this->render('newPassword',['']);
                    }
                }
                else{
                    $this->erreur="le mot de passe doit comporter aumois sept caracteres";
                    // $this->render('newPassword',['']);
                }
            }   
            else{   
                $this->erreur="Complètez tous les champs";
            }
        }
        
        $this->render('newPassword',['']);

    }
    public function confirmePass(){
        if(isset($_POST['terminer'])){
            if(!empty($_POST['confirmeComp'])){ 

                $this->password=$_SESSION['password'] ; 
                $this->username=$_SESSION['username'];
                $compte=$this->Login->getCompte($this->username,$this->password);
                $this->confirmeComp=$_POST['confirmeComp'];
                $this->idCompt=$compte['@idComp'];
                try {
                    //code...
                    $this->loadModel("Inscription");
                    $etatComp=$this->Inscription->compteActive($this->idCompt,$this->confirmeComp);
                    $this->index();

                } catch (\Throwable $th) {
                    //throw $th;
                }
                
            }
            
        }
    }

    public function rememberMe(){

        if(isset($_COOKIE['userName']) && $_COOKIE['userName']!=NULL){
            $idCompt=true ;
        }
        else{
            $idCompt=false ;
        }
        return $idCompt;
    }

    public function seDeconnecter(){
        $_SESSION=array();
        session_destroy();

        $this->cleanCookie();
        header('location:'.EE.'Acceuils');

    }



}