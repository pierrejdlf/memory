<? header('Content-Type: text/html; charset=utf-8'); ?>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Karla' rel='stylesheet' type='text/css'>
	<style type="text/css">
	body {	background:#F3F3F3;
			font-size:0.6em;
			font-family: 'Karla', sans-serif;}
	.day.even {	background:white;}
	.daytitle {	text-align:left;
				font-size:1.9em;
				padding:5px;
				border-top:4px gray solid;}
	.col {	width:32%;
			display:inline-block;
			/*border:1px solid lightgray;*/
			margin:5px;
			padding:0px;
			vertical-align: top;}
	.event, .notts {	width:50%;
						margin:5px;
						padding:0px;}
	.notts { border:1px solid black;}
	/*------------------------------*/
	.col div, .event div, .notts div {	padding:3px;}
	.event div {	background:#F3DFD6;}
	.notts div {	background:#D4ECE0;}
	.in {	background:#FFFFD8;}
	.out {	background:#D4E5FF;}
	.out .who {	color:gray;}
	.out.CHAT .time { color:transparent;}
	.CHAT {	border-left:6px solid #A7D88F;}
	.SMS {	border-left:6px solid #B1BFF3;}
	.time {	float:left;
			color:gray;
			margin-right:5px;}
	.who {	float:right;
			color:black;
			margin-left:5px;}
	/*------------------------------*/
	</style>
</head>
<body>
<?php
function myCompanyMotto(){
}


$row = 1;
setlocale(LC_ALL, 'fr_FR.UTF-8');

$curDate=$date;
$chat='';
$sms='';
$notts='';
$event='';
	
if (($handle = fopen("../CSV/MERGED.csv", "r")) !== FALSE) {
	$curDate = "";
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$date		= $data[0];
		$time 		= substr($data[1],0,5);
		$media 		= $data[2];
		$type 		= $data[3];
		$from		= $data[4];
		$to 		= $data[5];
		$content 	= $data[6];
		
		if ($media=='CHAT' && strlen($content)>800) $content=substr($content,0,800).'---[TRUNCATED]';
		if ($media=='NOTTS') $content=nl2br($content); //replace \n by <br/>
		
		if ($curDate!=$date) {
			$row +=1;
			
			// flush current queue
			$day='';
			$chatsplitted = split('</div>',$chat);
			$length = count($chatsplitted);
			// if CHAT is too long, we split it to fill the 3 columns !
			if($length>14) {
				$part = (int)($length/3);
				//$a = array_slice($chatsplitted,0,$part) ;
				$a = join('</div>',array_slice($chatsplitted,0,$part)).'</div>';
				$b = join('</div>',array_slice($chatsplitted,$part,$part)).'</div>';
				$c = join('</div>',array_slice($chatsplitted,2*$part,$length-2*$part)).'</div>';
				$day .= '<div class="col">'.$a.'</div>';
				$day .= '<div class="col">'.$b.'</div>';
				$day .= '<div class="col">'.$c.'</div>';
			} else {
				if($event!='') $day .= '<div class="event">'.$event.'</div>';
				if($notts!='') $day .= '<div class="notts">'.$notts.'</div>';
				if($chat!='') $day .= '<div class="col">'.$chat.'</div>';
			}

			$daytitle = '<div class="daytitle">'.$curDate.'</div>';
			$altcl = '';
			if ($row%2==0) $altcl="even";
			echo '<div class="day '.$altcl.'">'.$daytitle.$day.'</div>';
			
			// clean for incoming next values
			$curDate=$date;
			$chat='';
			//$sms='';
			$notts='';
			$event='';
		}
		
		$na='Iris';
		//if ($from==$na || $to==$na) { 
		if (1==1){
			// filling contents in $event / $notts / $chat
			if ($media=='CHAT' || $media=='SMS') {
				$t = '<span class="time">'.$time.'</span>';
				$qui = $from;
				if ($type=='out' && $media=='CHAT') $qui= $to;
				$w = '<span class="who">'.$qui.'</span>';
				$chat .= '<div class="'.$media.' '.$type.'">'.$t.$w.$content.'</div>';
			}
			if ($media=='NOTTS') {
				$notts .= '<div>'.$content.'</div>';
			}
			if ($media=='EVENT') {
				$event .= '<div>'.$content.'</div>';
			}
		}
	}
	fclose($handle);
}
?>
</body>
</html>