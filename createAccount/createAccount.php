<?php
    include_once("../libs/consts.php");

    session_start();

    $userAccountError = "";
    if (!empty($_SESSION["userAccountError"]))
    {
        $userAccountError = $_SESSION["userAccountError"];
        unset($_SESSION["userAccountError"]);
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Criar conta na A3</title>

        <link rel="stylesheet" href="../style/style.css?<?php echo getCacheTime()?>">
    </head>

    <body>
        <?php include("../header/header.php"); ?>

        <form 
            id="userInfoForm"
            class="postPageHeader"
            action="../createAccountRedirect/createAccountRedirect.php"
            method="post"
        >
            <?php include("../userInfoInputs/userInfoInputs.php"); ?>

            <input id="userInfoSubmitInput" class="yellowInteractable" type="submit" value="Criar conta"><br>

            <label id="userAccountErrorDisplay" class="errorDisplay"><?php echo $userAccountError; ?></label>
        </form>

        <?php include("../footer/footer.php"); ?>
    </body>
</html>