<?php
session_start();
require_once('common/connect.php');
require_once('common/utils.php');
$con=false;
$chat=false;
if(isset($_SESSION['id_joueur']))
{
	if(($_SESSION['id_joueur']!=0)) $con=true;
}
if(!$con)
{
	if(isset($_POST['login']) && isset($_POST['pwd']))
	{
		$sql="SELECT id_joueur,level FROM joueurs WHERE pseudo=:login and password=:pwd";
		$query=$connexion->prepare($sql);
		$query->bindValue('login', $_POST['login'], PDO::PARAM_STR);
		$query->bindValue('pwd', sha1($_POST['pwd']), PDO::PARAM_STR);
		if($query->execute())
		{
			$rst = $query->fetch(PDO::FETCH_ASSOC);
			if(!is_null($rst['id_joueur']))
			{
				$_SESSION['id_joueur']=$rst['id_joueur'];
				$_SESSION['login']=$_POST['login'];
				$_SESSION['level']=$rst['level'];
				$_SESSION['password']=$_POST['pwd'];
				$con=true;
			}
		}
		else  {echo 'ERREUR LOGIN SQL';}
	}
	
}

?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" charset="utf-8">
	<title>HEHLan</title>
	<META NAME="robots" CONTENT="none">
	
	<link rel="icon" href="img/logoheh.ico" >
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/getXhr.js"></script>

	<script type="text/javascript">
	function news_toggle(idn)
	{
		var nom='#contenu_news_'+idn;
		$(nom).toggle();

	}
	</script>
	
	<?php	
	if($con)
	{ 
		$duree_chat='2000';
		$duree_chat_users='20000';
		
		//----- test si chat est activé
		$sql="SELECT valeur FROM variables WHERE nom='chat_on'";
		$query=$connexion->prepare($sql);
		if($query->execute())
		{
			$rst = $query->fetch(PDO::FETCH_ASSOC);
			if($rst['valeur']==1) $chat=true;
		}
		else  {echo 'ERREUR SQL duree_chat';}
		//----------------------
		
		//----- Chat actif 
		if($chat) 
		{
			//------: recup des timings ajax
			$sql="SELECT valeur FROM variables WHERE nom='duree_chat'";
			$query=$connexion->prepare($sql);
			if($query->execute())
			{
				$rst = $query->fetch(PDO::FETCH_ASSOC);
				$duree_chat=$rst['valeur'];
			}
			else  {echo 'ERREUR SQL duree_chat';}	
			
			$sql="SELECT valeur FROM variables WHERE nom='duree_chat_users'";
			$query=$connexion->prepare($sql);
			if($query->execute())
			{
				$rst = $query->fetch(PDO::FETCH_ASSOC);
				$duree_chat_users=$rst['valeur'];
			}
			else  {echo 'ERREUR SQL duree_chat_users';}			
			//------------------
			
			
			echo '<script type="text/javascript">
		

		
			function afficher(max)
				{
					var xhr = getXhr();
			
					xhr.onreadystatechange = function()
												{
												if(xhr.readyState == 4 && xhr.status == 200)
													{
														var chat_div=document.getElementById("bloc_chat_texte");
														
														var response = eval("(" + xhr.responseText + ")");
														for(i=0;i < response.messages.message.length; i++)  {
															
															chat_div.innerHTML += "<span class=\"chatqui\">("+response.messages.message[i].time+") "+response.messages.message[i].user+" :</span> "+response.messages.message[i].text+"<br>";
															
															chat_div.scrollTop = chat_div.scrollHeight;
															if (response.messages.message[i].id>max) max=response.messages.message[i].id;
														}
														setTimeout("afficher("+max+")",'.$duree_chat.');

													}
												}
					xhr.open("POST","ajax/chat_read_json.php",true);
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhr.send("max="+max+"&start='.date("Y-m-d H:i:s").'");
				}
		
			function users()
				{
					var xhr = getXhr();

					
					xhr.onreadystatechange = function()
												{
												if(xhr.readyState == 4 && xhr.status == 200)
													{
													
													document.getElementById("bloc_chat_users").innerHTML=xhr.responseText;
													setTimeout("users()",'.$duree_chat_users.');

													}
												}
					xhr.open("POST","ajax/chat_users.php",true);
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					xhr.send("id='.$_SESSION['id_joueur'].'&pseudo='.$_SESSION['login'].'");
				}
			function ecrire()
				{
					var xhr = getXhr();
					var msg=document.getElementById("bloc_chat_message").value;
					document.getElementById("bloc_chat_message").value="";
					if(msg.length>0)
					{
						xhr.onreadystatechange = function()
													{
													if(xhr.readyState == 4 && xhr.status == 200)
														{
														
														document.getElementById("bloc_chat_message").innerHTML+=msg;
														

														}
													}
						xhr.open("POST","ajax/chat_insert.php",true);
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xhr.send("id='.$_SESSION['id_joueur'].'&pseudo='.$_SESSION['login'].'&msg="+msg);
					}
				}				
			 
			</script>'; 
		}
		//-------------
	}
	?>

    
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
					echo 'Bienvenue à toi '.$_SESSION['login'].', <a href="common/deco.php">se déconnecter</a><br>';
					$sql="SELECT DISTINCT m.id_match, m.heure, t.nomTournoi 
					FROM (matchs as m, tournoi as t,matchs_joueurs as mj, matchs_equipes as me, equipes_joueur as ej)

					WHERE
						t.id_tournoi=m.id_tournoi AND 
						(
							(mj.id_joueur=:idj AND m.id_match=mj.id_match)
							OR
							(ej.id_joueur=:idj AND me.id_equipe=ej.id_equipes AND m.id_match=me.id_match)
						)
						AND m.heure>NOW()
					ORDER BY m.heure
					LIMIT 0,3";
					$query=$connexion->prepare($sql);
					$query->bindValue('idj', $_SESSION['id_joueur'], PDO::PARAM_INT);
					if($query->execute())
					{
						$next_matches= $query->fetchAll(PDO::FETCH_ASSOC);
					}
					else {echo 'ERREUR SQL NEXT MATCHES'; exit;}	
					$first=true;
					foreach($next_matches as $next_match)
					{
						if($first) echo '<strong>Prochains matchs</strong><br>';
						echo get_jour_de_la_semaine($next_match['heure']).' '.get_heure($next_match['heure']).' '.$next_match['nomTournoi'].'<br>';
						$first=false;
					}
				}
			?>
		</div>	     
 	</div>
 	
    <div id="navigation">
	<?php
		require_once('common/menuTop.php');
    ?>        
    </div>
	<div id="container">
		<!--<div id="contenu">

		</div>-->
		<div id="bloc_news">
		<?php
		
			$sql="SELECT * FROM news WHERE invisible=0 ORDER BY quand DESC";
			$query=$connexion->prepare($sql);
	
			if($query->execute())
			{

				while( $news = $query->fetch(PDO::FETCH_ASSOC) )
				{
					$titre=htmlspecialchars($news['titre']);
					$texte=nl2br($news['texte']);
					$quand=get_jour_de_la_semaine($news['quand']).' à '.get_heure($news['quand']);
					$id_news=$news['id_news'];
					echo '<div class="une_news" id="bloc_news_'.$id_news.'">
							<div class="titre_news" id="titre_news_'.$id_news.'" onclick="news_toggle('.$id_news.');">
								'.$titre.'
									<div class="date_news" id="footer_news_'.$id_news.'">
								'.$quand.'
							</div>
							</div>
							<div class="contenu_news" id="contenu_news_'.$id_news.'">
							'.$texte.'
							</div>
							
						</div>';
				}
			}
			else  {echo 'ERREUR NEWS SQL';}
			
			
			
			
		
		?>
				
		</div>		
		<div id="bloc_chat">
		<?php
			if($con)
			{
				echo '
					<div id="bloc_chat_titre">
						HEHLan Chat
					</div>';
				if($chat)
				{
					echo '
						<div id="bloc_chat_texte">			
						</div>
						<div id="bloc_chat_users">
							<strong>Connectés :</strong><br>				
						</div>
						<div id="bloc_chat_send">
							<input type="text" name="message" id="bloc_chat_message" /> <input type="button" value="Envoyer" id="bloc_chat_bouton" onclick="ecrire();"/>
						</div>';
				}
				else				
				{
					echo '<div id="bloc_chat_texte">			
							<strong>Sorry les gars ... le chat est désactivé :o/</strong>
						</div>';
				}
			}
			else
			{
			echo '
				<div id="bloc_chat_titre">
					Connectez vous pour accèder au Chat :
				</div>	
				<div id="bloc_connexion">
					<form method="POST">
					<table style="border:0px">
						<tr>
							<td><strong>Login</strong></td>
							<td><input type="text" name="login" /></td>
						</tr>
						<tr>
							<td><strong>Password</strong></td>
							<td><input type="password" name="pwd" /></td>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" value="Connexion" /></td>						
						</tr>					
					</table>
					</form>
				</div>';
			}
			?>
		</div>
		
	</div>
    <div id="footer">
        <div id="about"><p>HEHLan All Rights Reserved 'Copyright' 2014</p></div>
        <div id="nothinghere"><img src="img/logo3.png" alt="CEHECOFH"></div>
        <div id="social"><a href="http://www.heh.be" target="_blank"><img src="img/logo4.png" alt="HeH" border="0"></a></div>
    </div>
	<?php
	if ($chat)
	{
		echo '<script type="text/javascript">
				
				$("#bloc_chat_message").keyup(function(event){
					if(event.keyCode == 13){
						$("#bloc_chat_bouton").click();
					}
				});
			
			afficher(0);
			users();
	
	
		</script>';
	}
	?>
</body>
</html>