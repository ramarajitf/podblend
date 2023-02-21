<?php
$data = $_REQUEST['data'];
$exdata = explode('_',$data);
global $wpdb;
$details = $wpdb->get_row("SELECT merge_url FROM rss_feed_url WHERE user_id = '".$exdata[0]."' AND merge_name = '".urldecode($exdata[1])."' ;");

header('Content-type: text/xml');
readfile(trim($details->merge_url));
exit;

?>