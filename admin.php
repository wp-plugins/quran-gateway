<?php
/*
 Plugin Name: Quran Gateway
 Plugin URI: http://www.islam.com.kw
 Description: Quran Gateway plugin allows you to display the Quran or its translation in different languages either verse by verse or whole surah along with audio streaming.
 Version: 1.0
 Author: EDC Team (E-Da`wah Committee)
 Author URI: http://www.islam.com.kw
 License: It is Free -_-
*/
include('setting.php');
include('files/ayat.php');

if(!isset($_GET['language_id']) && !isset($_GET['sora_id'])){
	
	if(get_option('quran_gateway_id') == 1){ 
	include_once('files/English_Sahih_International.php');
	}elseif(get_option('quran_gateway_id') == 2){
	include_once('files/French.php');
	}elseif(get_option('quran_gateway_id') == 3){
	include_once('files/German.php');
	}else{
	include_once('files/English_Sahih_International.php');
	}

}

function get_quran_languages($language_id=0){
$l[1] = array('English', 'Sahih International', '');
$l[2] = array('French', 'French', '');
$l[3] = array('German', 'German', '');
return $l[$language_id];
}

function get_quran_gateway($dashboard=0){
global $QURAN, $TRANSLATE, $sora_ar, $sora_en, $ar_mp3_files, $en_mp3_files;

if(isset($_GET['language_id']) && isset($_GET['sora_id'])){
$code = '';
}else{
	
if(get_option('quran_gateway_view') == 1 || is_admin() == 1){

if(get_option('quran_gateway_allow_by_sora')==1){
$random_sora = get_option('quran_gateway_by_sora');
$random_aya = rand(1, count($QURAN[$random_sora]));
}else{
$random_sora = rand(1, 114);
$random_aya = rand(1, count($QURAN[$random_sora]));
}

$sora_number = strlen($random_sora);
$aya_number = strlen($random_aya);

if($sora_number==1){
	$s = '00'.$random_sora;
}elseif($sora_number==2){
	$s = '0'.$random_sora;
}elseif($sora_number==3){
	$s = $random_sora;
}

if($aya_number==1){
	$a = '00'.$random_aya;
}elseif($aya_number==2){
	$a = '0'.$random_aya;
}elseif($aya_number==3){
	$a = $random_aya;
}
	
$sound_file_ar = ''.$ar_mp3_files.'/'.$s.''.$a.'.mp3';

if(get_option('quran_gateway_id') == 1){
$sound_file = ''.$en_mp3_files.'/'.$s.''.$a.'.mp3';
$sound_file_en = ' onclick="changesoundx(\''.$sound_file.'\');"';
$listen = '<img onclick="changesoundx(\''.$sound_file.'\');" src="'.trailingslashit(plugins_url(null,__FILE__)).'/i/listen.png" alt="Listen" />';
}else{
$sound_file_en = '';
$listen = '';
}

$quran_gateway_hidden_text_ar = get_option( 'quran_gateway_hidden_text_ar' );
$quran_gateway_hidden_text_en = get_option( 'quran_gateway_hidden_text_en' );
$quran_gateway_hidden_player = get_option( 'quran_gateway_hidden_player' );
$quran_gateway_hidden_file_download = get_option( 'quran_gateway_hidden_file_download' );
$quran_gateway_border = get_option( 'quran_gateway_border' );
$quran_gateway_background_color = get_option( 'quran_gateway_background_color' );
$quran_gateway_border_color = get_option( 'quran_gateway_border_color' );
$quran_gateway_padding = get_option( 'quran_gateway_padding' );
$quran_gateway_margin = get_option( 'quran_gateway_margin' );
$quran_gateway_margin_between = get_option( 'quran_gateway_margin_between' );

if(empty($quran_gateway_padding)){
$padding = '';
}else{
$padding = 'padding:'.$quran_gateway_padding.'px;';
}

if(empty($quran_gateway_margin)){
$margin = '';
}else{
$margin = 'margin:'.$quran_gateway_margin.'px;';
}

if($quran_gateway_margin_between == ""){
$margin_between = '';
}else{
$margin_between = '<div style="margin-top:'.$quran_gateway_margin_between.'px;"></div>';
}

if($quran_gateway_background_color == ""){
$background_color = '';
}else{
$background_color = 'background-color:'.$quran_gateway_background_color.';';
}

if($quran_gateway_border > 0){
	if($quran_gateway_border_color == ""){
			$border = '';
	}else{
			$border = 'border:'.$quran_gateway_border.'px solid '.$quran_gateway_border_color.';';
	}
}else{
	$border = '';
}

if($quran_gateway_background_color == "" && $border == "" && $padding == "" && $margin == ""){
$style = '';
}else{
$style = ' style="'.$border.' '.$background_color.''.$padding.''.$margin.'"';
}

$code = '';

if($quran_gateway_hidden_player == 1){
$get_sound_ar = '';
$get_sound_en = '';
}else{
if($quran_gateway_hidden_file_download == 1){
$d = '';
}else{
$d = '<br /><a href="\'+ID+\'">Download this file</a>';
}

$code .= "<script type=\"text/javascript\">
  function changesoundx(ID) {
    var sound_id = document.getElementById(\"quran_player\");
    sound_id.innerHTML = '<div style=\"margin-top:15px;\"><audio controls autoplay><source src=\"'+ID+'\" type=\"audio/mpeg\">Your browser does not support the audio element.</audio>".$d."</div>';
  }
</script>";
$get_sound_ar = '<img onclick="changesoundx(\''.$sound_file_ar.'\');" src="'.trailingslashit(plugins_url(null,__FILE__)).'/i/listen_ar.png" alt="Listen" />';
$get_sound_en = $listen;
}

$code .= '<div class="quran_gateway"'.$style.'>';
if($quran_gateway_hidden_text_ar != 1){
$code .= '<div class="aya_ar">'.$QURAN[$random_sora][$random_aya].' <span class="sora_name">'.$sora_ar[$random_sora].' ['.$random_aya.']</span> '.$get_sound_ar.'</div>';
$code .= $margin_between;
}

if($quran_gateway_hidden_text_en != 1){
$code .= '<div class="aya_en">'.$TRANSLATE[$random_sora][$random_aya].' <span class="sora_name">'.$sora_en[$random_sora].' ['.$random_aya.']</span> '.$get_sound_en.'</div>';
}
if($quran_gateway_hidden_player != 1){
$code .= '<div id="quran_player"></div>';
}

$code .= '</div>';

}else{
$code = '';
}
}
return $code;
}
add_action( 'get_header', 'get_quran_gateway' );

















function get_quran_by_sora($sora=0, $breadcrumb="", $l=1){
global $QURAN, $TRANSLATE, $sora_ar, $sora_en, $ar_mp3_files, $en_mp3_files;

if(isset($_GET['language_id']) && $_GET['language_id'] != 0){
$language_id = intval($_GET['language_id']);
}else{
$language_id = $l;
}

if($language_id == 1){ 
	include('files/English_Sahih_International.php');
}elseif($language_id == 2){
	include('files/French.php');
}elseif($language_id == 3){
	include('files/German.php');
}else{
	include('files/English_Sahih_International.php');
}

if($sora > 0 && $sora < 115){
$sora_id = $sora;
}else{
$sora_id = 1;
}

$sora_count = count($QURAN[$sora_id]);
$sora_number = strlen($sora_id);

if($sora_number==1){
	$s = '00'.$sora_id;
}elseif($sora_number==2){
	$s = '0'.$sora_id;
}elseif($sora_number==3){
	$s = $sora_id;
}

$code = '';
$d = '<br /><a href="\'+ID+\'">Download this file</a>';
$code .= "<script type=\"text/javascript\">
  function changesoundy(ID) {
    var sound_id = document.getElementById(\"quran_player_by_sora\");
    sound_id.innerHTML = '<div style=\"margin-top:15px;\"><audio controls autoplay><source src=\"'+ID+'\" type=\"audio/mpeg\">Your browser does not support the audio element.</audio>".$d."</div>';
  }
</script>";
$code .= '<h2 class="sora_title">'.$breadcrumb.''.$sora_en[$sora_id].' - '.$sora_ar[$sora_id].'</h2>';
if(is_admin()){
$code .= '<div class="sora_short_code">Shortcode: <code>get_sorah['.$sora_id.']</code></div>';
}
$code .= '<div id="quran_player_by_sora"></div>';
for($i=1; $i<=$sora_count; ++$i){
$aya_id = $i;

$aya_number = strlen($aya_id);

if($aya_number==1){
	$a = '00'.$aya_id;
}elseif($aya_number==2){
	$a = '0'.$aya_id;
}elseif($aya_number==3){
	$a = $aya_id;
}

$sound_file_ar = ''.$ar_mp3_files.'/'.$s.''.$a.'.mp3';

if(get_option('quran_gateway_id') == 1){
if($language_id == 1){
$sound_file = ''.$en_mp3_files.'/'.$s.''.$a.'.mp3';
$sound_file_en = ' onclick="changesoundy(\''.$sound_file.'\');"';
}else{
$sound_file_en = '';
}
}else{
$sound_file_en = '';
}

$get_sound_ar = ' onclick="changesoundy(\''.$sound_file_ar.'\');"';
$get_sound_en = $sound_file_en;

$code .= '<div class="quran_gateway_by_sora">';
$code .= '<div class="aya_ar_by_sora"'.$get_sound_ar.'>'.$QURAN[$sora_id][$aya_id].' <span class="sora_name_by_sora">'.$sora_ar[$sora_id].' ['.$aya_id.']</span></div>';
$code .= '<div class="aya_en_by_sora"'.$get_sound_en.'>'.$TRANSLATE[$sora_id][$aya_id].' <span class="sora_name_by_sora">'.$sora_en[$sora_id].' ['.$aya_id.']</span></div>';
$code .= '</div>';
}

return $code;
}

function quran_by_sora_replace($text){
$text = preg_replace('/get_sorah\[([0-9]*?)\]/e','get_quran_by_sora(\\1)',$text);
return $text;
}
 
add_filter('the_content','quran_by_sora_replace');

function quran_by_sora_and_language_replace($text){
$text = preg_replace('/get_sorah\[([0-9]*?),([0-9]*?)\]/e','get_quran_by_sora(\\1,"",\\2)',$text);
return $text;
}
 
add_filter('the_content','quran_by_sora_and_language_replace');

function get_quran_all_sora($language=0){
global $post, $sora_ar, $sora_en;
$ID = $post->ID;
$permalink = post_permalink( $ID );
//strip_tags()

if(isset($_GET['language_id']) && $_GET['language_id'] != 0){
$language_id = intval($_GET['language_id']);
}else{
$language_id = intval($language);
}

if(isset($_GET['sora_id']) && $_GET['sora_id'] != 0){
$sora_id = intval($_GET['sora_id']);
}else{
$sora_id = 1;
}
if(isset($_GET['sora_id']) && $_GET['sora_id'] != 0){
$breadcrumb = '<a href="'.$permalink.'">The Noble Qur\'an</a> &raquo; ';
$code = get_quran_by_sora($sora_id, $breadcrumb);
}else{
$code = '<div class="quran_gateway_sora_list">';
$code .= '<h2 class="sora_title">The Noble Qur\'an</h2>';
$code .= '<ul>';
for($i=1; $i<=114; ++$i){
$params = array( 'language_id' => intval($language_id), 'sora_id' => $i );
$postlink = add_query_arg( $params, $permalink );
$code .= '<li>'.$i.'. <a href="'.$postlink.'">'.$sora_en[$i].'</a></li>';
}
$code .= '</ul>';
$code .= '<div style="clear:both;"></div>';
$code .= '</div>';
}
return $code;
}

function quran_all_sora_replace($text){
$text = preg_replace('/get_all_sorah\[([0-9]*?)\]/e','get_quran_all_sora(\\1)',$text);
return $text;
}
 
add_filter('the_content','quran_all_sora_replace');














function Quran_Gateway_head() {
    $options = get_option( 'my-theme-options' );
    $color = $options['color'];
    echo "<style> h1 { color: $color; } </style>";
}
add_action( 'wp_head', 'Quran_Gateway_head' );

function Quran_Gateway_install(){
	add_option( 'quran_gateway_id', '1', '', 'yes' ); 
	add_option( 'quran_gateway_view', '1', '', 'yes' );
	add_option( 'quran_gateway_by_sora', '1', '', 'yes' );
	add_option( 'quran_gateway_allow_by_sora', '0', '', 'yes' );
	add_option( 'quran_gateway_hidden_text_ar', '0', '', 'yes' );
	add_option( 'quran_gateway_hidden_text_en', '0', '', 'yes' );
	add_option( 'quran_gateway_hidden_player', '0', '', 'yes' );
	add_option( 'quran_gateway_hidden_file_download', '0', '', 'yes' );
	add_option( 'quran_gateway_border', '1', '', 'yes' );
	add_option( 'quran_gateway_background_color', '#f2f2f2', '', 'yes' );
	add_option( 'quran_gateway_border_color', '#cccccc', '', 'yes' );
	add_option( 'quran_gateway_padding', '10', '', 'yes' );
	add_option( 'quran_gateway_margin', '10', '', 'yes' );
	add_option( 'quran_gateway_margin_between', '10', '', 'yes' );
}
register_activation_hook(__FILE__,'Quran_Gateway_install'); 

/*
add_action('wp_head','Quran_Gateway_add_font');
function Quran_Gateway_add_font(){
echo "<style type=\"text/css\" media=\"screen\">";
echo "@font-face { font-family: 'KFGQPC Uthman Taha Naskh'; src: url('".plugin_dir_url( __FILE__ )."fonts/UthmanTN1_Ver10/UthmanTN1_Ver10.otf'); font-weight: bold; }";
echo ".quran_gateway_widget { margin:0; border:0px solid #cccccc; padding:5px; }
.quran_gateway_widget div.aya_ar { direction:rtl; text-align:right; padding:10px 0 20px; margin:0 0 15px 0; border-bottom:0px dotted #cccccc; font-size:18px; }
.quran_gateway_widget div.aya_en { direction:ltr; text-align:left; margin:10px 0 0 0; font-size:16px; }
.quran_gateway_widget span.sora_name { font-size:14px; color:green; }

.quran_gateway { margin:10px; padding:10px; background-color:#f2f2f2; border:1px solid #cccccc; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
.aya_ar { font-family: 'KFGQPC Uthman Taha Naskh', Arial, Tahoma; font-size:28px; line-height:38px; direction:rtl; text-align:right; padding:0; margin:7px 0 15px 0; }
.aya_en { direction:ltr; text-align:left; margin:10px 0 0 0; font-size:16px; line-height:25px; }
.sora_name { font-family: 'KFGQPC Uthman Taha Naskh', Arial, Tahoma; font-size:17px; color:green; }

#quran_player { text-align:center; }";
echo "</style>";
}
*/

function Quran_Gateway_admin_style() {
	wp_register_style( 'quran-gateway-styles', plugin_dir_url( __FILE__ ).'style.css' );
	wp_enqueue_style( 'quran-gateway-styles' );
	
}
add_action('wp_enqueue_scripts', 'Quran_Gateway_admin_style');

function Quran_Gateway_add_style() {
	echo "<style type=\"text/css\" media=\"screen\">\n";
	echo "@font-face { font-family: 'KFGQPC Uthman Taha Naskh'; src: url(".plugin_dir_url( __FILE__ )."fonts/UthmanTN1_Ver10/UthmanTN1_Ver10.otf); font-weight: bold; }";
	echo ".quran_gateway { margin:0; padding:10px; background-color:#ffffff; border:0px solid #cccccc; }";
	echo ".aya_ar { font-family: 'KFGQPC Uthman Taha Naskh', Arial, Tahoma; font-size:25px; line-height:38px; direction:rtl; text-align:right; padding:10px 0 10px 0; margin:0 0 15px 0; border-bottom:0px dotted #cccccc; }";
	echo ".aya_en { direction:ltr; text-align:left; margin:10px 0 0 0; font-size:16px; line-height:25px; }";
	echo ".sora_name { font-family: 'KFGQPC Uthman Taha Naskh', Arial, Tahoma; font-size:25px; font-size:17px; color:green; }";
	echo ".quran_gateway_by_sora { margin:0 0 10px 0; padding:10px; background-color:#f2f2f2; border-bottom:1px dotted #cccccc; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }
.aya_ar_by_sora { font-family: 'KFGQPC Uthman Taha Naskh', Arial, Tahoma; font-size:28px; line-height:38px; direction:rtl; text-align:right; padding:0; margin:0 0 15px 0; }
.aya_en_by_sora { direction:ltr; text-align:left; margin:10px 0 0 0; font-size:16px; line-height:25px; }
.sora_name_by_sora { font-family: 'KFGQPC Uthman Taha Naskh', Arial, Tahoma; font-size:17px; color:green; }
#quran_player { text-align:center; margin-bottom:10px; }
#quran_player_by_sora { text-align:center; margin-bottom:10px; }
.sora_title { color:#2196F3; font-family: 'KFGQPC Uthman Taha Naskh', Arial, Tahoma; }
.sora_short_code { margin::0 0 15px 0; }
.sora_short_code code { color:#B71C1C; }

.quran_gateway_sora_list { padding: 0px; margin: 0px; }
.quran_gateway_sora_list ul { list-style-type: none; padding: 0px; margin: 0px; }
.quran_gateway_sora_list ul li { float:left; width:47%; padding:5px; margin:0 0 10px 10px; border-bottom:1px dotted #cccccc; }
/* rules for iPad in landscape orientation */
@media only screen and (device-width: 768px) and (orientation: landscape) {
.quran_gateway_sora_list ul li { display:block; width:100%; padding:0 0 10px 0; margin:0 0 10px 0; }
}

/* Mobile Landscape Size to Tablet Portrait (devices and browsers) */
@media only screen and (min-width: 480px) and (max-width: 767px) {
.quran_gateway_sora_list ul li { display:block; width:100%; padding:0 0 10px 0; margin:0 0 10px 0; }
}

/* Mobile Portrait Size to Mobile Landscape Size (devices and browsers) */
@media only screen and (min-width: 320px) and (max-width: 479px) {
.quran_gateway_sora_list ul li { display:block; width:100%; padding:0 0 10px 0; margin:0 0 10px 0; }
}

@media only screen and (min-width: 240px) and (max-width: 320px) {
.quran_gateway_sora_list ul li { display:block; width:100%; padding:0 0 10px 0; margin:0 0 10px 0; }
}

@media only screen and (max-width: 240px) {
.quran_gateway_sora_list ul li { display:block; width:100%; padding:0 0 10px 0; margin:0 0 10px 0; }
}";
	do_action('quran_gateway_css');
	echo "</style>\n";
}
add_action('admin_head','Quran_Gateway_add_style');

add_action('admin_menu', 'Quran_gateway_menu');
function Quran_gateway_menu() {
	add_menu_page( 'Quran Gateway', 'Quran Gateway', 'manage_options', 'quran-gateway-edit', 'quran_gateway_options', ''.trailingslashit(plugins_url(null,__FILE__)).'/i/quran_gateway.png' );
	add_submenu_page( 'quran-gateway-edit', 'Shortcode', 'Shortcode', 'manage_options', 'quran-gateway-edit-setting', 'get_quran_shortcode');
}

function get_files(){
?>
<select name="quran_gateway_id" id="quran_gateway_id">
<option value="1"<?php if(isset($_POST['quran_gateway_id']) && $_POST['quran_gateway_id'] == 1){ echo ' selected="selected"'; } if(get_option('quran_gateway_id')==1){ echo ' selected="selected"'; }?>>English</option>
<option value="2"<?php if(isset($_POST['quran_gateway_id']) && $_POST['quran_gateway_id'] == 2){ echo ' selected="selected"'; } if(get_option('quran_gateway_id')==2){ echo ' selected="selected"'; }?>>French</option>
<option value="3"<?php if(isset($_POST['quran_gateway_id']) && $_POST['quran_gateway_id'] == 3){ echo ' selected="selected"'; } if(get_option('quran_gateway_id')==3){ echo ' selected="selected"'; }?>>German</option>
</select>
<?php
}

function quran_gateway_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

if(isset($_POST['submitted']) && $_POST['submitted'] == 1){
	if ( get_option( 'quran_gateway_id' ) != "" ) {
		update_option( 'quran_gateway_id', intval($_POST['quran_gateway_id']) );
		update_option( 'quran_gateway_view', intval($_POST['quran_gateway_view']) );
		update_option( 'quran_gateway_by_sora', intval($_POST['quran_gateway_by_sora']) );
		update_option( 'quran_gateway_allow_by_sora', intval($_POST['quran_gateway_allow_by_sora']) );
		update_option( 'quran_gateway_hidden_text_ar', intval($_POST['quran_gateway_hidden_text_ar']) );
		update_option( 'quran_gateway_hidden_text_en', intval($_POST['quran_gateway_hidden_text_en']) );
		update_option( 'quran_gateway_hidden_player', intval($_POST['quran_gateway_hidden_player']) );
		update_option( 'quran_gateway_hidden_file_download', intval($_POST['quran_gateway_hidden_file_download']) );
		update_option( 'quran_gateway_border', intval($_POST['quran_gateway_border']) );
		update_option( 'quran_gateway_background_color', strip_tags($_POST['quran_gateway_background_color']) );
		update_option( 'quran_gateway_border_color', strip_tags($_POST['quran_gateway_border_color']) );
		update_option( 'quran_gateway_padding', intval($_POST['quran_gateway_padding']) );
		update_option( 'quran_gateway_margin', intval($_POST['quran_gateway_margin']) );
		update_option( 'quran_gateway_margin_between', intval($_POST['quran_gateway_margin_between']) );
	} else {
		add_option( 'quran_gateway_id', '1', null );
		add_option( 'quran_gateway_view', '1', null );
		add_option( 'quran_gateway_by_sora', '1', null );
		add_option( 'quran_gateway_allow_by_sora', '0', null );
		add_option( 'quran_gateway_hidden_text_ar', '0', null );
		add_option( 'quran_gateway_hidden_text_en', '0', null );
		add_option( 'quran_gateway_hidden_player', '0', null );
		add_option( 'quran_gateway_hidden_file_download', '0', null );
		add_option( 'quran_gateway_border', '1', null );
		add_option( 'quran_gateway_background_color', '#f2f2f2', null );
		add_option( 'quran_gateway_border_color', '#cccccc', null );
		add_option( 'quran_gateway_padding', '10', null );
		add_option( 'quran_gateway_margin', '10', null );
		add_option( 'quran_gateway_margin_between', '10', null );
	}
}
?>

<div class="wrap">
	<h2>Quran Gateway</h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<form name="sytform" action="" method="post">
				<input type="hidden" name="submitted" value="1" />

					<div class="stuffbox">
					<h3><label for="quran_gateway_id">Select Language</label></h3>
					<div class="inside">
					<?php //echo get_quran_all_sora(); ?>
					<?php //echo get_quran_by_sora(1); ?>
					<?php get_files(); ?>
					</div>
					</div>
									
					<div class="stuffbox">
					<h3><label for="quran_gateway_view">Allow Quran Random</label></h3>
					<div class="inside">
					<select name="quran_gateway_view" id="quran_gateway_view">
					<?php
					if(get_option('quran_gateway_view')==1){
					echo '<option value="1" selected="selected">Yes</option>';
					echo '<option value="0">No</option>';
					}else{
					echo '<option value="1">Yes</option>';
					echo '<option value="0" selected="selected">No</option>';
					}
					?>
					</select>
					</div>
					</div>
					
					<div class="stuffbox">
					<h3><label for="quran_gateway_by_sora">Random by sora</label></h3>
					<div class="inside">
						
					<select name="quran_gateway_allow_by_sora" id="quran_gateway_allow_by_sora">
					<option value="1"<?php if(get_option('quran_gateway_allow_by_sora')==1){ echo ' selected="selected"'; }?>>Yes</option>
					<option value="0"<?php if(get_option('quran_gateway_allow_by_sora')==0){ echo ' selected="selected"'; }?>>No</option>
					</select> <label for="quran_gateway_allow_by_sora">Allow random by sora</label><br />
					
					<select name="quran_gateway_by_sora" id="quran_gateway_by_sora">
					<?php
					global $sora_en;
					for($i=1; $i < count($sora_en); ++$i){
					if(get_option('quran_gateway_by_sora')==$i){
					echo '<option value="'.$i.'" selected="selected">'.$i.'. '.$sora_en[$i].'</option>';
					}else{
					echo '<option value="'.$i.'">'.$i.'. '.$sora_en[$i].'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_by_sora">Select sora</label>
					</div>
					</div>
						
					<div class="stuffbox">
					<h3><label for="quran_gateway_id">Setting</label></h3>
					<div class="inside">

					<select name="quran_gateway_hidden_text_ar" id="quran_gateway_hidden_text_ar">
					<?php
					if(get_option('quran_gateway_hidden_text_ar')==1){
					echo '<option value="1" selected="selected">Hidden</option>';
					echo '<option value="0">Show</option>';
					}else{
					echo '<option value="1">Hidden</option>';
					echo '<option value="0" selected="selected">Show</option>';
					}
					?>
					</select> <label for="quran_gateway_hidden_text_ar">Arabic text</label><br />
						
					<select name="quran_gateway_hidden_text_en" id="quran_gateway_hidden_text_en">
					<?php
					if(get_option('quran_gateway_hidden_text_en')==1){
					echo '<option value="1" selected="selected">Hidden</option>';
					echo '<option value="0">Show</option>';
					}else{
					echo '<option value="1">Hidden</option>';
					echo '<option value="0" selected="selected">Show</option>';
					}
					?>
					</select> <label for="quran_gateway_hidden_text_en">Language text</label><br />
						
					<select name="quran_gateway_hidden_player" id="quran_gateway_hidden_player">
					<?php
					if(get_option('quran_gateway_hidden_player')==1){
					echo '<option value="1" selected="selected">Hidden</option>';
					echo '<option value="0">Show</option>';
					}else{
					echo '<option value="1">Hidden</option>';
					echo '<option value="0" selected="selected">Show</option>';
					}
					?>
					</select> <label for="quran_gateway_hidden_player">Audio player</label><br />
						
					<select name="quran_gateway_hidden_file_download" id="quran_gateway_hidden_file_download">
					<?php
					if(get_option('quran_gateway_hidden_file_download')==1){
					echo '<option value="1" selected="selected">Hidden</option>';
					echo '<option value="0">Show</option>';
					}else{
					echo '<option value="1">Hidden</option>';
					echo '<option value="0" selected="selected">Show</option>';
					}
					?>
					</select> <label for="quran_gateway_hidden_file_download">Download file</label><br />
						
					<select name="quran_gateway_padding" id="quran_gateway_padding">
					<?php
					echo '<option value="">- - -</option>';
					for($ii=1; $ii <= 100; ++$ii){
					if(get_option('quran_gateway_padding')==$ii){
					echo '<option value="'.$ii.'" selected="selected">'.$ii.'</option>';
					}else{
					echo '<option value="'.$ii.'">'.$ii.'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_padding">Padding</label><br />
						
					<select name="quran_gateway_margin" id="quran_gateway_margin">
					<?php
					echo '<option value="">- - -</option>';
					for($iii=1; $iii <= 100; ++$iii){
					if(get_option('quran_gateway_margin')==$iii){
					echo '<option value="'.$iii.'" selected="selected">'.$iii.'</option>';
					}else{
					echo '<option value="'.$iii.'">'.$iii.'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_margin">Margin</label><br />
						
					<select name="quran_gateway_margin_between" id="quran_gateway_margin_between">
					<?php
					echo '<option value="">- - -</option>';
					for($iiii=1; $iiii <= 100; ++$iiii){
					if(get_option('quran_gateway_margin_between')==$iiii){
					echo '<option value="'.$iiii.'" selected="selected">'.$iiii.'</option>';
					}else{
					echo '<option value="'.$iiii.'">'.$iiii.'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_margin_between">Margin between text</label><br />
						
					<select name="quran_gateway_border" id="quran_gateway_border">
					<?php
					echo '<option value="">- - -</option>';
					for($iiiii=1; $iiiii <= 10; ++$iiiii){
					if(get_option('quran_gateway_border')==$iiiii){
					echo '<option value="'.$iiiii.'" selected="selected">'.$iiiii.'</option>';
					}else{
					echo '<option value="'.$iiiii.'">'.$iiiii.'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_border">Border</label><br />
						
					<select name="quran_gateway_background_color" id="quran_gateway_background_color">
					<option value="">- - -</option>
					<?php
					global $colors;
					for($c=0; $c < count($colors); ++$c){
					if(get_option('quran_gateway_background_color')==$colors[$c]){
					echo '<option style="background-color:'.$colors[$c].';" value="'.$colors[$c].'" selected="selected">'.$colors[$c].'</option>';
					}else{
					echo '<option style="background-color:'.$colors[$c].';" value="'.$colors[$c].'">'.$colors[$c].'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_background_color" style="color:<?php echo get_option('quran_gateway_background_color'); ?>">Background color</label><br />

					<select name="quran_gateway_border_color" id="quran_gateway_border_color">
					<option value="">- - -</option>
					<?php
					global $colors;
					for($cc=0; $cc < count($colors); ++$cc){
					if(get_option('quran_gateway_border_color')==$colors[$cc]){
					echo '<option style="background-color:'.$colors[$cc].';" value="'.$colors[$cc].'" selected="selected">'.$colors[$cc].'</option>';
					}else{
					echo '<option style="background-color:'.$colors[$cc].';" value="'.$colors[$cc].'">'.$colors[$cc].'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_border_color" style="color:<?php echo get_option('quran_gateway_border_color'); ?>">Border color</label>

					</div>
					</div>

					<div id="publishing-action">
						<input name="Submit" type="submit" class="button-large button-primary" id="publish" value="Update options" />
					</div>

				</form>
			</div>
		
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables">
					<div id="linksubmitdiv" class="postbox ">
					<h3><span>View example</span></h3>
						<div class="inside">
						<?php echo get_quran_gateway(1); ?>
						</div>
						<div class="inside">
						<p>Insert code in header.php: <code>&lt;?php if ( function_exists('get_quran_gateway') ){ echo get_quran_gateway(); } ?&gt;</code></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}

function get_quran_shortcode() {
	global $sora_ar, $sora_en;
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	if(isset($_GET['add']) && $_GET['add'] == 1){
		if ( isset($_GET['add_all_sorah']) && $_GET['add_all_sorah'] == 1 ) {
			if ( isset($_GET['language_id']) && $_GET['language_id'] != "" ) {
				$language_id = intval($_GET['language_id']);
				$content = 'get_all_sorah['.$language_id.']';
			}else{
				$language_id = 1;
				$content = 'get_all_sorah['.$language_id.']';
			}
			$language = get_quran_languages($language_id);
			
			if ( isset($_GET['sora_id']) && $_GET['sora_id'] != "" ) {
				$sora_id = intval($_GET['sora_id']);
				$title = ' - '.$sora_en[$sora_id];
				$content = 'get_sorah['.$sora_id.','.$language_id.']';
			}else{
				$sora_id = 1;
				$title = '';
			}
			

			
			$category_id = 1;
			$post_title = 'The Noble quran in '.$language[0].''.$title.'';
			$post_excerpt = 'The Noble quran in '.$language[0].' '.$language[1].''.$title.'';
			
			$my_post = array(
			  'post_title'    => $post_title,
			  'post_content'  => $content,
			  'post_status'   => 'publish',
			  'post_excerpt'  => $post_excerpt,
			  'post_category' => array($category_id)
			);
			$added_post = wp_insert_post( $my_post );

			if($added_post){
				//$report = '<p style="color:green;">Added Post: ID <a href="post.php?post='.$added_post.'&action=edit">'.$added_post.'</a></p>';
				echo '<meta http-equiv="refresh" content="0; URL=\'admin.php?page=quran-gateway-edit-setting&post_id='.$added_post.'\'" />';
			}else{
				echo '<p style="color:red;">Error: not insert post!</p>';
			}
		}else{
			echo '';
		}

	}else{
		echo '';
	}
	?>
<div class="wrap">
	<h2>Get Shortcode</h2>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<form name="sytform" action="" method="post">
				<input type="hidden" name="submitted" value="1" />
				<input type="hidden" name="post_id" value="h" />

					<div class="stuffbox">
					<h3><label for="quran_gateway_id">Language shortcode</label></h3>
					<div class="inside">
					<?php //echo get_quran_all_sora(); ?>
					<?php //echo get_quran_by_sora(1); ?>
					<?php get_files(); ?>
					</div>
					</div>
									
					<div class="stuffbox">
					<h3><label for="quran_gateway_by_sora">Sorah shortcode</label></h3>
					<div class="inside">
			
					<select name="quran_gateway_by_sora" id="quran_gateway_by_sora">
					<?php
					global $sora_en;
					echo '<option value="0">- - -</option>';
					for($i=1; $i < count($sora_en); ++$i){
					if(isset($_POST['quran_gateway_by_sora']) && $_POST['quran_gateway_by_sora']==$i){
					echo '<option value="'.$i.'" selected="selected">'.$i.'. '.$sora_en[$i].'</option>';
					}else{
					echo '<option value="'.$i.'">'.$i.'. '.$sora_en[$i].'</option>';
					}
					}
					?>
					</select> <label for="quran_gateway_by_sora">Select sorah</label>
					</div>
					</div>
						
					<div id="publishing-action">
						<input name="Submit" type="submit" class="button-large button-primary" id="publish" value="Get shortcode" />
					</div>

				</form>
			</div>
		
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables">
					<div id="linksubmitdiv" class="postbox ">
					<h3><span>View shortcode</span></h3>
						<div class="inside">
						
						<?php
						if(isset($_GET['post_id']) && $_GET['post_id'] != 0 && !isset($_POST['post_id'])){
							echo '<p style="color:green;">Added Post: ID <a href="post.php?post='.intval($_GET['post_id']).'&action=edit">'.intval($_GET['post_id']).'</a>';
						}else{
							if(isset($_POST['quran_gateway_id']) && $_POST['quran_gateway_id'] != 0){
								echo '<p>Language shortcode: <code>get_all_sorah['.intval($_POST['quran_gateway_id']).']</code><br /><a href="admin.php?page=quran-gateway-edit-setting&add=1&add_all_sorah=1&language_id='.intval($_POST['quran_gateway_id']).'">Add</a></p>';
								$lang = intval($_POST['quran_gateway_id']);
							}else{
								//echo '<p>Please select from dropmenu.</p>';
								$lang = 1;
							}
							
							if(isset($_POST['quran_gateway_by_sora']) && $_POST['quran_gateway_by_sora'] != 0){
								echo '<p>Surah shortcode: <code>get_sorah['.intval($_POST['quran_gateway_by_sora']).','.$lang.']</code><br /><a href="admin.php?page=quran-gateway-edit-setting&add=1&add_all_sorah=1&language_id='.intval($_POST['quran_gateway_id']).'&sora_id='.intval($_POST['quran_gateway_by_sora']).'">Add</a></p>';
							}else{
								//echo '<p>Please select from dropmenu.</p>';
							}
							
							if(!isset($_POST['quran_gateway_by_sora']) && !isset($_POST['quran_gateway_id'])){
								echo '<p>Please select from dropmenu.</p>';
							}else{
								echo '<p>Insert code in post/page.</p>';
							}
						}
						?>
						
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
}