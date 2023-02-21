<?php

    echo get_header();

if (isset($_POST['generator_submit']) && $_POST['urls'] != "") {


            $user_id = get_current_user_id();
            $merge_name = $_POST['merge_name'];
            $url = $_POST['urls'];
            $chanel_title = $_POST['chanel_title'];
            $chanel_link = $_POST['chanel_link'];
            $chanel_language = $_POST['chanel_language'];
            $chanel_copyright = $_POST['chanel_copyright'];
            $chanel_description = $_POST['chanel_description'];
            $chanel_image = $_POST['chanel_image'];
            $chanel_explicit = $_POST['chanel_explicit'];
            $chanel_type = $_POST['chanel_type'];
            $chanel_subtitle = $_POST['chanel_subtitle'];
            $chanel_author = $_POST['chanel_author'];
            $chanel_summary = $_POST['chanel_summary'];
            // $chanel_owner = $_POST['chanel_owner'];
            $chanel_name = $_POST['chanel_name'];
            $chanel_email = $_POST['chanel_email'];
            $chanel_i_image = $_POST['chanel_i_image'];
            $chanel_categories = $_POST['chanel_categories'];
            $imp_url = explode(PHP_EOL,$url);


if(count($imp_url) > 50){
        echo "<div class='error_msg'>maximum number of feeds is 50</div>";
    }else{

 $invalidurl = false;$html_item = '';
 for ($i=0; $i <= count($imp_url); $i++) { 

    
    if(strpos($imp_url[$i], "independentpodcast.network")!==false){
        continue;
    }
        
        
        if(@simplexml_load_file(trim($imp_url[$i]))){
      $feeds = simplexml_load_file(trim($imp_url[$i]));



      global $wpdb;

        $details = $wpdb->get_row("SELECT * FROM rss_feed_url WHERE feed_url = '".$imp_url[$i]."' AND user_id = '".$user_id."' AND merge_name = '".$merge_name."';");

        if($details->id)
        {
        
        $existing_data = file_get_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$details->id.'.xml');

        $str_len = strlen($data);

        $current_data = file_get_contents(trim($imp_url[$i]));

        $str_len2 = strlen($current_data);      

        if($str_len != $str_len2){

            $updatedata = file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$details->id.'.xml', $current_data);
            
        }else if($str_len == $str_len2){
            continue;
        }

        }else{

        $query = "INSERT INTO rss_feed_url (feed_url,user_id,chanel_title,chanel_link,chanel_language,chanel_copyright,chanel_description,chanel_image,chanel_explicit,chanel_type,chanel_subtitle,chanel_author,chanel_summary,chanel_owner,chanel_name,chanel_email,chanel_i_image,chanel_categories,merge_name) VALUES ('".$imp_url[$i]."','".$user_id."','".$chanel_title."','".$chanel_link."','".$chanel_language."','".$chanel_copyright."','".$chanel_description."','".$chanel_image."','".$chanel_explicit."','".$chanel_type."','".$chanel_subtitle."','".$chanel_author."','".$chanel_summary."',' ','".$chanel_name."','".$chanel_email."','".$chanel_i_image."','".$chanel_categories."','".$merge_name."')";

    $sql = $wpdb->query($query);

    $inserted_id = $wpdb->insert_id;
    $inserted_ids[] = $wpdb->insert_id;

    $newdata = file_get_contents(trim($imp_url[$i]));

    file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/data/'.$inserted_id.'.xml', $newdata);

}
if($it == '') $it = 0;


      foreach ($feeds->channel->item as $items) {
           $postDate = $items->pubDate;
   $pubDate = date('D, d M Y',strtotime($postDate));
        $item_data[] = $items;
    $it++;
   }
     }

 }


 function date_sort($a, $b) {
    return strtotime($a->pubDate) - strtotime($b->pubDate);
}
usort($item_data, "date_sort");

$rev_item_data = array_reverse($item_data);

