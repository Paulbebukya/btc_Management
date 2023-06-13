<link rel="stylesheet"
    href="<?=EE?>app/plugins/css/template/fonts/material-icon/css/material-design-iconic-font.min.css">
<!-- Main css -->
<link rel="stylesheet" href="<?=EE?>app/plugins/css/template/css/style.css?t=<?= time()?>">

<!-- Sign up form -->
<section class="signup">
    <div class="container">
        <div class="signup-content">
            <div class="signup-form">
                <h2 class="form-title">Ajouter votre compte d'utilisateur</h2>
                <form method="POST" class="register-form " id="register-form"
                    action='<?=EE?>Inscriptions/creatUserCompte'>
                    <div class="form-group">
                        <label for="name"><i class="zmdi zmdi-account material-icons-name"></i> Votre nom
                            d'utilisateur</label>
                        <input type="text" name="username" id="username" placeholder=" " value='<?=isset($_POST['username'])?$_POST['username']:null ?>' />
                    </div>
                    <div class="form-group">
                        <label for="pass" onclick="eye()"><i id="icone"></i> Votre mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="" value='<?=isset($_POST['password'])?$_POST['password']:null ?>' />
                    </div>
                    <div class="form-group">
                        <label for="pass" onclick="eye()"><i class="" id="icone2"></i> Confirmer votre mot de
                            passe</label>
                        <input type="password" name="password2" id="password2" placeholder="" value='<?=isset($_POST['password2'])?$_POST['password2']:null ?>' />
                    </div>
                    <div class="form-group form-button">
                        <input type="submit" name="btnCompte" id="signup" class="form-submit" value="Etape 2/3" />
                    </div>
                    <?php
                    if(isset($this->erreur)){
                    ?>
                    <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading"> 😔 oups!</h4>
                        <p class="mb-0"><?=$this->erreur ?></p>
                    </div>
                    
                    <?php }?>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
e = true;
$('#icone').addClass("fa fa-eye-slash");
$('#icone2').addClass("fa fa-eye-slash");

function eye() {
    if (e) {
        document.getElementById("password").setAttribute("type", "text");
        document.getElementById("password2").setAttribute("type", "text");
        $('#icone').addClass("fa fa-eye");
        $('#icone2').addClass("fa fa-eye");
        e = false;
    } else {
        document.getElementById("password").setAttribute("type", "password");
        document.getElementById("password2").setAttribute("type", "password");
        $('#icone').removeClass("fa fa-eye");
        $('#icone2').removeClass("fa fa-eye");
        $('#icone').addClass("fa fa-eye-slash");
        $('#icone2').addClass("fa fa-eye-slash");
        e = true;
    }
}
</script>