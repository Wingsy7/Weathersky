<?php /* Requires and includes */
require "./include/header.inc.php";
?>

<?php echo en_tete("Plan du site", false);
$style_css = getStyle(); ?>

<?php echo TD_actuel_selectionné(0); ?>
    <main>
        <div class="main-part">
            <h1 class="h1-custom">Plan du site</h1>
            <section>
                <ul>
                    <li>
                    
                            <h3>Accueil</h3>
                            <ul>
                                <li><a href="index.php">Page d'accueil</a></li>
                            </ul>
                    
                    </li>                    <li>
                        <section>
                            <h3>Projet</h3>
                            <ul>
                                <li><a href="./projet/tech.php">Page Tech du projet</a></li>
                            </ul>
                        </section>
                    </li>
                    <li>
                        <section>
                            <a href="td5.php"><h3>TD5</h3></a>
                            <ul>
                                <li><a href="td5.php#exercice-1">Exercice 1</a></li>
                                <li><a href="td5.php#exercice-2">Exercice 2</a></li>
                                <li><a href="td5.php#exercice-3">Exercice 3</a></li>
                                <li><a href="td5.php#exercice-4">Exercice 4</a></li>
                                <li><a href="td5.php#exercice-5">Exercice 5</a></li>
                            </ul>
                        </section>
                    </li>
                    <li>
                        <section>
                            <a href="td6.php"><h3>TD6</h3></a>
                            <ul>
                                <li><a href="td6.php#exercice-1">Exercice 1</a></li>
                                <li><a href="td6.php#exercice-2">Exercice 2</a></li>
                            </ul>
                        </section>
                    </li>
                    <li>
                        <section>
                            <a href="td7.php"><h3>TD7</h3></a>
                            <ul>
                                <li><a href="td7.php#exercice-1">Exercice 1</a></li>
                                <li><a href="td7.php#exercice-2">Exercice 2</a></li>
                            </ul>
                        </section>
                    </li>
                    <li>
                        <section>
                            <a href="td8.php"><h3>TD8</h3></a>
                            <ul>
                                <li><a href="td8.php#exercice-1">Exercice 1</a></li>
                                <li><a href="td8.php#exercice-2">Exercice 2</a></li>
                            </ul>
                        </section>
                    </li>
                    <li>
                        <section>
                            <a href="td9.php"><h3>TD9</h3></a>
                            <ul>
                                <li><a href="td9.php#exercice-1">Exercice 1</a></li>
                                <li><a href="td9.php#exercice-2">Exercice 2</a></li>
                                <li><a href="td9.php#exercice-3">Exercice 3</a></li>
                                <li><a href="td9.php#exercice-4">Exercice 4</a></li>
                                <li><a href="td9.php#exercice-5">Exercice 5</a></li>
                                <li><a href="td9.php#exercice-6">Exercice 6</a></li>
                            </ul>
                        </section>
                    </li>
                    <li>
                        <section>
                            <a href="td10.php"><h3>TD10</h3></a>
                            <ul>
                                <li><a href="td10.php#exercice-1">Exercice 1</a></li>
                                <li><a href="td10.php#exercice-2">Exercice 2</a></li>
                            </ul>
                        </section>
                    </li>
                </ul>
            </section>
        </div>
    </main>
<?php 
require "./include/footer.inc.php";
 ?>