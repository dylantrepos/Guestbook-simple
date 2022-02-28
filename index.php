<?php
require 'vendor/autoload.php';

use App\Guestbook\GuestBook;
use App\Guestbook\Message;


    $erreurs = [];
    $erreur_pseudo = "";
    $erreur_message = "";
    
    $livre_dor = new GuestBook(__DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "messages.txt");


    if(isset($_POST["username"],  $_POST["message"])){
        
        $message = new Message(htmlentities($_POST["username"]), htmlentities($_POST["message"]));
        if($message->isValid()){
            $livre_dor->addMessage($message);
        }
        else{
            $erreurs = $message->getErrors();
            $erreur_pseudo = !empty($erreurs["username"]) ? " is-invalid" : "";
            $erreur_message = !empty($erreurs["message"]) ? " is-invalid" : "";
        }
    }



    $title = "Guestbook";
    require "./elements/header.php";
?>

    <div class="container" style="margin-top: 10px">
        <h1>Guestbook</h1>
        
        <?php if(!empty($erreurs)): ?>
            <div class="alert alert-danger">
                The form is not correct
            </div>
        <?php elseif(!empty($_POST["username"]) && !empty($_POST["message"]) && empty($erreurs)): ?>
            <div class="alert alert-success">
                Thank you ! Your message has been saved with success.
            </div>
        <?php endif ?>


        <form action="" method="post" class="needs-validation" novalidate>
            <div class="form-group">
                <div class="form-list">
                    <input type="text" name="username" placeholder="Your username..." class="form-control <?= $erreur_pseudo ?> " value="<?= htmlentities($_POST["username"] ?? '') ?>" required>

                    <?php if((isset($erreurs)) && (isset($erreurs["username"]))): ?>
                        <?= $erreurs["username"]; ?>
                    <?php endif ?>

                </div>
                <div class="form-list" style="margin-top: 10px">
                    <textarea name="message" placeholder="Your message..." class="form-control <?= $erreur_message ?>" required><?= htmlentities($_POST["message"] ?? '') ?></textarea>
                    <?php if((isset($erreurs)) && (isset($erreurs["message"]))): ?>
                        <?= $erreurs["message"]; ?>
                    <?php endif ?>
                </div>
            </div>
            <input type="submit" value="Add my message" class="btn btn-primary">
        </form>
        <br>
        <hr>
        <h1>Messages from the guestbook : </h1>
        <?php if($livre_dor !== null && !empty($livre_dor->getMessages())):?>
            <?php foreach($livre_dor->getMessages() as $le_message): ?>
                <?php $le_message_json = Message::fromJSON($le_message); ?>
                <?= $le_message_json->toHMTL() ?>
            <?php endforeach ?>
        <?php endif ?>

    </div>
<?php 
    require "./elements/footer.php";
?>