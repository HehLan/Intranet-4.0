<?php
function get_jour_de_la_semaine($chaine)
{
 $y=substr($chaine,0,4);
 $m=substr($chaine,5,2);
 $d=substr($chaine,8,2);
 $timestamp = mktime (0, 0, 0, $m, $d, $y);
 $jsem=date("w",$timestamp);
 switch ($jsem)
 {
	case '0' : $jsem='dimanche'; break;
	case '1' : $jsem='lundi'; break;
	case '2' : $jsem='mardi'; break;
	case '3' : $jsem='mercredi'; break;
	case '4' : $jsem='jeudi'; break;
	case '5' : $jsem='vendredi'; break;
	case '6' : $jsem='samedi'; break;
 }
	return $jsem;
}

function get_date($chaine)
{
 $y=substr($chaine,0,4);
 $m=substr($chaine,5,2);
 $d=substr($chaine,8,2);
 $timestamp = mktime (0, 0, 0, $m, $d, $y);
 $jsem=date("w",$timestamp);
 switch ($jsem)
 {
	case '0' : $jsem='dimanche'; break;
	case '1' : $jsem='lundi'; break;
	case '2' : $jsem='mardi'; break;
	case '3' : $jsem='mercredi'; break;
	case '4' : $jsem='jeudi'; break;
	case '5' : $jsem='vendredi'; break;
	case '6' : $jsem='samedi'; break;
 }
 
 switch($m)
 {
	case '01' : $m='janvier'; break;
	case '02' : $m='février'; break;
	case '03' : $m='mars'; break;
	case '04' : $m='avril'; break;
	case '05' : $m='mai'; break;
	case '06' : $m='juin'; break;
	case '07' : $m='juillet'; break;
	case '08' : $m='aout'; break;
	case '09' : $m='septembre'; break;
	case '10' : $m='octobre'; break;
	case '11' : $m='novembre'; break;
	case '12' : $m='décembre'; break;
 }

 
 
 return $jsem.' '.$d.' '.$m.' '.$y;
}

function get_heure($chaine)
{
 $h=substr($chaine,11,2);
 $m=substr($chaine,14,2);

	return $h.'h'.$m;
 }
 
 function existe_manche_de_groupe($conn,$idt,$jpt)
 {
	if($jpt>1)
	{
		$sql="SELECT COUNT(*) as nbr
		FROM manches_equipes as me, matchs as m
		WHERE m.id_tournoi=:idt AND m.id_groupe IS NOT NULL AND me.id_match=m.id_match";
	}
	else
	{
		$sql="SELECT COUNT(*) as nbr
		FROM manches_joueurs as mj, matchs as m
		WHERE m.id_tournoi=:idt AND m.id_groupe IS NOT NULL AND mj.id_match=m.id_match";
	}
	$query=$conn->prepare($sql);
	$query->bindValue('idt',$idt,PDO::PARAM_INT);
	if($query->execute())
	{
		$nbr=$query->fetch(PDO::FETCH_ASSOC);
	}
	else {echo 'ERREUR EXISTE MANCHE GROUPE TEAM SQL'; exit;}
	$nbr=$nbr['nbr'];
	if($nbr==0) return false;
	else return true;		
 }
 
  function existe_manche_de_finale($conn,$idt,$jpt,$lb)
 {
	if($jpt>1)
	{
		$sql="SELECT COUNT(*) as nbr
		FROM manches_equipes as me, matchs as m
		WHERE m.id_tournoi=:idt AND m.id_groupe IS NULL AND me.id_match=m.id_match AND m.looser_bracket=:lb";
	}
	else
	{
		$sql="SELECT COUNT(*) as nbr
		FROM manches_joueurs as mj, matchs as m
		WHERE m.id_tournoi=:idt AND m.id_groupe IS NULL AND mj.id_match=m.id_match AND m.looser_bracket=:lb";
	}
	$query=$conn->prepare($sql);
	$query->bindValue('idt',$idt,PDO::PARAM_INT);
	$query->bindValue('lb',$lb,PDO::PARAM_INT);
	if($query->execute())
	{
		$nbr=$query->fetch(PDO::FETCH_ASSOC);
	}
	else {echo 'ERREUR EXISTE MANCHE GROUPE TEAM SQL'; exit;}
	$nbr=$nbr['nbr'];
	if($nbr==0) return false;
	else return true;		
 }
 
