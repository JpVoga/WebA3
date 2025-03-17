<?php
    include_once("../libs/consts.php");
    include_once("../libs/database.php");


    function createAccountFailed(string $error)
    {
        $_SESSION["userAccountError"] = $error;
        header("Location:../createAccount/createAccount.php");
        die(); // header WON'T stop the page, must do this to exit
    }


    session_start();

    if (empty($_POST["userName"]) || empty($_POST["password"]))
    {
        createAccountFailed("Ambos o nome de usuário e a senha devem ser preenchidos.");
    }
    else
    {
        $userName = ((string) ($_POST["userName"]));
        $userNameFilteredForQuery = filterQuotesFromString($userName);

        $password = ((string) ($_POST["password"]));
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $passwordHashFilteredForQuery = filterQuotesFromString($passwordHash);
    }

    try
    {
        $database = createDatabaseConnection();
        $usersWithSameNameQuery = "SELECT * FROM users WHERE name = '{$userNameFilteredForQuery}'";
        $usersWithSameNameResult = $database->query($usersWithSameNameQuery);

        if ($usersWithSameNameResult->num_rows > 0)
        {
            createAccountFailed("Já existe um usuário com esse nome. Tente outro.");
        }
        else
        {
            $database->query(
                "INSERT INTO users(name, passwordHash) VALUE(
                    '{$userNameFilteredForQuery}',
                    '{$passwordHashFilteredForQuery}'
                )"
            );
            $_SESSION["user"] = $database->query($usersWithSameNameQuery)->fetch_assoc();

            header("Location:../home/home.php");
            die();
        }
    }
    catch (mysqli_sql_exception $e) {createAccountFailed("Falha ao conectar-se ao servidor.");}
?>