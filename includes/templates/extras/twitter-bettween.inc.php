<?php
/**
 * Agrega un bloque de twitter de conversación
 */
$twPeople1 = '';
$twPeople2 = '';
$twDate1 = date('M-d-Y', time()-(60*60*24*365));
$twDate2 = date('M-d-Y');
 
$urlBtw = 'http://bettween.com:80/conversations/embed?';
$urlBtw.= 'user1=@'.$twPeople1;
$urlBtw.= '&user2=@'.$twPeople1;
$urlBtw.= '&date1='.$twDate1.'&date2='.$twDate2;
$urlBtw.= '&order=asc';
$urlBtw.= '&mainBackgroundColor=00457D';
$urlBtw.= '&headerFooterColor=FFFFFF';
$urlBtw.= '&borderColor=D8D8D8';
$urlBtw.= '&tweetColor=333333';
$urlBtw.= '&tweetBackgroundColor=FFFFFF';
$urlBtw.= '&tweetDetailColor=999999';
$urlBtw.= '&detailColor=333333';
$urlBtw.= '&detailBackgroundColor=F0F0F0';
$urlBtw.= '&fontSize=12';
$urlBtw.= '&width=600';
$urlBtw.= '&height=250';
?>

<div class="twitter-bettween">
	<h4>Twitter de búsqueda</h4>
	<iframe src="<?php echo $urlBtw;?>" frameborder="0" framespacing="0" scrolling="no" width="600" height="370" border="0"></iframe>

</div>