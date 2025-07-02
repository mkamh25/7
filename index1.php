<?php
header("Content-type: text/html; charset=utf-8"); // Changed to HTML content type
header("Cache-Control: no-store, no-cache, must-revalidate");
// print "<?xml version=\"1.0\" encoding=\"utf-8\"?>"; // Remove or comment out
echo "<!DOCTYPE html>"; // Changed to HTML5 DOCTYPE
include_once("core.php");
include_once("ip.php");
$action = addslashes(htmlspecialchars($_GET['action'])); if (!$action) { $action = ""; }

if ($action !== "arena") echo"<wml><card id=\"heroes\" title=\"$title_total\"><p align=\"center\">";
if ($HTTP_USER_AGENT == "") {

}
include("mukaka.php");
$id = addslashes(htmlspecialchars($_GET['id'])); if (!$id) { $id = ""; }
$topic = mysql_fetch_array(mysql_query("SELECT max,topic FROM spec LIMIT 1"));
if(!$topic[max]){
mysql_query("insert into spec (max,topic) values ('0','')");}

include_once("check.php");
if(($user[username] == "chaotic") or ($user[blokas]=="1")){
echo" ";}
else{
if ($action !== "arena") {
if($user[ship]>time()){
echo"<small>Your ship is under attack!</small><br/><small><a href=\"index.php?id=$id&amp;action=laiv&amp;la=vand\">Strike back!</a></small><br/>";}}

$par=mysql_fetch_array(mysql_query("SELECT * FROM para where nick='$user[username]'"));
$data=date("Y-m-d");
if(!$par[nick]){
mysql_query("insert into para (nick,data) values ('$user[username]','$data')");$par=mysql_fetch_array(mysql_query("SELECT * FROM para where nick='$user[username]'"));
}

if($par[data]!=="$data"){
mysql_query("DELETE FROM para");}

if(($action!=="arena") and ($action!=="map") and ($action!=="nbattle") and ($action!=="run") and ($action!=="laiv")){
$nn=strtolower($user[username]);
$kauk=mysql_fetch_array(mysql_query("select * from nbattle where heroe='$nn' and active='0' limit 1"));
if($kauk[id]){
if($kauk[vnd]=="0"){
$wow="action=nbattle&amp;id=$id&amp;i=$i&amp;j=$j&amp;k=$k";}
else{
$wow="action=laiv&amp;la=kov&amp;id=$id";}
include_once("names/units.php");
$name=$unit_name_s1[$kauk[unit]];
echo"<small>You still have pending battle with <b> $name </b></small><br/><small><b><anchor>[&#187;] To battlefield<go method=\"post\" href=\"index.php?$wow\"><postfield name=\"event\" value=\"$kauk[id]\"/></go></anchor></b></small><br/><small><anchor>[&#171;] Kabur<go method=\"post\" href=\"index.php?action=run&amp;id=$id\"><postfield name=\"mekeke\" value=\"$kauk[id]\"/></go></anchor></small><br/></p></card></wml>";exit;}}

include_once("skils/strategy.php");
$npm=mysql_query("SELECT COUNT(id) AS num FROM pm where nick='$user[username]' and active='0'");
$newpmm=($npm) ? mysql_result($npm, 0, 'num') : 0;
$apm=mysql_query("SELECT COUNT(id) AS num FROM pm where nick='$user[username]'");
if (($newpmm == "1") or ($newpmm == "21")) {
echo"<small><a href=\"pm.php?id=$id&amp;forum=$forum&amp;topic=$topic\">You have $newpmm messages!</a></small><br/>$line<br/>";
}
elseif ((($newpmm > 1) and ($newpmm <= 9)) or (($newpmm > 21) and ($newpmm < 30)))
{
echo"<small><a href=\"pm.php?id=$id&amp;forum=$forum&amp;topic=$topic\">You have $newpmm messages!</a></small><br/>$line<br/>";
}
elseif ((($newpmm > 9) and ($newpmm <= 20)) or ($newpmm == "30"))
{
echo"<small><a href=\"pm.php?id=$id&amp;forum=$forum&amp;topic=$topic\">You have $newpmm messages!</a></small><br/>$line<br/>";
}

if($user[trade]=="1"){
$trd=mysql_fetch_array(mysql_query("SELECT * FROM trade where name='$user[username]'"));
}
if($user[trade]=="2"){
$trd=mysql_fetch_array(mysql_query("SELECT * FROM trade where name2='$user[username]'"));
}
if(($trd[id]) and ($action!=="trade")){
if(($trd[act]=="0") and (strtolower($trd[name2])==strtolower($user[username]))){
echo"<small><a href=\"index.php?id=$id&amp;action=nick_info&amp;name=$trd[name]\">$trd[name]</a>offers trade!</small><br/>";
echo"<small><a href=\"index.php?id=$id&amp;action=trade&amp;da=sut&amp;idzz=$trd[id]\">Accept</a></small><br/>";
echo"<small><a href=\"index.php?id=$id&amp;action=trade&amp;da=atm&amp;idzz=$trd[id]\">Reject</a></small><br/>";
}
else {
if(strtolower($user[username])==strtolower($trd[name])){
echo"<small>Wait for <a href=\"index.php?id=$id&amp;action=nick_info&amp;name=$trd[name2]\">$trd[name2]'s responses!</a></small><br/>";
}
if(strtolower($user[username])==strtolower($trd[name2])){
echo"<small>Wait for <a href=\"index.php?id=$id&amp;action=nick_info&amp;name=$trd[name]\">$trd[name]'s responses!</a></small><br/>";
}
if((strtolower($user[username])==strtolower($trd[name])) and ($trd[act]=="0")){
echo"<small>No answere yet</small><br/><small><a href=\"index.php?id=$id&amp;action=trade&amp;da=ats&amp;idzz=$trd[id]\">Cancel trade!</a></small><br/>";}
else {
echo"<small><a href=\"index.php?id=$id&amp;action=trade&amp;da=trade&amp;idzz=$trd[id]\">To traderoom!</a></small><br/>";}}}


mysql_query("DELETE FROM magic where name=''");
if(preg_match('/-/i', $user[expierence])){
mysql_query("UPDATE users SET expierence='0' WHERE username='$user[username]' LIMIT 1");}
if(preg_match('/-/i', $user[gold])){
mysql_query("UPDATE users SET gold='0' WHERE username='$user[username]' LIMIT 1");}
if(preg_match('/-/i', $user[new_pm])){
mysql_query("UPDATE users SET new_pm='0' WHERE username='$user[username]' LIMIT 1");}
if(preg_match("/'/i", $browser)){
mysql_query("UPDATE users SET onl='Unknown' where session='$id'");
}else{
mysql_query("UPDATE users SET onl='".mysql_real_escape_string($browser)."' where session='$id'");
}
if(preg_match("/'/i", $koks_ip)){
mysql_query("UPDATE users SET ip='Unknown' where session='$id'");
}else{
mysql_query("UPDATE users SET ip='".mysql_real_escape_string($koks_ip)."' where session='$id'");
}
$idm="$koks_ip|$browser";
if($user[username]=="Nakked"){
@file_put_contents("nak.txt","$idm");}

if (($action == "map") or ($action == "object") or ($action == "nbattle") or ($action == "event") or ($action == "online") or ($action == "nick_info")) {
$i = addslashes(htmlspecialchars($_GET['i'])); if (!$i) { $i = ""; }
$j = addslashes(htmlspecialchars($_GET['j'])); if (!$j) { $j = ""; }
$k = addslashes(htmlspecialchars($_GET['k'])); if (!$k) { $k = ""; }
if ($action !== "nbattle") {
$place = "$i|$j|$k";
if (addslashes(htmlspecialchars($_GET['event'])) == "arena") $place = "arena";
include_once("online.php");
}
else {
$place = addslashes(htmlspecialchars($_GET['event']));
include_once("online.php");
}
if ((($k !== "") and (!file_exists("map/$i/$j/$k.php"))) or (($j !== "") and (!file_exists("map/$i/$j"))) or ((!file_exists("map/$i")))) {
echo"<small><b>Not such territory</b></small><br/>$line<br/><small><a href=\"index.php?id=$id\">$home</a></small></p></card></wml>";
mysql_close($db);
exit;
}
if ($i !== "") {
$header = "";
include("map/$i.php");
if($need){
$kei=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$user[username]' and name='$need'"));
if(!$kei[name]){
include_once("names/artifacts.php");

echo"<small>You need <b>$artifact_name[$need]</b> if you want to access this area.</small><br/>$line</p><p align=\"left\"><small><b>&#171;</b><a href=\"index.php?id=$id\">$homet</a></small></p></card></wml>";
mysql_close($db);
exit;
}
}


if ($level_limit > $user[level]) {
echo"<small>You must be at <b>$level_limit level</b> if you want to access this area.</small><br/>$line</p><p align=\"left\"><small><b>&#171;</b><a href=\"index.php?id=$id\">$homet</a></small></p></card></wml>";
mysql_close($db);
exit;
}
}
if ($j !== "") {
$header = "";
include_once("map/$i/$j.php");
include_once("names/lands.php");
$land = $land_name[$i];
if($need){
$kei=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$user[username]' and name='$need'"));
if(!$kei[name]){
include_once("names/artifacts.php");

echo"<small>You need <b>$artifact_name[$need]</b> if you want to access this area.</small><br/>$line</p><p align=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&amp;id=$id&amp;i=$i\">$land</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=$id\">$homet</a></small></p></card></wml>";
mysql_close($db);
exit;
}
}


if ($level_limit > $user[level]) {
echo"<small>You must be at <b>$level_limit level</b> if you want to access this room.</small><br/>$line</p><p align=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&amp;id=$id&amp;i=$i\">$land</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=$id\">$homet</a></small></p></card></wml>";
mysql_close($db);
exit;
}
}
if ($k !== "") {
$header = "";
include_once("names/territories.php");
$territory = $territory_name[$j];
include("map/$i/$j/$k.php");
if($need){
$kei=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$user[username]' and name='$need'"));
if(!$kei[name]){
include_once("names/artifacts.php");

echo"<small>you need <b>$artifact_name[$need]</b> if you want to access this room.</small><br/>$line</p><p align=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&amp;id=$id&amp;i=$i&amp;j=$j\">$territory</a></small><br/><small><b>&#171;</b><a href=\"index.php?action=map&amp;id=$id&amp;i=$i\">$land</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=$id\">$homet</a></small></p></card></wml>";
mysql_close($db);
exit;
}
}


if ($level_limit > $user[level]) {
echo"<small>You must be at <b>$level_limit level</b> if you want to access this area.</small><br/>$line</p><p align=\"left\"><small><b>&#171;</b><a href=\"index.php?action=map&amp;id=$id&amp;i=$i&amp;j=$j\">$territory</a></small><br/><small><b>&#171;</b><a href=\"index.php?action=map&amp;id=$id&amp;i=$i\">$land</a></small><br/><small><b>&#171;</b><a href=\"index.php?id=$id\">$homet</a></small></p></card></wml>";
mysql_close($db);
exit;
}
}
}
elseif ($action == "object") {
$place = $i;
include_once("online.php");
}
elseif ($action == "arena") {
include_once("include/arena.php");
}
elseif ($action == "abattle") {
$p = addslashes(htmlspecialchars($_GET['p'])); if (!$p) { $p = ""; }
if ($p == "") {
include_once("include/arena_battle.php");
}
elseif ($p == "spells") {
include_once("include/arena_spells.php");
}
elseif ($p == "info") {
include_once("include/arena_info.php");
}
}
else {
include_once("online.php");
}
if ($action == "map") {
include_once("include/map.php");
}

if ($action == "kred") {
echo"<small>No information yet</small><br/>$line<br/><small><a href=\"index.php?id=$id\">$home</a></small>";
}
if ($action == "object") {
include_once("include/object.php");
}
if ($action == "event") {
include_once("include/event.php");
}
if ($action == "nbattle") {
$p = addslashes(htmlspecialchars($_GET['p'])); if (!$p) { $p = ""; }
if ($p == "") {
include_once("include/neutral_battle.php");
}
elseif ($p == "spells") {
include_once("include/nbattle_spells.php");
}
elseif ($p == "info") {
include_once("include/nbattle_info.php");
}
}

include_once("include/newskill.php");
include_once("include/aukcionas.php");

include_once("include/ally.php");
if($action=="rekla"){
include_once("rekla.php");}
if($action=="frenzy"){
include_once("include/frenzy.php");}
if($action=="sfrenzy"){
include_once("include/super_frenzy.php");}

if($action=="catapulta"){
$war=mysql_fetch_array(mysql_query("SELECT hp FROM war where user='$user[username]' and machine='catapulta'"));
if($war[hp]){
echo"<small>You already have catapult!</small>";}
elseif(($user[gold]<15000) or ($user[wood]<5)){
echo"<small>Not enough resources</small>";}
else{
mysql_query("update users set wood=wood-5,gold=gold-15000 where username='$user[username]'");
mysql_query("insert into war (user,machine,hp) values ('$user[username]','catapulta','1000')");
echo"<small>Catapult was successfully bought</small>";}
echo"<br/>$line<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}



if($action=="laiv"){
include_once("include/laiv.php");}


if($action=="akad"){
include_once("include/akad.php");}
if($action=="viktz"){
if(($user[status]!=="Administrator") and ($user[status]!=="Moderator") and ($user[status]!=="Captain")){echo"Error! Only for admin!</p></card></wml>";exit;}
include_once("vikt.php");
for($i=$nuo; $i<count($kls); $i  ){
mysql_query("insert into viktorinos_klausimai (klausimas,atsakymas) values ('$kls[$i]','$ats[$i]')");}
$nph = array_reverse(file("kls.txt"));
$kiek_nph = count($nph);
for ($oh = 0; $oh < $kiek_nph; $oh  )
{
$oph = explode("|", $nph[$oh]);
mysql_query("insert into viktorinos_klausimai (klausimas,atsakymas) values ('$oph[0]','$oph[1]')");
}
echo"Date Added";}
if($action=="vikt"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error! Only for admin!</p></card></wml>";exit;}
include_once("include/viktorina.class.php");
$cViktorina = new cViktorina(1);
if ($cViktorina->start)
{
$cViktorina->stop();
echo"Quiz stopped.<br/><small><a href=\"quiz-$id\">Quiz</a></small><br/>$line<br/><small><a href=\"ac-xcpanelx-$id\">cPanel</a></small><br/>";
echo"<small><a href=\"cl-$id\">$home</a></small>";
}else
{
$cViktorina->start();
echo"Quiz started.<br/><small><a href=\"quiz-$id\">Quiz</a></small><br/>$line<br/><small><a href=\"ac-xcpanelx-$id\">cPanel</a></small><br/>";
echo"<small><a href=\"cl-$id\">$home</a></small>";}}
if($action=="linija"){
include_once("include/linija.php");}
if($action=="linija2"){
include_once("include/linija2.php");}
if($action=="scholar"){
include_once("include/scholar.php");}
if($action=="shop1"){
include_once("include/shop.php");}
if($action=="shop2"){
include_once("include/shop.php");}

if($action=="castle"){
include_once("include/castle.php");}
if($action=="find"){
include_once("include/find.php");}
if($action=="game"){
include_once("game.php");}
if($action=="krdinf"){
include_once("include/krdinf.php");}
if($action=="delpm"){
$pid=$_GET['pid'];
mysql_query("delete from pm where id='$pid' limit 1");
echo"<small>OK</small><br/><small><a href=\"index.php?id=$id\">$home</a></small>";}



if($action=="run"){
include_once("include/run.php");}
if($action=="barak"){
include_once("include/barak.php");}
if($action=="member"){
include_once("member.php");}
if($action=="infor"){
include_once("include/info.php");}
if($action=="rpmd"){
if(($user[status]!=="King") and ($user[status]!=="Administrator")){echo"Error! Only for admin!</p></card></wml>";exit;}
include_once("rpmd.php");}
if($action=="aukats"){
if(($user[status]!=="King") and ($user[status]!=="Administrator")){echo"Error! Only for admin!</p></card></wml>";exit;}
include_once("aukats.php");}
if($action=="barak5"){
include_once("include/barak.php");}
if($action=="barak2"){
include_once("include/barak.php");}
if($action=="barak3"){
include_once("include/barak.php");}
if($action=="barak4"){
include_once("include/barak.php");}
if($action=="krd"){
include_once("include/krd.php");}
if($action=="reglog"){
include_once("reglog.php");}
if($action=="findip"){
include_once("include/findip.php");}

if($action=="next"){
include_once("include/next.php");}

if ($action == "") {
$place="pagr";
include_once("online.php");
$usr=strtolower($user[username]);
$nauj=mysql_fetch_array(mysql_query("SELECT date FROM news order by date desc LIMIT 1"));
mysql_query("UPDATE laivynas SET ejimas='999999999' where user='Arshc'");
$day = mysql_fetch_array(mysql_query("SELECT day, time FROM time LIMIT 1"));
$queries++;
if ($day[1] < $time) {
$dd = ($time - $day[1]) / $day_length;
$days = ceil($dd);
$day[1] = $day_length - ($day_length + ceil(($dd - $days) * $day_length)) + $time;
$day[0] = $days + $day[0];
mysql_query("UPDATE time SET day='$day[0]', time='$day[1]' LIMIT 1");
$queries++;
}
if ($user[day] < $day[0]) {
$days = $day[0] - $user[day];
$mp2=2;
$mp7=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$user[username]' and det='1' and name='wizards_well'"));
if($mp7[name]){
$mp2=$mp2+50;}
$mp5=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$user[username]' and det='1' and name='talisman_of_mana'"));
if($mp5[name]){
$mp2=$mp2+2;}
$mp4=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$user[username]' and det='1' and name='charm_of_mana'"));
if($mp4[name]){
$mp2=$mp2+1;}
$mp6=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$user[username]' and det='1' and name='mystic_orb_of_mana'"));
if($mp6[name]){
$mp2=$mp2+3;}
include_once("skils/mistic.php");
$mp2=$mp2*$days;
if($user[maxmana]-$user[mana]<$mp2){
$mp2=$user[maxmana]-$user[mana];}
mysql_query("UPDATE users SET mana=mana+$mp2 where username='$user[username]'");
if ($days > 6) $days = 6;
include_once("core/gold.php");
include_once("res/mercury.php");
include_once("res/sulfur.php");
include_once("res/gem.php");
include_once("res/stone.php");
include_once("res/wood.php");
include_once("res/crystal.php");
include_once("res/cor.php");
if($crt>0){
$crt=$crt*$days;
mysql_query("UPDATE users SET crystal=crystal+$crt where session='$id' LIMIT 1");}
if($stn>0){
$stn=$stn*$days;
mysql_query("UPDATE users SET stone=stone+$stn where session='$id' LIMIT 1");}
if($wd>0){
$wd=$wd*$days;
mysql_query("UPDATE users SET wood=wood+$wd where session='$id' LIMIT 1");}
if($mer>0){
$mer=$mer*$days;
mysql_query("UPDATE users SET mercury=mercury+$mer where session='$id' LIMIT 1");}
if($gms>0){
$gms=$gms*$days;
mysql_query("UPDATE users SET gem=gem+$gms where session='$id' LIMIT 1");}
if($sul>0){
$sul=$sul*$days;
mysql_query("UPDATE users SET sulfur=sulfur+$sul where session='$id' LIMIT 1");}

$lai=mysql_fetch_array(mysql_query("SELECT * FROM laivynas where user='$user[username]'"));
if($lai[user]){
include_once("ships/$lai[name].php");
include_once("skils/navigace.php");
if($nav>0){
$speed=round($speed*$nav);
}
mysql_query("UPDATE laivynas SET ejimas='$speed' where user='$user[username]'");}
$gold = $days * $gold_day;
mysql_query("UPDATE users SET day='$day[0]', gold=gold+$gold WHERE session='$id' LIMIT 1");
$queries++;
}
include_once("names/classes.php");
$class = $class_name[$user['class']];
$datex = date("m-d H:i");
$date=date("l, j F h:iA");
$alpm=($apm) ? mysql_result($apm, 0, 'num') : 0;
$mana=$user[knowledge]*10;
include_once("skils/intelekt.php");
if($user[maxmana]<$mana){
mysql_query("UPDATE users SET maxmana='$mana' where session='$id'");
}

if($user[level]<3){
echo"<small><a href=\"index.php?id=$id&amp;action=infor\">How to play?</a></small><br/>";
}
if($user[skill_points]>"0"){
echo"<small><a href=\"index.php?id=$id&amp;action=newskill\">You have $skl unused skillpoints!</a></small><br/>";
}
if($user[kvietimas]!=="0"){
$aly=mysql_fetch_array(mysql_query("SELECT * FROM ally where id='$user[kvietimas]'"));
$alyx=htmlspecialchars($aly[pavadinimas]);
echo"<small><a href=\"index.php?id=$id&amp;action=ally&amp;idz=$aly[id]\">$alyx</a> alliance invites you to be one of them member!</small><br/>";
echo"<small><a href=\"index.php?id=$id&amp;action=stot&amp;idz=$aly[id]\">Agree</a></small> | ";
echo"<small><a href=\"index.php?id=$id&amp;action=mest&amp;idz=$aly[id]\">Disagree</a></small><br/>$line<br/>";}
if($user[member]=="0"){
echo"<small><a href=\"index.php?id=$id&amp;action=member\">Be a member and be rewarded!!!</a></small><br/>";
}

echo"<b><a href='ac-ancs-$id'>[&#187;] News Update [&#171;]</a></b>";
echo"<br/>$line<br/>";
echo"<img src=\"img/logobanner.png\" alt=\"$title\"/><br/>$line<br/><small>[$date]</small><br/>$line<br/>";
$eyeko=mysql_num_rows(mysql_query("SELECT * FROM anc"));
$hehes = mysql_query("SELECT anc, addedby, id, anctime FROM anc
ORDER BY anctime DESC LIMIT 1");
$sql = "SELECT anc, addedby, id, anctime FROM anc ORDER BY anctime DESC LIMIT 1";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while($hehe = mysql_fetch_array($hehes))
{
$x4 = stripslashes(htmlspecialchars(strlen($hehe[0])<25?$hehe[0]:substr($hehe[0],0,21)));
}
}
$lshouts = mysql_query("SELECT shout, shouter, id,shtime FROM shouts
ORDER BY shtime DESC LIMIT 1");
$sql = "SELECT id, shout, shouter,shtime FROM shouts ORDER BY shtime DESC LIMIT 1";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while($lshout = mysql_fetch_array($lshouts)){
$shad=$lshout[3];
$remain=time() - $shad;
echo"<b>Shoutbox:</b><br/><a href=\"index.php?id=$id&amp;action=nick_info&amp;name=$lshout[1]\">$lshout[1]</a>: ";
$x4 = stripslashes(htmlspecialchars($lshout[0]));
$past=gettimemsg($remain);
$shadd=date("g:ia",$shad);
$shdt=date("M d",$shad);
$shday=date("l",$shad);
$tg=explode(" ",$past);
if($past=="1 day ago"){$pst="Kemarin pada $shadd";}elseif(($past=="2 days ago") or ($past=="3 days ago") or ($past=="4 days ago") or ($past=="5 days ago") or ($past=="6 days ago")){$pst="$shday, $shadd";}elseif(($past=="7 days ago") and ($tg[0]>6)){$pst="$shdt at $shadd";}else{$pst="$past";}
echo"$x4 - $pst";
if(($user[status] == "Administrator") or ($user[status] == "Moderator") or ($user[status] == "King")){
echo"<a href=\"index.php?id=$id&amp;action=delsh&amp;shid=$lshout[2]\">X</a>";}
echo"<br/>";}}
else{
echo"No shout yet<br/>";}
echo"<a href=\"index.php?id=$id&amp;action=shout\">Shout</a> | ";
echo"<a href=\"index.php?id=$id&amp;action=shouts\">More</a><br/>---</br>";
echo"</p><p align='center'>";
include_once("core/level.php");
$level = level($user[level]);
if ($level[$user[level]] <= $user[expierence]) {
$lev = $user[level]+1;
echo"<small><u><a href=\"index.php?id=$id&amp;action=level\"><b>You reached $lev level!</b></a></u></small><br/>***<br/>";
}
if ($user[battle] > $time) {
$left = $user[battle] - $time;
if($left<2){$sx="Second";}else{$sx="Seconds";}
echo"<small><b><u>You need to rest for $left $sx.</u></b><br/></small>***<br/>";
}
echo"</p><p align='center'>";
if ($user[rain] > time()){ echo"<small><b>Experience Rain!!!</b></small><br/>";
$left2=$user[rain]-$time;
$h2 = floor($left2 / 3600);
$m2 = floor(($left2- ($h2 * 3600)) / 60);
$s2 = $left2 - $h2 * 3600 - $m2 * 60;
if($h2<2){$xh="Hour";}else{$xh="Hours";}
if($m2<2){$xm="Minute";}else{$xm="Minutes";}
if($s2<2){$xs="Second";}else{$xs="Seconds";}
echo"<small><b>Times left : ";
if($h2>0){echo"$h2 $xh, ";}
if($m2>0){echo"$m2 $xm, ";}elseif(($h2>0) and ($m2=="0")){echo"$m2 $xm, ";}
if($s2>0){echo"$s2 $xs";}else{echo"$s2 $xs";}
echo"</b></small><br/>***<br/>";}

if (($user[sfrenzy] > time()) or ($user[sfrenzy2]>0)){ echo"<small><b>Super Frenzy!!!</b></small><br/>";
if($user[sfrenzy]>time()){
$left2=$user[sfrenzy]-$time;}
else {
$left2=$user[sfrenzy2];}
$h2 = floor($left2 / 3600);
$m2 = floor(($left2- ($h2 * 3600)) / 60);
$s2 = $left2 - $h2 * 3600 - $m2 * 60;
echo"<small><b>Times left : ";
if($h2<2){$xh="Hour";}else{$xh="Hours";}
if($m2<2){$xm="Minute";}else{$xm="Minutes";}
if($s2<2){$xs="Second";}else{$xs="Seconds";}
if($h2>0){echo"$h2 $xh, ";}
if($m2>0){echo"$m2 $xm, ";}elseif(($h2>0) and ($m2=="0")){echo"$m2 $xm, ";}
if($s2>0){echo"$s2 $xs";}else{echo"$s2 $xs";}
if(($h2=="0") and ($m2=="0") and ($s2=="0")){echo"Expired :(";}
echo"</b></small><br/>";
if($user[sfrenzy]>time()){
$sf="Stop";} else {
$sf="Start";}
echo"<small><a href=\"index.php?action=sfrenzy&amp;id=$id\">$sf</a></small><br/>***<br/>";}

if (($user[immortal] > time()) or ($user[fre]>0)){ echo"<small><b>Frenzy!!!</b></small><br/>";
if($user[immortal]>time()){
$left2=$user[immortal]-$time;}
else {
$left2=$user[fre];}
$h2 = floor($left2 / 3600);
$m2 = floor(($left2- ($h2 * 3600)) / 60);
$s2 = $left2 - $h2 * 3600 - $m2 * 60;
if($h2<2){$xh="Hour";}else{$xh="Hours";}
if($m2<2){$xm="Minute";}else{$xm="Minutes";}
if($s2<2){$xs="Second";}else{$xs="Seconds";}
echo"<small><b>Times left : ";
if($h2>0){echo" $h2 $xh, ";}
if($m2>0){echo"$m2 $xm, ";}elseif(($h2>0) and ($m2=="0")){echo"$m2 $xm, ";}
if($s2>0){echo"$s2 $xs";}else{echo"$s2 $xs";}
if(($xh=="0") and ($xm=="0") and ($s2=="0")){echo"Expired :(";}
echo"</b></small><br/>";
if($user[immortal]>time()){
$fr="Stop";} else {
$fr="Start";}
echo"<small><a href=\"index.php?action=frenzy&amp;id=$id\">$fr</a></small><br/>***<br/>";}
echo"</p><p align='left'>";
echo"<small><b>$user[username]'s Menu</b></small><br/>
<small><b>[*]</b> <a href=\"index.php?action=mymenu&amp;id=$id\">My Castle</a></small><br/><small><b>[*]</b> <a href=\"index.php?action=capitol&amp;id=$id\">Capitol</a></small>";
if($user[ally]!=="0"){
echo"<br/><small><b>[*]</b> <a href=\"index.php?action=ally&amp;id=$id&amp;idz=$user[ally]\">Alliance</a></small>";}
echo"<br/><small><b>[*]</b> <a href=\"pm.php?id=$id\">Inbox [$newpmm/$alpm]</a></small><br/>";
$kr4=number_format($user[kred]);
echo"<small><b>[*]</b> <a href=\"index.php?action=krd&amp;id=$id\">Kroin [$kr4]</a></small><br/>";
$on = mysql_query("SELECT place FROM users WHERE time>$time");
$queries++;
$onl[kaln] = 0;
$onl[arena] = 0;
$onl[forum] = 0;
$onl[zod] = 0;
$onl[gb] = 0;
$onl[gb2100] = 0;
while ($onn = mysql_fetch_array($on)) {
$online++;
@$onl[$onn[0]]++;
}
echo"</p><p align=\"left\">";
echo"<small><b><u>Heroes World</u></b></small><br/>";
echo"<small><b>$bar</b> <a href=\"index.php?action=laiv&amp;id=$id\">Port</a></small><br/>";
//echo"$line<br/><small><u><b>Peta Wilayah</b></u></small><br/>";
$lands = 0;
if ($handle = opendir("map/")) {
while (false !== ($file = readdir($handle))) {
if ($file != "." && $file != ".." && $file != "index.php" && $file != "act.php") {
$file = explode(".", $file);
if ($file[1] == "") {
$land[$lands] = "$file[0]";
$lands++;
}
}
}
closedir($handle);
}
include_once("names/lands.php");
for ($t = 0; $t < count($land); $t++) {
$landn = $land_name[$land[$t]];
if ($t > 0) { echo"<br/>"; }
if($land[$t]!=="act"){
echo"<small><b>$bar</b> <a href=\"index.php?action=map&amp;id=$id&amp;i=$land[$t]\">$landn</a></small>";
}        }
$left = $day[1] - $time;
$file = @fopen("online.txt", "r");
@flock($file, 1);
@$count = fgets($file, 255);
@fclose($file);
$count = explode("|", $count);
if ($online >= $count[0]) {
$file = @fopen("online.txt", "w");
$date = date("m-d H:i");
$count = "$online|$date";
fputs($file, $count);
flock($file, 2);
fclose($file);
}
$h = floor($left / 3600);
$m = floor(($left- ($h * 3600)) / 60);
$s = $left - $h * 3600 - $m * 60;
echo"
<br/>";
if($topic[max]<$online){
$dta=date("Y-m-d H:i:s");
$max="$online $dta";
mysql_query("UPDATE spec SET max='$max'");}
$to=explode(" ",$topic[max]);
echo"</p><p align='left'>";
echo"<small><b><u>Community &amp; Others</u></b></small><br/>";
echo"
<small><b>[*]</b> <a href=\"tavern-$id\">Tavern [$onl[gb]]</a></small><br/>";
echo"
<small><b>[*]</b> <a href='ac-game-$id'>Trivia Quiz [$onl[zod]]</a></small><br/>";
echo"
<small><b>[*]</b> <a href=\"forum.php?id=$id\">Forum [$onl[forum]]</a></small><br/>";
echo"
<small><b>[*]</b> <a href=\"index.php?action=arena&amp;id=$id\">Gladiator [".$onl[arena]."]</a></small><br/>";
echo"
<small><b>[*]</b> <a href='ac-tophero-$id'>Hall of Fame</a></small><br/>";
echo"
<small><b>[*]</b> <a href='ac-infor-$id'>Information</a></small>";
echo"</p><p align='left'>";
if(($user[status] == "Moderator") or ($user[username] =="Arshc")){ echo"<small><b>Staff Panel</b><br/><b>[*]</b> <a href=\"index.php?action=xcpanelx&amp;id=$id\">Admin Panel</a></small><br/>";}
if(($user[status] == "Administrator") or ($user[username] =="Arshc")){ echo"<b>[*]</b> <a href=\"index.php?action=usernick&amp;id=$id\">User Panel</a>";}
if(($user[status] == "Administrator") or ($user[username] =="Arshc")){echo"<br/><b>[*]</b> <a href=\"index.php?action=tool&amp;id=$id\">Artifact Tools</a><br/><b>[*]</b> <a href=\"index.php?action=cl1&amp;id=$id\">Nick Tools</a>";}

echo"</p><p align='center'>";
$dax=$day[0];
if($dax<2){$dax="Day";}else{$dax="Days";}
if($h<2){$hx="Jam";}else{$hx="Hours";}
if($m<2){$mx="Minute";}else{$mx="Minutes";}
if($s<2){$sx="Second";}else{$sx="Seconds";}
echo"$line<br/>
<small>Games $dax : </small> $day[0] $dax<br/>";
echo"<small>Next Day : </small><br/>
<small>";
if($h>0){echo"<b>$h </b> $hx, ";}
if($m>0){echo"<b>$m </b> $mx, ";}elseif(($h>0) and ($m=="0")){echo"<b>$m </b> $mx, ";}
if($s>0){echo"<b>$s </b> $sx";}else{echo"<b>$s </b> $sx";}
if(($h=="0") and ($m=="0") and ($s=="0")){echo"<b><br/>Resources and mana earned!</b>";}
echo"</small><br/>$line<br/>";
echo"<b><a href=\"index.php?action=online&amp;id=$id\"> Now Online [$online]</a></b><br/>";
echo"<b><a href=\"index.php?action=maxon&amp;id=$id\">Max Online [$to[0]]</a></b><br/>";
echo"<b><a href=\"index.php?action=logout&amp;id=$id\">&#171; Logout</a></b>";}

elseif($action=="allyreit"){
include_once("include/reit.php");}
elseif ($action == "mymenu") {
include_once("include/my_menu.php");
}
elseif ($action == "pltop") {
include_once("include/pltop.php");
}
elseif ($action == "huinfo") {
include_once("include/my_unit_info.php");
}
elseif ($action == "btop") {
include_once("include/btop.php");
}
elseif ($action == "qtop") {
include_once("include/qtop.php");
}
elseif ($action == "sinfo") {
include_once("include/skill_info.php");
}
elseif ($action == "library") {
include_once("include/library.php");
}
elseif ($action == "ainfo") {
include("names/artifacts.php");
include("names/artap.php");
include_once("include/artifacts_info.php");
}
elseif($action=="useart"){
include_once("include/useart.php");}
elseif ($action == "online") {
include_once("include/online.php");
}
elseif ($action == "level") {
include_once("include/level.php");
}
elseif ($action == "profile") {
include_once("include/profile.php");
}
elseif ($action == "profile2") {
include_once("include/profile.php");
}
elseif ($action == "profile3") {
include_once("include/profile.php");
}
elseif ($action == "profile4") {
include_once("include/profile.php");
}
elseif ($action == "pvp") {
include_once("include/pvp.php");
}
elseif ($action == "profile5") {
include_once("include/profile.php");
}
elseif ($action == "ns") {
include_once("include/ns.php");
}
elseif ($action == "capitol") {
include_once("include/capitol.php");
}
elseif ($action == "nick_info") {
include_once("include/view_nick_info.php");
}
elseif ($action == "qturn") {
include_once("include/qturnyras.php"); }
elseif ($action == "newbie") { include_once("include/newbie.php");
}
elseif ($action == "trade") {
include_once("include/trade.php");
}
elseif ($action == "alibrary") {
include_once("include/alibrary.php");
}
elseif ($action == "qturn1") {
include_once("include/qturnyras.php"); }
elseif ($action == "newbie1") { include_once("include/newbie.php");
}
elseif ($action == "qturn2") {
include_once("include/qturnyras.php"); }
elseif ($action == "newbie2") { include_once("include/newbie.php");
}
elseif ($action == "potion_shop") {
include_once("include/potion_shop.php");
}
elseif ($action == "slibrary") {
include_once("include/slibrary.php");
}
elseif ($action == "wizardry") {
include_once("include/school_of_wizardry.php");
}
elseif ($action == "qturn3") {
include_once("include/qturnyras.php"); }
elseif ($action == "newbie3") { include_once("include/newbie.php");
}
elseif ($action == "tophero") {
include_once("include/tophero.php");
}
elseif ($action == "artinfo") {
include_once("include/a_info.php");
}
elseif ($action == "top") {
include_once("include/top.php");
}
elseif ($action == "toplan") {
include_once("include/toplan.php");
}
elseif ($action == "quiz") {
include_once("include/viktorina.php");
}
elseif ($action == "cas") {
include_once("include/casino.php");
}
elseif ($action == "reg") { include_once("include/ns.php"); } elseif ($action == "gld2") {
include_once("include/casino.php");
}
elseif ($action == "cr2") {
include_once("include/casino.php");
}
elseif ($action == "wear") {
include_once("include/wear.php");
}
elseif ($action == "shout") {
include_once("shout.php");}
elseif ($action == "shoutproc") {
include_once("shoutproc.php");}
elseif ($action == "shouts") {
include_once("shouts.php");}
elseif ($action == "delsh") {
include_once("deleshout.php");}
elseif ($action == "anc") {
include_once("xanc.php");}
elseif ($action == "ancp") {
include_once("xancp.php");}
elseif ($action == "ancs") {
include_once("xancs.php");}
elseif ($action == "delanc") {
include_once("xancdel.php");}
elseif ($action == "logout") {
$name = strtolower($user[username]);
$queries++;
mysql_query("UPDATE users SET time='$time' WHERE username='$name' LIMIT 1");
echo"<small>You are offline!Please come back later!</small><br/>$line<br/><small><a href=\"index.php?lang=$lang\">$home</a></small>";
}
elseif ($action == "usernick") {
if(($user[id]!=="1") and ($user[id]!=="2") and ($user[status]!=="Administrator")){echo"Error!</p></card></wml>";exit;}
$wer = $_GET["wer"];
$sts = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id='$wer' LIMIT 1"));
echo"cPanel-Username<br/>";
echo"ID: <br/><input type=\"text\" name=\"idxx\" value=\"$wer\"/><br/>";
echo"To What Username?:<br/><input name=\"username\" type=\"text\" maxlength=\"20\" value=\"$sts[username]\"/><br/>";
echo"Status:<br/><input name=\"userstats\" type=\"text\" maxlength=\"20\" value=\"$sts[status]\"/><br/>";
echo "<anchor>Update!";
echo "<go href=\"index.php?action=xuserinfoxx&amp;id=$id&amp;wer=$wer\" method=\"post\">";
echo "<postfield name=\"idxx\" value=\"$(idxx)\"/>";
echo "<postfield name=\"username\" value=\"$(username)\"/>";
echo "<postfield name=\"userstats\" value=\"$(userstats)\"/>";
echo "</go></anchor><br/>";
echo "$line<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif ($action == "xuserinfoxx") {
if(($user[id]!=="1") and ($user[id]!=="2") and ($user[status]!=="Administrator")){echo"Error!</p></card></wml>";exit;}
$wer = $_GET["wer"];
$idxxx = $_POST["idxx"];
$username = $_POST["username"];
$userstats = $_POST["userstats"];
$res = mysql_query("UPDATE users SET username='$username', status='$userstats' WHERE id='$idxxx' LIMIT 1");
if($res){
echo "<small>User CodeName Updated!</small><br/>id: $idxxx<br/>New Nick: $username<br/>Status: $userstats";
}else{
echo "Error!<br/>";}
echo "<br/>$line<br/><small><a href=\"index.php?id=$id&amp;action=usernick&amp;wer=$wer\">$back</a><br/></small><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif ($action == "tool") {
if(($user[id]!=="1") and ($user[id]!=="2")){echo"Error!</p></card></wml>";exit;}
$wer = $_GET["wer"];
$sts = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id='$wer' LIMIT 1"));
if($wer==""){
echo"Artifact Tools-<br/>";
echo"User: <br/><input type=\"text\" name=\"tuser\"/><br/>";
echo"Artifact Name: <br/><input type=\"text\" name=\"artn\"/><br/>";
echo"Art-Type:<br/><input name=\"artt\" type=\"text\" maxlength=\"20\"/><br/>";
echo"ILan?:<br/><input type=\"text\" name=\"ilan\" maxlength=\"2\"/><br/>";
echo "<anchor>Give Art";
echo "<go href=\"index.php?action=xgiveart&amp;id=$id&amp;wer=$wer\" method=\"post\">";
echo "<postfield name=\"tuser\" value=\"$(tuser)\"/>";
echo "<postfield name=\"artn\" value=\"$(artn)\"/><postfield name=\"artt\" value=\"$(artt)\"/><postfield name=\"ilan\" value=\"$(ilan)\"/>";
echo "</go></anchor>";}
else{echo"Give $wer Artifact?<br/>";
echo"Artifact Name: <br/><input type=\"text\" name=\"artn\"/><br/>";
echo"Art-Type:<br/><input name=\"artt\" type=\"text\" maxlength=\"20\"/><br/>";
echo"ILan?:<br/><input type=\"text\" name=\"ilan\" maxlength=\"2\"/><br/>";
echo "<anchor>Give Art";
echo "<go href=\"index.php?action=xgiveart&amp;id=$id\" method=\"post\">";
echo "<postfield name=\"tuser\" value=\"$wer\"/>";
echo "<postfield name=\"artn\" value=\"$(artn)\"/><postfield name=\"artt\" value=\"$(artt)\"/><postfield name=\"ilan\" value=\"$(ilan)\"/>";
echo "</go></anchor>";}
echo "<br/>$line<br/><small><a href=\"index.php?id=$id\">$home</a></small>";
}
elseif ($action == "xgiveart") {
if(($user[id]!=="1") and ($user[id]!=="2")){echo"Error!</p></card></wml>";exit;}
$wer = $_GET["wer"];
$tuser = $_POST["tuser"];
$artn = $_POST["artn"];
$artt = $_POST["artt"];
$quan = $_POST["ilan"];
if ($artn!==""){
include_once("names/artifacts.php");
$art=$artifact_name[$artn];
echo"$tuser, received $quan $art - a $artt .<br/>";
echo"<img src=\"/img/artifact/$artn.gif\" alt=\"hehe\"/><br/>";
include_once("artifact/use/$artn.php");
$art=mysql_fetch_array(mysql_query("SELECT * FROM artifacts where user='$tuser' and name='$artn'"));
if (!$art[name]){
mysql_query("insert into artifacts(user,name,kiek,type) values ('$tuser','$artn','$quan','$artt')");}
else{
mysql_query("UPDATE artifacts SET kiek=kiek+$quan WHERE name='$artn' and user='$tuser'");}}
echo "<small></small><br/>$line<br/><small><a href=\"index.php?id=$id&amp;action=tool&amp;wer=$wer\">$back</a></small><br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="avand"){
if($user[status]!=="Administrator"){echo"Error!</p></card></wml>";exit;}
include_once("jura.php");
mysql_query("DELETE FROM jura where type!='game'");
for($p=0; $p<count($jura); $p++){
$obi=explode("-",$jura[$p]);
$ex=explode("|",$obi[4]);
$ex2=explode("|",$obi[5]);
$tim=time()+$ex[0]*$ex[1];
$tim2=$ex2[0]*$ex2[1];
mysql_query("insert into jura (name,type,kiek,loc,time,time2,subtype,res,kres) values ('$obi[0]','$obi[1]','$obi[2]','$obi[3]','$tim','$tim2','$obi[6]','$obi[7]','$obi[8]')");}
echo"<small>Refreshed</small>";}

if($action=="gold"){
$name=$_GET['name'];
echo"<small>Kau mentransfer <b>$name</b></small><br/><small>Jumlah : </small><br/><input type=\"texu\" name=\"gold\" format=\"*N\"/><br/><small><anchor>Transfer<go method=\"post\" href=\"index.php?id=$id&amp;action=gold2&amp;name=$name\"><postfield name=\"gold\" value=\"$(gold)\"/></go></anchor></small><br/>$line<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}

if($action=="gold2"){
$name=$_GET['name'];
$gold=$_POST['gold'];
$usr=mysql_fetch_array(mysql_query("SELECT * FROM users where username='$name'"));
if(($user[ally]<1) or ($user[ally]!==$usr[ally])){
echo"<small>Error!</small>";}
elseif($gold>$user[gold]){
echo"<small>Emas tidak cukup!</small>";}
elseif($user[perv]>time()){
echo"<small>Kau tidak bisa mentransfer sekarang.</small>";}
elseif($usr[level]*100000<$gold){
$pgo=$usr[level]*100000;
echo"<small>Kau tidak bisa transfer lebih dari $pgo emas.</small>";}
else {
$ti=time()+3600*2;
mysql_query("UPDATE users SET gold=gold-$gold,perv='$ti' where username='$user[username]'");
mysql_query("UPDATE users SET gold=gold+$gold where username='$name'");
echo"<small>Mentransfer $gold emas ke $name</small>";}
echo"<br/>$line<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="truncates"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[id]!=="2")){echo"Error!Only for admin!</p></card></wml>";exit;}
echo"<b>Truncator Tool(Truncate..)</b><br/>";
$nbkh = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM nbattle"));
$pmkh = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM pm"));
$obkh = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM objects"));
$aukh = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM aukatas"));
$mpkh = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM map"));
echo"<small><a href=\"ac-deletnbattle-$id\">Battles</a> $nbkh[0]</small><br/>";
echo"<small><a href=\"ac-deletpms-$id\">Private Msgs</a> $pmkh[0]</small><br/>";
echo"<small><a href=\"ac-deletobjects-$id\">Object Logs</a> $obkh[0]</small><br/>";
echo"<small><a href=\"ac-deletlogs-$id\">Market Logs</a> $aukh[0]</small><br/>";
echo"<small><a href=\"ac-deletchat-$id\">Chat Msgs</a></small><br/>";
echo"<small><a href=\"ac-deletqchat-$id\">Quiz Msgs</a></small><br/>";
echo"<small><a href=\"ac-deletmap-$id\">Map Logs</a> $mpkh[0]</small><br/>";
echo"<small><a href=\"ac-deletshouts-$id\">Shouts</a><br/>";
echo"<small><a href=\"ac-deletancs-$id\">Announcements</a></small><br/>";
echo"<a href=\"cl-$id\">$home</a></small>";}
if($action=="xcpanelx"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
$dti=date("Y-m-d");
$sn=mysql_query("SELECT COUNT(id) AS num FROM sms where data='$dti'");
$snd=($sn) ? mysql_result($sn,0, 'num') : 0;
$al=mysql_query("SELECT COUNT(id) AS num FROM sms");
$all=($al) ? mysql_result($al,0, 'num') : 0;
$lt=0;
$lt2=0;
$ltu=mysql_query("SELECT kaina FROM sms where data='$dti'");
while($row=mysql_fetch_array($ltu)){
$li=$row['kaina'];
$lt=$lt+$li;}
$ltu2=mysql_query("SELECT kaina FROM sms");
while($row2=mysql_fetch_array($ltu2)){
$li2=$row2['kaina'];
$lt2=$lt2+$li2;}
if($user[status]=="Moderator"){
include_once("include/viktorina.class.php");
$cViktorina = new cViktorina(1);
if ($cViktorina->start)
{echo"<small><a href=\"ac-vikt-$id\">Hentikan kuis</a></small><br/>";}
else{echo"<small><a href=\"ac-vikt-$id\">Aktifkan kuis</a></small><br/>";}
echo"<small><a href=\"ac-qmpanel-$id\">Panel Kuis</a></small><br/>";
echo"<small><a href=\"ac-anc-$id\">Announce</a><br/>";
}else{
echo"<small><a href=\"ac-truncates-$id\">Truncator(!)</a></small><br/>";
echo"<small><a href=\"ac-aukats-$id\">Members Actions</a></small><br/>";
echo"<small><a href=\"index.php?id=$id&amp;action=reglog\">New Registered Users</a></small><br/>";
echo"<small><a href=\"ac-rpmd-$id\">Messaging Panel</a></small><br/>";
echo"<small><a href=\"index.php?id=$id&amp;action=findip\">Find IP</a></small><br/>";
include_once("include/viktorina.class.php");
$cViktorina = new cViktorina(1);
if ($cViktorina->start)
{echo"<small><a href=\"ac-vikt-$id\">Hentikan kuis</a></small><br/>";}
else{echo"<small><a href=\"ac-vikt-$id\">Aktifkan kuis</a></small><br/>";}
echo"<small><a href=\"ac-qmpanel-$id\">Panel Kuis</a></small><br/>";
//echo"<small><a href=\"ac-viktz-$id\">Daftar kuis</a></small><br/>";
echo"<small><a href=\"ac-avand-$id\">Muat ulang Pelabuhan</a></small><br/>";
echo"<small><a href=\"ac-anc-$id\">Announce</a><br/>";
echo"<small><a href=\"ac-asms-$id\">SMS</a></small><br/>";}
echo"<a href=\"cl-$id\">$home</a></small>";}
elseif($action=="dnew"){
if($user[status]!=="Administrator"){echo"Error!Only for admin!</p></card></wml>";exit;}
echo"<small>Title<br/><input type=\"text\" name=\"title\"/><br/>New<br/><input type=\"text\" name=\"new\"/><br/><anchor>Add<go method=\"post\" href=\"index.php?action=dnew2&amp;id=$id\">
<postfield name=\"title\" value=\"$(title)\"/><postfield name=\"new\" value=\"$(new)\"/></go></anchor><br/>$line<br/><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="dnew2"){
$dat=date("Y-m-d H:i:s");
$title=$_POST['title'];
$new=$_POST['new'];
mysql_query("insert into news (title,zin,date) values ('$title','$new','$dat')");
echo"<small>News was added Successfully<br/>$title: $new<br/>$line<br/><a href=\"cl-$id\">$home</a></small>";}
elseif($action=="news"){
$nws=mysql_query("SELECT COUNT(id) AS num FROM news");
$ntot=($nws) ? mysql_result($nws, 0, 'num') : 0;
echo"<small><b>Arshc Heroes III - Updates</b><br/>";
$psl=$_POST['psl'];
if(!$psl){$psl=1;}
$nuo=$psl*10-10;
$iki=$psl*10;
if($ntot<1){
echo"Belum ada kabar<br/>";} else {
$news=mysql_query("SELECT title,id FROM news order by id desc LIMIT $nuo,$iki");
while($rowz=mysql_fetch_array($news)){
$tit=$rowz['title'];
$idz=$rowz['id'];
echo"<a href=\"ac-snew-$id&amp;idz=$idz\">$tit</a>";
if(($user[id]=="1") or ($user[username]=="Arshc") or ($user[username]=="Arshc1")){
echo"<a href=\"ac-cl2-$id&amp;dnew=$idz\">(x)</a>";}
echo"<br/>";}
echo"";
if($ntot>10){
$tol=$psl+1;
echo"<anchor>$next<go method=\"post\" href=\"ac-news-$id\"><postfield name=\"psl\" value=\"$tol\"/></go></anchor><br/>";}
if($psl>1){
$atg=$psl-1;
echo"<anchor>$back<go method=\"post\" href=\"ac-news-$id\"><postfield name=\"psl\" value=\"$atg\"/></go></anchor><br/>";}}
echo"$line<br/><a href=\"cl-$id\">$home</a></small>";}
elseif($action=="snew"){
$idz=$_GET['idz'];
$qua=mysql_query("SELECT date,zin,title FROM news where id='$idz'");
while($rows=mysql_fetch_array($qua)){
$tit4=$rows['title'];
$dti=$rows['date'];
$zin=$rows['zin'];}
echo"<small><b>$tit4</b><br/>$zin<br/><b>$dti</b><br/>$line<br/><a href=\"cl-$id\">$home</a></small>";}
elseif($action=="qmpanel"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
echo "Lebih spesifik, oke! :)<br/>";
echo "Pertanyaan :<br/><input name=\"question\" maxlength=\"400\"/><br/>";
echo "Jawaban :<br/><input name=\"answer\" maxlength=\"100\"/><br/>";
echo "<anchor>Submit";
echo "
<go href=\"ac-qmpanelpr-$id\" method=\"post\">
<postfield name=\"question\" value=\"$(question)\"/>
<postfield name=\"answer\" value=\"$(answer)\"/>
</go>
";
echo "</anchor><br/>";
echo "<a href=\"ac-quizlist-$id\">Daftar Kuis</a><br/>";
echo "<a href=\"cl-$id\">$home</a>";}
elseif($action=="qmpanelpr"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
$question = $_POST['question'];
$answer = $_POST['answer'];
$res = mysql_query("INSERT INTO viktorinos_klausimai SET klausimas='".$question."', atsakymas='".$answer."'");
if($res){
echo "Soal berhasil ditambahkan!<br/>Pertanyaan: $question<br/>Jawaban : $answer<br/>";
}else{
echo "Kesalahan!<br/>";}
echo "<a href=\"ac-quizlist-$id\">Daftar Kuis</a><br/>";
echo "<a href=\"ac-qmpanel-$id\">Panel kuis</a><br/>";
echo "<a href=\"cl-$id\">$home</a>";}
elseif($action=="delquiztion"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
$idx = $_GET["idx"];
$res = mysql_query("DELETE FROM viktorinos_klausimai WHERE id='".$idx."'");
if($res)
{
echo "Soal kuis berhasil dihapus!<br/>";
}else{
echo "Kesalahan!<br/>";}
echo"<a href=\"ac-quizlist-$id\">Daftar Kuis</a><br/>";
echo"<a href=\"ac-qmpanel-$id\">Panel kuis</a><br/>";
echo"<a href=\"cl-$id\">$home</a>";}
elseif($action=="quizlist"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
$page = $_GET["page"];
if($page=="" || $page<=0)$page=1;
$noi = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM viktorinos_klausimai"));
$num_items = $noi[0]; //changable
$items_per_page= 10;
$num_pages = ceil($num_items/$items_per_page);
if(($page>$num_pages)&&$page!=1)$page= $num_pages;
$limit_start = ($page-1)*$items_per_page;
//changable sql
$sql = "SELECT id, klausimas, atsakymas FROM viktorinos_klausimai ORDER BY id LIMIT $limit_start, $items_per_page";
$items = mysql_query($sql);
if(mysql_num_rows($items)>0)
{
while ($item = mysql_fetch_array($items))
{
echo "Pertanyaan : $item[1]<br/>Jawaban : $item[2] <a href=\"ac-editqq-$id&amp;idx=$item[0]\">(e)</a><a href=\"ac-delquiztion-$id&amp;idx=$item[0]\">[x]</a><br/>--<br/>";
}
}else{echo"Belum ada pertanyaan.";}
if($page>1)
{
$ppage = $page-1;
echo "<a href=\"ac-quizlist-$id&amp;page=$ppage\">$back</a> ";
}
if($page<$num_pages)
{
$npage = $page+1;
echo "<a href=\"ac-quizlist-$id&amp;page=$npage\">$next</a>";
}
echo "<br/>$page/$num_pages<br/>";
if($num_pages>2)
{
$rets = "page<input name=\"pg\" format=\"*N\" size=\"3\"/>";
$rets .= "<anchor>jump";
$rets .= "<go href=\"index.php\" method=\"get\">";
$rets .= "<postfield name=\"id\" value=\"$id\"/>";
$rets .= "<postfield name=\"action\" value=\"quizlist\"/>";
$rets .= "<postfield name=\"page\" value=\"$(pg)\"/>";
$rets .= "</go></anchor><br/>";
echo $rets;
}
echo"<a href=\"ac-qmpanel-$id\">Quiz Panel</a><br/>";
echo"<a href=\"cl-$id\">$home</a>";
}
else if($action=="editqq"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
$idx=$_GET["idx"];
$xquestion=mysql_fetch_array(mysql_query("SELECT klausimas FROM viktorinos_klausimai WHERE id='".$idx."'"));
$xanswer=mysql_fetch_array(mysql_query("SELECT atsakymas FROM viktorinos_klausimai WHERE id='".$idx."'"));
echo"Pertanyaan : <input name=\"nquestion\" maxlength=\"400\" value=\"$xquestion[0]\"/><br/>";
echo"Jawaban : <input name=\"nanswer\" maxlength=\"200\" value=\"$xanswer[0]\"/><br/>";
echo"<anchor>Masukkan";
echo"<go href=\"ac-editques-$id&amp;idx=$idx\" method=\"post\">";
echo"<postfield name=\"nquestion\" value=\"$(nquestion)\"/>";
echo"<postfield name=\"nanswer\" value=\"$(nanswer)\"/>";
echo"</go></anchor><br/>";
echo "<a href=\"ac-quizlist-$id\">Daftar kuis</a><br/>";
echo "<a href=\"ac-qmpanel-$id\">Panel kuis</a><br/>";
echo"<a href=\"cl-$id\">$home</a>";
}
else if($action=="editques"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
$xidx=$_GET["idx"];
$fcq=$_POST["nquestion"];
$fca=$_POST["nanswer"];
$res = mysql_query("UPDATE viktorinos_klausimai SET klausimas='".$fcq."', atsakymas='".$fca."' WHERE id='".$xidx."'");
if($res)
{
echo"Pertanyaan berhasil di ubah!<br/>Pertanyaan : $fcq<br/>Pertanyaan : $fca<br/>";
}else{echo"Kesalahan!<br/>";}
echo "<a href=\"ac-quizlist-$id\">Daftar kuis</a><br/>";
echo "<a href=\"ac-qmpanel-$id\">Panel kuis</a><br/>";
echo"<a href=\"cl-$id\">$home</a>";}
elseif($action=="blokas"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
echo"<small>Blocked</small><br/><small><a href=\"index.php?id=$id\">$home</a></small>";
$name=$_GET['name'];
mysql_query("UPDATE users SET blokas='1' where username='$name'");}
elseif($action=="blokas2"){
if(($user[status]!=="King") and ($user[status]!=="Administrator") and ($user[status]!=="Moderator")){echo"Error!Only for admin!</p></card></wml>";exit;}
echo"<small>Unblocked</small><br/><small><a href=\"index.php?id=$id\">$home</a></small>";
$name=$_GET['name'];
mysql_query("UPDATE users SET blokas='0' where username='$name'");}
elseif($action=="nsdel"){
if($user[id]!=="1"){
echo"<small>Error!</small></p></card></wml>";
exit;mysql_close($db);}
echo"<small>Done</small><br/><small><a href=\"cl-$id\">$home</a></small>";
$name=$_GET['name'];
mysql_query("UPDATE users SET ns='0' where username='$name'");}
elseif($action=="cl2"){
$dnew=$_GET['dnew'];
mysql_query("DELETE FROM news WHERE id='$dnew'");
echo"hmm News $dnew Removed";}
elseif($action=="cl1"){
if(($user[id]!=="1") and ($user[id]!=="2") and ($user[username]!=="Arshc")){echo"Only for Admins</p></card></wml>";exit;}
$wer = $_GET["wer"];
$h = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE username='$wer' LIMIT 1"));
if($wer==""){
echo "Update User Status(use - to deduct)<br/>";
echo "Username:<br/><input name=\"un\" maxlength=\"100\"/><br/>";
echo "Rank:<br/><input name=\"prm\" maxlength=\"100\" value=\"Peasant\"/><br/>";
echo"Level:<br/><input name=\"lvl\" maxlength=\"100\" value=\"0\"/><br/>";
echo"Exp:<br/><input name=\"exp\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Credit:<br/><input name=\"kr\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Gold:<br/><input name=\"gl\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Gem:<br/><input name=\"gem\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Mercury:<br/><input name=\"merc\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Sulfur:<br/><input name=\"sfr\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Crystal:<br/><input name=\"cry\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Wood:<br/><input name=\"wd\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Ores:<br/><input name=\"stn\" maxlength=\"100\" value=\"0\"/><br/>";}
else{echo "Update $wer Status(use - to deduct)<br/>";
echo "Username:<br/><input name=\"un\" maxlength=\"100\" value=\"$wer\"/><br/>";
echo"Level<small>($h[level])</small>:<br/><input name=\"lvl\" maxlength=\"100\" value=\"0\"/><br/>";
echo"Exp<small>($h[expierence])</small>:<br/><input name=\"exp\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Credit<small>($h[kred])</small>:<br/><input name=\"kr\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Gold<small>($h[gold])</small>:<br/><input name=\"gl\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Gem<small>($h[gem])</small>:<br/><input name=\"gem\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Mercury<small>($h[mercury])</small>:<br/><input name=\"merc\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Sulfur<small>($h[sulfur])</small>:<br/><input name=\"sfr\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Crystal<small>($h[crystal])</small>:<br/><input name=\"cry\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Wood<small>($h[wood])</small>:<br/><input name=\"wd\" maxlength=\"100\" value=\"0\"/><br/>";
echo "Stone<small>($h[stone])</small>:<br/><input name=\"stn\" maxlength=\"100\" value=\"0\"/><br/>";}
echo "<anchor>Submit";
echo "
<go href=\"ac-cl1pr-$id&amp;wer=$wer\" method=\"post\">
<postfield name=\"un\" value=\"$(un)\"/>
<postfield name=\"lvl\" value=\"$(lvl)\"/>
<postfield name=\"exp\" value=\"$(exp)\"/>
<postfield name=\"kr\" value=\"$(kr)\"/>
<postfield name=\"gl\" value=\"$(gl)\"/>
<postfield name=\"gem\" value=\"$(gem)\"/>
<postfield name=\"merc\" value=\"$(merc)\"/>
<postfield name=\"sfr\" value=\"$(sfr)\"/>
<postfield name=\"cry\" value=\"$(cry)\"/>
<postfield name=\"wd\" value=\"$(wd)\"/>
<postfield name=\"stn\" value=\"$(stn)\"/>
</go>";
echo "</anchor><br/>";
echo"----<br/>";
echo "ResetPass:<input type=\"text\" maxlength='100' name='rsxpass' title='ResetPass?:' value=''/><br/>Name:<input type=\"text\" maxlength=\"100\" name=\"ursx\" value=\"$wer\"/><br/>
<anchor>resetpass<go href='ac-cl1pr-$id&cl=p' method='post'><postfield name='rsxpass' value='$(rsxpass)'/><postfield name='ursx' value='$(ursx)'/></go></anchor><br/>";
echo "<a href=\"ac-xcpanelx-$id\">cPanel</a><br/>";
echo "<a href=\"cl-$id\">$home</a>";}
else if($action=="cl1pr"){
if(($user[id]!=="1") and ($user[id]!=="2") and ($user[username]!=="Arshc")){echo"Only for Admins</p></card></wml>";exit;}
$cl=$_GET['cl'];
$np=$_POST['rsxpass'];
$nu=$_POST['ursx'];
$wer = $_GET["wer"];
$xunx=$_POST["un"];
$lvl=$_POST["lvl"];
$exp=$_POST["exp"];
$kr=$_POST["kr"];
$gl=$_POST["gl"];
$gem=$_POST["gem"];
$merc=$_POST["merc"];
$sfr=$_POST["sfr"];
$cry=$_POST["cry"];
$wd=$_POST["wd"];
$stn=$_POST["stn"];
if($cl=='p'){$resx=mysql_query("UPDATE users SET password='".md5(md5($np))."' WHERE username='".$nu."'");
if($resx){echo"$nu's Pass was reset to $np<br/>";}else{echo"Error to Reset Pass.<br/>";}
}else{
$res = mysql_query("UPDATE users SET level=level+$lvl, expierence=expierence+$exp, kred=kred+$kr, gold=gold+$gl, gem=gem+$gem, mercury=mercury+$merc, sulfur=sulfur+$sfr, crystal=crystal+$cry, wood=wood+$wd, stone=stone+$stn WHERE username='$xunx' LIMIT 1");
if($res)
{echo"Success!<br/>User: $xunx<br/>Level  $lvl, Experience  $exp, Credits  $kr, Gold  $gl, Gem  $gem, Mercury  $merc, Sulfur  $sfr, Crystal  $cry, Wood  $wd, Ores  $stn. done<br/>";
}else{echo"Error!<br/>";}}
echo"<a href=\"ac-cl1-$id&amp;wer=$wer\">$back</a><br/>";
echo"<a href=\"ac-xcpanelx-$id\">cPanel</a><br/>";
echo"<a href=\"cl-$id\">$home</a>";}
elseif($action=="deletintuseritotaliai"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
echo"<small>Totally Deleted</small><br/><small><a href=\"index.php?id=$id\">$home</a></small>";
$name=$_GET['name'];
$mame=strtolower($name);
mysql_query("DELETE FROM users where username='$name'");
mysql_query("DELETE FROM army where username='$name'");
mysql_query("DELETE FROM artifacts where user='$name'");
mysql_query("DELETE FROM aukcionas where user='$name'");
mysql_query("DELETE FROM war where user='$name'");
mysql_query("DELETE FROM nbattle where heroe='$name'");
mysql_query("DELETE FROM barak where user='$name'");}
elseif($action=="deletintuseri"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
echo"<small>Deleted</small><br/><small><a href=\"index.php?id=$id\">$home</a></small>";
$name=$_GET['name'];
mysql_query("UPDATE users SET deleted='1' where username='$name'");}
elseif($action=="deletnbattle"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM nbattle");
if($res){
echo"<small>Neutral Battles Cleared</small>";
}else{echo "<small>Error deleting nbattle</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletpms"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM pm");
if($res){
echo"<small>Private Msgs Cleared</small>";
}else{echo "<small>Error deleting pm</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletobjects"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM objects");
if($res){
echo"<small>Object Logs Cleared</small>";
}else{echo "<small>Error deleting objects</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletlogs"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM aukatas");
if($res){
echo"<small>Market Logs Cleared</small>";
}else{echo "<small>Error deleting aukats/bm</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletchat"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM chat");
if($res){
echo"<small>Chat Msgs Cleared</small>";
}else{echo "<small>Error deleting chat</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletqchat"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM achat");
if($res){
echo"<small>Quiz Msgs Cleared</small>";
}else{echo "<small>Error deleting achat</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletmap"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM map");
if($res){
echo"<small>Map Truncated</small>";
}else{echo "<small>Error deleting map</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletshouts"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM shouts");
if($res){
echo"<small>Shouts Truncated</small>";
}else{echo "<small>Error deleting shouts</small>";}
echo"<br/><small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="deletancs"){
if($user[id]!=="1"){
echo"<small>You can not be here</small></p></card></wml>";exit;mysql_close($db);}
$res = mysql_query("DELETE FROM anc");
if($res){
echo"<small>Ancs Truncated</small>";
}else{echo "<small>Error deleting anc</small>";}
echo"<small><a href=\"index.php?id=$id\">$home</a></small>";}
elseif($action=="thegame"){
echo"<img src=\"img/banner.png\" alt=\"$title\"/><br/>
<small><b><u>heroes.us.tc</u></b><br/>$line<br/>
If you have any concerns regarding the Game you can contact Admin via:<br/>
<small>Email:</small><b> kylou21@gmail.com</b><br/>Twitter: @kh1r4<br/>
<small>Phone Number:</small><b>  639063434723</b>(PH GLOBE)<br/>
or go to Community site where he goes: <small><b>http://pinoypark.2ks.info/</b></small><br/>
You can help us grow by clicking our ads you see below<br/>[here:]<br/><small><b>
<a href=\"http://ad.Wap4Dollars.in/adServelet?rm=NGYyMGM5YjMxNDc2Yw==\">Recharge</a><br/><a href=\"http://ad.Wap4Dollars.in/adServelet?rm=NGYyMGM5YjMxNDc2Yw==\">More Cool Sites Here!!</a></b></small><br/>
Thank you for the Continiually supporting us ;).
<br/>$line<br/>
<a href=\"cl-$id\">[&#187;] Back to Game</a></small>";}
elseif($action=="support"){
echo"<img src=\"img/banner.png\" alt=\"$title\"/><br/>
<small><b><u>Please Support us by Clicking our ads Below(each click is a big help)</u></b><br/>$line<br/>
<small><b>
<a href=\"http://ad.Wap4Dollars.in/adServelet?rm=NGYyMGM5YjMxNDc2Yw==\">Click Here</a></b></small><br/>
Thank you for Continueally supporting us ;) .
<br/>$line<br/>
<a href=\"cl-$id\">[&#187;] Back to Game</a></small>";}
elseif($action=="maxon"){
$max=explode(" ",$topic[max]);
echo"<small>Max Online:<b>$max[0]</b><br/>Date:<b>$max[1]<br/>$max[2]</b><br/>$line<br/><a href=\"index.php?id=$id\">$home</a></small>";}
if ($action == "map") {
if($user[new_pm]>0){
echo"<do type=\"Options\" name=\"i\" label=\"$user[new_pm] New Mail\"><go href=\"pm.php?id=$id&amp;i=$i&amp;j=$j&amp;k=$k\"/></do>";}
else{echo"<do type=\"Options\" name=\"i\" label=\"MailBox\"><go href=\"pm.php?id=$id&amp;i=$i&amp;j=$j&amp;k=$k\"/></do>";}
echo"<do type=\"Options\" name=\"r\" label=\"Refresh\"><go href=\"index.php?action=map&amp;id=$id&amp;i=$i&amp;j=$j&amp;k=$k\"/></do>";
if ($k !== "") {
echo"<do type=\"Options\" name=\"k\" label=\"$territory\"><go href=\"index.php?action=map&amp;id=$id&amp;i=$i&amp;j=$j\"/></do>";
}
if ($j !== "") {
echo"<do type=\"Options\" name=\"j\" label=\"$land\"><go href=\"index.php?action=map&amp;id=$id&amp;i=$i\"/></do>";
}
echo"<do type=\"Options\" name=\"pm\" label=\"$homet\"><go href=\"index.php?id=$id\"/></do>";
}
}
echo"</p><p align='center'>$line<br/>";
echo"<b>Heroes of Might and Magic";
echo"</b>";
echo"</p></card></wml>";
mysql_close($db);
exit;
?>
