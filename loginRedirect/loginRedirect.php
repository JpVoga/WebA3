<?php
    include_once("../libs/consts.php");
    include_once("../libs/database.php");


    function loginFailed(string $error)
    {
        $_SESSION["userAccountError"] = $error;
        header("Location:../login/login.php");
        die();
    }


    session_start();

    if (empty($_POST["userName"]) || empty($_POST["password"]))
    {
        loginFailed("Ambos o nome de usuário e a senha devem ser preenchidos.");
    }
    else
    {
        $userName = ((string) ($_POST["userName"]));
        $userNameFilteredForQuery = filterQuotesFromString($userName);

        $password = ((string) ($_POST["password"]));
    }

    try
    {
        $database = createDatabaseConnection();
        $usersWithSameNameResult = $database->query(
            "SELECT * FROM users WHERE name = '{$userNameFilteredForQuery}'"
        );

        if ($usersWithSameNameResult->num_rows != 1) loginFailed("Nome de usuário incorreto.");
        else
        {
            $user = $usersWithSameNameResult->fetch_assoc();

            if (password_verify($password, $user["passwordHash"]))
            {
                $_SESSION["user"] = $user;
                header("Location:../home/home.php");
                die();
            }
            else loginFailed("Senha incorreta.");
        }
    }
    catch (mysqli_sql_exception $e) {loginFailed("Falha ao conectar-se ao servidor.");}
?>