if ($channel_data == "") {
    $channel_data .= '<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">';
      $channel_data .= '<channel>
<atom:link href="'.$chanel_link.'" rel="self" type="application/rss+xml"/>
<title>'.$chanel_title.'</title>
<link>'.$chanel_link.'</link>
<language>'.$chanel_language.'</language>
<copyright>'.$chanel_copyright.'</copyright>
<description>'.stripslashes($chanel_description).'</description>
<image>
<url>'.$chanel_image.'</url>
<title>'.$chanel_title.'</title>
<link>'.$chanel_link.'</link>
</image>
<itunes:explicit>'.$chanel_explicit.'</itunes:explicit>
<itunes:type>'.$chanel_type.'</itunes:type>
<itunes:subtitle>'.$chanel_subtitle.'</itunes:subtitle>
<itunes:author>'.$chanel_author.'</itunes:author>
<itunes:summary>'.stripslashes($chanel_summary).'</itunes:summary>
<itunes:owner>
<itunes:name>'.$chanel_name.'</itunes:name>
<itunes:email>'.$chanel_email .'</itunes:email>
</itunes:owner>
<itunes:image href="'.$chanel_i_image.'"/>
<itunes:category text="'.$chanel_categories .'"> </itunes:category>';

$html_item .= $channel_data; 
}


foreach ($rev_item_data as $rev_item) {
   $episode = count($rev_item);
   $title = $rev_item->title;
   $link = $rev_item->link;
   $url=get_encloser($rev_item->enclosure);  
   $description = $rev_item->description;
   $postDate = $rev_item->pubDate;
   $pubDate = date('D, d M Y H:i:s',strtotime($postDate));
   $guid = $rev_item->guid;
$html_item .= '<item>
<title>'.$title.'</title>
<link>'.$link.'</link>
<description>'.$description.'</description>
<pubDate>'.$pubDate.'</pubDate>
<itunes:title xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$title.'</itunes:title>
<itunes:episodeType xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">full</itunes:episodeType>
<itunes:episode xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$i.'</itunes:episode>
<itunes:author xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">New Mommy Media | Independent Podcast Network</itunes:author>
<itunes:subtitle xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$description.'</itunes:subtitle>
<itunes:summary xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">'.$description.'</itunes:summary>
<content:encoded><p>'.$description.'</p></content:encoded>
<enclosure url="'.$rev_item->enclosure->attributes['url'].'" type="'.$rev_item->enclosure->attributes['type'].'"/>
<guid isPermaLink="false">'.$guid.'</guid>
<enclosure url="'.$url.'" length="0" type="audio/mpeg"/>
</item>';

}


 $html_item .= '</channel>
</rss>';    


$html_item = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $html_item);
//echo htmlentities($html_item);
$replaced = str_replace(' ', '-', $merge_name);
if (!file_exists(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml')) {
    mkdir(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/', 0777, true);
}
 file_put_contents(ABSPATH.'wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml', $html_item);
 $jemi = get_site_url().'/wp-content/plugins/ITF-RSS-feed-generator/user/'.$user_id.'/'.$replaced.'/single_file.xml';

// print_r($inserted_ids);exit;
$jkons = implode(',', $inserted_ids); 

$query = "UPDATE  rss_feed_url SET merge_url = '".$jemi."' WHERE id IN (".$jkons.");";


    $sql = $wpdb->query($query);

}
        }

        ?>
<style>
.chanl_info .form-group label, .chanl_info .form-group input {
    width: 40% !important;
    display: inline-block;
    position: relative;
}

.chanl_info .form-group label {
    width: 20% !important;
    font-size: 18px;
    font-weight: 500;
}

.chanl_info {
    border: 1px solid #0000002b;
    border-radius: 4px;
    padding: 20px;
    box-shadow: 0 0 5px 3px #0000002b;
    margin: 20px 0;
}

.chanl_info .form-group {
    margin: 6px 0;
}

.chanl_info h3 {
    text-align: center;
    margin-bottom: 30px;
    font-size: 30px;
}


label.form-control {
    font-size: 17px;
    text-align: left !important;
    margin: 20px 0;
    display: inline-block;
    margin-right: 20px;
}

.generator_frm h1 {
    text-align: center;
}

input.merge_name {
    width: 40% !important;
    display: inline-block;
    border: 1px solid #000;
}

input.generator_submit {
}
.generator_submit input[type="submit"]:hover {
    border: 2px solid #174de7ad;
    box-shadow: 0 0 4px 1px #174de7ad;
}

