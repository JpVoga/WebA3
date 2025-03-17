<header id="pageHeader">
    <div id="titleArea" class="pageHeaderChild">
        <a id="homePageLink" class="noDecorationLink" href="../home/home.php" target="_self">A3 Ecommerce</a>
    </div>

    <form id="searchForm" class="pageHeaderChild" action="../search/search.php" method="get">
        <input id="searchInput" class="greyInteractable" name="search" type="text" placeholder="Pesquisar...">
    </form>

    <div id="loginArea" class="pageHeaderChild">
        <?php
            if (empty($_SESSION["user"]))
            {
                echo
                    '<button
                        id="createAccountButton"
                        class="loginAreaButton greyInteractable"
                    >
                        Cadastrar-se
                    </button>

                    <button
                        id="loginButton"
                        class="loginAreaButton greyInteractable"
                    >
                        Login
                    </button>';
            }
            else
            {
                $filteredUserName = htmlentities($_SESSION["user"]["name"]);
                echo "<label id=\"userNameDisplay\">{$filteredUserName}</label>";
                echo '<button id="logoutButton" class="loginAreaButton greyInteractable">Sair</button>';
            }
        ?>
        
    </div>
</header>

<script src="../header/header.js?<?php echo getCacheTime(); ?>"></script>