<?php
// begin
isset($_GET['magnet']) or die(convertFalse());
$magnet_url = urldecode($_GET['magnet']);
$magnetHead = substr($magnet_url, 0 ,8);
if($magnetHead !== 'magnet:?') {
	convertFalse();	// convert failed
	return;
}
$pos_of_xt = strpos($magnet_url, 'xt');
if ($pos_of_xt === false) {
	convertFalse();	// convert failed
	return;
}
$pos_of_and = strpos($magnet_url, '&', $pos_of_xt);
if ($pos_of_and === false) {// not find '&' after 'xt'
	$pos_of_maohao = strrpos($magnet_url, ':', $pos_of_xt);
	if ($pos_of_maohao === false){
		convertFalse();
		return;
	}
	$hashEncode = substr($magnet_url, $pos_of_maohao+1); 
	$url = toTorrent($hashEncode);
	if (strlen($hashEncode) != 40)
	{
		convertFalse();
		return;
	}
	convertOk($url);
	return;
}
else{
	$offset = strlen($magnet_url) - ($pos_of_and+1);
	$offset = 0 - $offset;
	$pos_of_maohao = strrpos($magnet_url, ':', $offset);
	if ($pos_of_maohao === false){
		convertFalse();
		return;
	}
	$hashLen = $pos_of_and - $pos_of_maohao - 1;
	$hashEncode = substr($magnet_url, $pos_of_maohao+1, $hashLen);
	if (strlen($hashEncode) != 40)
	{
		convertFalse();
		return;
	}
	$url = toTorrent($hashEncode);
	convertOk($url);
	return;
}
// end

function toTorrent(&$hashEncode)
{
	$hashEncode = strtoupper($hashEncode);
	$forTorrent = 'https://itorrents.org/torrent/'.$hashEncode.'.torrent'; 
	return $forTorrent;
}

function convertOk(&$url){
	$arr = array('result'=>1, 'url'=>$url);
	$json_return = json_encode($arr);
	echo $json_return;
}

function convertFalse(){
	$arr = array('result'=>0, 'url'=>null);
	$json_return = json_encode($arr);
	echo $json_return;
}
?>
