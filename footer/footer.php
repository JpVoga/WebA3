<footer id="pageFooter">
    <div id="pageFooterText">
        &copy;
        <?php
            $creationYear = "2023";
            $currentYear = date("Y");

            echo $creationYear;
            if ($creationYear != $currentYear) echo " - " . $currentYear;
        ?>
        João Pedro Voga de Oliveira, Cristiano Amorim, Thiago Pralom Cavalcante, Lucas Ribeiro Figueiredo
    </div>
</footer>