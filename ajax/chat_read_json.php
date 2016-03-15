<?php
require_once('../common/connect.php');
require_once('../common/utils.php');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); 
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" ); 
header("Cache-Control: no-cache, must-revalidate" ); 
header("Pragma: no-cache" );
header("Content-Type: text/xml; charset=utf-8");
$sql="SELECT * FROM tchat WHERE quand>=";

if(!isset($_POST['start'])) $sql.="NOW()";
else $sql.=":start";

$sql.=" AND id_chat>:max ORDER BY quand";
$query=$connexion->prepare($sql);

$max=0;
if(isset($_POST['max'])) $max=$_POST['max'];
$query->bindValue('max', $max, PDO::PARAM_INT);
if(isset($_POST['start'])) $query->bindValue('start', $_POST['start'], PDO::PARAM_INT);

echo '
{"messages":
	{"message":[ ';
if($query->execute())
{
	$first=true;
	while( $msg = $query->fetch(PDO::FETCH_ASSOC) )
	{
		$pseudo=htmlspecialchars($msg['pseudo']);
		$message=htmlspecialchars($msg['message']);
		//$quand=get_date($msg['quand']).' à '.get_heure($msg['quand']);
		$quand=get_heure($msg['quand']);
		if(!$first) echo ',';
		echo '{"id": "'.$msg['id_chat'].'","user": "'.$pseudo.'", "text": "'.$message.'", "time": "'.$quand.'" }';
		$first=false;
	}
}
else  {echo 'ERREUR NEWS SQL';}
echo ' ]
	}
}';


