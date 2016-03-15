<?php
session_start();
require_once('modules/connect.php');
require_once('../common/utils.php');
$con=false;

if(isset($_SESSION['id_joueur']))
{
	if(($_SESSION['id_joueur']!=0) && $_SESSION['level']<=3) $con=true;
}
if(!$con)
{
 header('Location: ../index.php');
} 
?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" charset="utf-8">
	<title>HEHLan</title>
	<META NAME="robots" CONTENT="none">
	
	<link rel="icon" href="../img/logoheh.ico" >
    <link rel="stylesheet" href="../css/style.css" type="text/css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/getXhr.js"></script>
    <script type="text/javascript">

	
	</script>
</head>

<body style="background-color: #000;">

 	<div id="header">
		<div id="banner">
		    <a href="index.php">
		    <img src="img/logoheh.png" alt="HEHLan" width="250px">
		    </a>
		</div>
		<div id="login">
			<?php
				if($con)
				{
					echo 'Bienvenu à toi '.$_SESSION['login'].', <a href="../common/deco.php">se déconnecter</a><br>';
					
				}
			?>
		</div>	     
 	</div>
 	
    <div id="navigation">
	<?php
		require_once('modules/menuTop.php');
    ?>        
    </div>
	<div id="container">
		<div id="contenu">
		<?php
			
		echo '<h1>Ajouter une news</h1>
		
			<form method="POST" action="news_save.php">
			<input type="hidden" name="id_news" value="0">
			<table>
				<tr>
					<td>
						<strong>Titre</strong>
					</td>
					<td>
						<input type="text" name="titre" size="50" value="">
						<input type="submit" value="modifier">
					</td>
				</tr>
				<tr>
					<td>
						<strong>Texte</strong>
					</td>
					<td>
						<textarea name="texte" cols="60" rows="8"></textarea>
					</td>
				</tr>	
<tr>
<td colspan=2>
<input type=submit value=enregistrer>
</td>
</tr>				
			</table>
			</form>
			<br>
			<br>	
			<h1>Modifier les news</h1>';	
		
		$sql="SELECT * FROM news ORDER by invisible, quand DESC";
		$query=$connexion->prepare($sql);
		$query->execute();
		$firstinvi=true;
		while($news=$query->fetch(PDO::FETCH_ASSOC))
		{
			if($news['invisible']==1 && $firstinvi)
			{
				echo '<h1>News effacées</h1>';
				$firstinvi=false;
			}
			echo '<form method="POST" action="news_save.php">
			<input type="hidden" name="id_news" value="'.$news['id_news'].'">
			<table>
				<tr>
					<td>
						<strong>Titre</strong>
					</td>
					<td>
						<input type="text" name="titre" size="50" value="'.htmlspecialchars($news['titre']).'">
						<input type="submit" value="modifier">';
			if($news['invisible']==0) echo '<a href="news_effacer.php?invisible=1&id_news='.$news['id_news'].'">Masquer</a>';
			else echo '<a href="news_effacer.php?invisible=0&id_news='.$news['id_news'].'">Dé-Masquer</a>';
					echo '</td>
				</tr>
				<tr>
					<td>
						<strong>Texte</strong>
					</td>
					<td>
						<textarea name="texte" cols="60" rows="8">'.$news['texte'].'</textarea>
					</td>
				</tr>				
				<tr>
					<td colspan="2">
						Dernière maj : '.$news['quand'].'		
					</td>
				</tr>
			</table>
			</form>
			<br>
			<br>';
		}
		?>
		</div>
	</div>
    <div id="footer">
        <div id="about"><p>HEHLan All Rights Reserved 'Copyright' 2014</p></div>
        <div id="nothinghere"><img src="img/logo3.png" alt="CEHECOFH"></div>
        <div id="social"><a href="http://www.heh.be" target="_blank"><img src="img/logo4.png" alt="HeH" border="0"></a></div>
    </div>
	
</body>
</html>