.generator_frm + a {
    margin-top: 10px;
    font-size: 15px;
}

.generator_frm {
    margin-bottom: 10px;
}
.labl_form input[type="submit"] {
    display: block;
    padding: 6px;
    margin-top: 10px;
    background-color: #174de7;
    color: #fff;
    border: 1px solid;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
}
.generator_frm {
    width: 90%;
    margin: 0 auto;
    display: block;
    text-align: left;
}

.generator_frm textarea {
    border: 1px solid #000;
    border-radius: 3px;
    font-size: 22px;
    width: 100%;
    margin-top: 20px;
    padding: 20px;
    height: 120px;
    resize: none;
}

.generator_frm input.generator_submit {
}

.rss_feed_generator a {
    width: 90%;
    margin: 20px auto 20px;
    display: block;
}
.sucs_msg {
    font-size: 18px;
    padding: 15px;
    color: green;
}
.merge_url_cls{
    font-size: 18px;
    padding: 15px;
}
</style>
<div class="rss_feed_generator">
    <div class="generator_frm">
            <h1>RSS Feed Generator</h1>
        <form method="post" action="" name="rss_generator">
             <?php 
                if($_POST['urls']){
                   ?>
               <div class="sucs_msg">Successfully generated with the new RSS feed Kindly find below link</div>
               <?php $replaced = str_replace(' ', '-', $merge_name); ?>
               <a class="merge_url_cls" target="_blank" href="<?php echo get_site_url().'/feed/'.$replaced; ?>">Click to open Generated XML file</a>
              <?php } ?>
            <div class="labl_form">
                <div class="chanl_info">
                    <h3>Channel Info</h3>
    <div class="form-group">
        <label for="chanel_title">Title</label>
        <input type="text" name="chanel_title" class="form-control" id="chanel_title" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_link">Link</label>
        <input type="text" name="chanel_link" class="form-control" id="chanel_link" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_language">Language</label>
        <input type="text" name="chanel_language" class="form-control" id="chanel_language" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_copyright">Copyright</label>
        <input type="text" name="chanel_copyright" class="form-control" id="chanel_copyright" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_description">Description</label>
        <input type="text" name="chanel_description" class="form-control" id="chanel_description" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_image">Image</label>
        <input type="text" name="chanel_image" class="form-control" id="chanel_image" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_explicit">iTunes: explicit</label>
        <input type="text" name="chanel_explicit" class="form-control" id="chanel_explicit" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_type">iTunes:type</label>
        <input type="text" name="chanel_type" class="form-control" id="chanel_type" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_subtitle">iTunes: subtitle</label>
        <input type="text" name="chanel_subtitle" class="form-control" id="chanel_subtitle" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_author">iTunes author</label>
        <input type="text" name="chanel_author" class="form-control" id="chanel_author" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_summary">iTunes summary</label>
        <input type="text" name="chanel_summary" class="form-control" id="chanel_summary" placeholder="">
    </div>
    <!-- <div class="form-group">
        <label for="chanel_owner">iTunes owner</label>
        <input type="text" name="chanel_owner" class="form-control" id="chanel_owner" placeholder="">
    </div> -->
    <div class="form-group">
        <label for="chanel_name">iTunes name</label>
        <input type="text" name="chanel_name" class="form-control" id="chanel_name" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_email">iTunes email</label>
        <input type="text" name="chanel_email" class="form-control" id="chanel_email" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_i_image">iTunes image</label>
        <input type="text" name="chanel_i_image" class="form-control" id="chanel_i_image" placeholder="">
    </div>
    <div class="form-group">
        <label for="chanel_categories">iTunes categories</label>
        <input type="text" name="chanel_categories" class="form-control" id="chanel_categories" placeholder="">
    </div>
                </div>
                <textarea class="form-control spacer10 fix-form-width" name="urls" rows="30" cols="200"></textarea>
                <label class="form-control">Merge name</label>
                <input type="text" name="merge_name" class="merge_name">
                <input class="generator_submit" type="submit" name="generator_submit" value="Generate">
            </div>
        </form>
        </div>
</div>
<?php

echo get_footer();