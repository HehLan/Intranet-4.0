<?php

echo            '<ul>
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="joueurs.php">Joueurs</a></li>
                    <li><a href="tournois.php?id=1">LOL</a></li>
                    <li><a href="tournois.php?id=2">CoD4</a></li>
                    <li><a href="tournois.php?id=3">TM</a></li>
					<li><a href="tournois.php?id=5">Hearthstone</a></li>';
/*if(isset($_SESSION['id_joueur']))
{
	echo '<li><a href="ModifProfil.php">Mon profil</a></li>';
}*/						
if(isset($_SESSION['level']))
{
	if($_SESSION['level']==1) echo '<li><a href="admin/index.php">Admin</a></li>';
}					
echo'           </ul>';