function inscrits_en_finale($conn,$idt,$jpt,$lb)
 {
	if($jpt>1)
	{
		$sql="SELECT COUNT(*) as nbr
		FROM matchs_equipes as me, matchs as m
		WHERE m.id_tournoi=:idt AND m.id_groupe IS NULL AND me.id_match=m.id_match AND m.looser_bracket=:lb";
	}
	else
	{
		$sql="SELECT COUNT(*) as nbr
		FROM matchs_joueurs as mj, matchs as m
		WHERE m.id_tournoi=:idt AND m.id_groupe IS NULL AND mj.id_match=m.id_match AND m.looser_bracket=:lb";
	}
	$query=$conn->prepare($sql);
	$query->bindValue('idt',$idt,PDO::PARAM_INT);
	$query->bindValue('lb',$lb,PDO::PARAM_INT);
	if($query->execute())
	{
		$nbr=$query->fetch(PDO::FETCH_ASSOC);
	}
	else {echo 'ERREUR EXISTE MATCH en FINALE TEAM SQL'; exit;}
	$nbr=$nbr['nbr'];
	if($nbr==0) return false;
	else return true;		
 }
 
 function creer_match_equipe($conn,$idt,$idg,$nbrm,$tpm,$heure,$ide1,$ide2)
 {
	$sql="INSERT INTO matchs (id_tournoi,nbr_manche,teamParMatch,heure,id_groupe)
	VALUES (:idt,:nbrm,:tpm,:heure,:idg)";
	$query=$conn->prepare($sql);
	$query->bindValue('idt', $idt, PDO::PARAM_INT);	
	$query->bindValue('idg', $idg, PDO::PARAM_INT);	
	$query->bindValue('nbrm', $nbrm, PDO::PARAM_INT);	
	$query->bindValue('tpm', $tpm, PDO::PARAM_INT);	
	$query->bindValue('heure', $heure, PDO::PARAM_INT);	
	if(!$query->execute()) {echo 'ERREUR INSERT MATCHS (JOUEURS)'; exit;}
	
	$id_match=$conn->lastInsertId();

	$sql="INSERT INTO matchs_equipes (id_match,id_equipe)
		VALUES (:idm,:ide1),(:idm,:ide2); ";
	$query=$conn->prepare($sql);
	$query->bindValue('idm', $id_match, PDO::PARAM_INT);				
	$query->bindValue('ide1', $ide1, PDO::PARAM_INT);				
	$query->bindValue('ide2', $ide2, PDO::PARAM_INT);				
	if(!$query->execute()) {echo 'ERREUR INSERT MATCHS_JOUEURS'; exit;}
 }
 
 function ajouter_heures($h1,$nbr)
 {
	$h=substr($nbr,0,2);
	$m=substr($nbr,3,2);
	$date = new DateTime($h1);
	$date->add(new DateInterval('PT'.$h.'H'.$m.'M'));
	return $date->format('Y-m-d H:i:s');
 }
 
 function get_variable($conn,$nom)
 {
 	$sql="SELECT valeur FROM variables WHERE nom=:nom";
	$query=$conn->prepare($sql);
	$query->bindValue('nom', $nom, PDO::PARAM_STR);	
	if(!$query->execute()) {echo 'ERREUR SELECT VALEUR'; exit;}
	$nom=$query->fetch(PDO::FETCH_ASSOC);
	return $nom['valeur'];
 }
 
