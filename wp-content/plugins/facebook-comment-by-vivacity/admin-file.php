<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'admin_menu', 'vi_my_admin_plugin' );
function vi_my_admin_plugin() {
    wp_register_script( 'my_plugin_script', plugins_url('/script.js', __FILE__), array('jquery'));
    wp_enqueue_script( 'my_plugin_script' );
}

function vi_fbcomment_init(){
	register_setting( 'vi_fbcomment_option', 'fbcomment' );
	$new_fboptn = array(
		'fbml' => 'on',
		'opengraph' => 'off',
		'fbns' => 'off',
		'html5' => 'on',
		'posts' => 'on',
		'pages' => 'off',
		'pagesid' => 00,
		'homepage' => 'off',
		'appID' => '',
		'mods' => '',
		'num' => '6',
		'count' => 'on',
		'countmsg' => 'comments',
		'title' => 'Comments',
		'width' => '500',
		'pluginsite' => 'off',
		'scheme' => 'light',
		'hideWpComments' => 'off',
		'postshideWpComments' => 'off',
		'pageshideWpComments' => 'off',
		'selected_types' => 'selected_types',
		'lang' => 'en_Us'
	);


	foreach( $new_fboptn as $key => $value ) {
		if( $existing = get_option( 'fbcomment_' . $key ) ) {
			 $new_fboptn[$key] = $existing;
			delete_option( 'fbcomment_' . $key );
		}
	}
	add_option( 'fbcomment', $new_fboptn );
}
add_action('admin_init', 'vi_fbcomment_init' );


function vi_show_fbcomment_option() {
	add_options_page( __( 'FB Comments', 'facebook-comment-by-vivacity' ), __( 'FB Comments', 'facebook-comment-by-vivacity' ), 'manage_options', 'fbcomment', 'vi_fbcomment_option');
}
add_action('admin_menu', 'vi_show_fbcomment_option');

 
function vi_fbcomments_msg(){
 $fboptn = get_option('fbcomment');

   	if ($fboptn['appID'] == "") {
	$fb_adminUrl = get_admin_url()."options-general.php?page=". __( "fbcomment", "facebook-comment-by-vivacity" );
      echo '<div class="error">
            <p>'. __( "Please Enter Your Facebook App ID. Required for Fb comments.", "facebook-comment-by-vivacity" ).' <a class="fcv_a" href="'.$fb_adminUrl.'">'. __( "Click here", "facebook-comment-by-vivacity" ).'</a></p>
            </div>';
   }  
}
add_action('admin_notices', 'vi_fbcomments_msg');

function vi_fbcomment_option() {
	?>
<link href="<?php echo plugins_url( 'css/style.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css">

   <div class="fcv_top">
  <h3> <?php _e( "FB Comments Plugin", "facebook-comment-by-vivacity" ) ?> <small><?php _e("by","facebook-comment-by-vivacity") ?> <a class="fcv_a" href="http://www.vivacityinfotech.com" target="_blank">Vivacity Infotech Pvt. Ltd.</a>
  </h3>
    </div> <!-- ------End of top-----------  -->
  	<div class="fcv_inner_wrap">
	 <div class="fcv_left">
	<form method="post" action="options.php" id="options">
			<?php settings_fields('vi_fbcomment_option'); ?>
			<?php $fboptn = get_option('fbcomment'); 
if (!isset($fboptn['fbml'])) {$fboptn['fbml'] = "";}
if (!isset($fboptn['fbns'])) {$fboptn['fbns'] = "";}
if (!isset($fboptn['opengraph'])) {$fboptn['opengraph'] = "";}
if (!isset($fboptn['html5'])) {$fboptn['html5'] = "";}
if (!isset($fboptn['pluginsite'])) {$fboptn['pluginsite'] = "";}
if (!isset($fboptn['posts'])) {$fboptn['posts'] = "";}
if (!isset($fboptn['pages'])) {$fboptn['pages'] = "";}
if (!isset($fboptn['homepage'])) {$fboptn['homepage'] = "";}
if (!isset($fboptn['count'])) {$fboptn['count'] = "";}
if (!isset($fboptn['countmsg'])) {$fboptn['countmsg'] = "";}
if (!isset($fboptn['jquery'])) {$fboptn['jquery'] = "";}
if (!isset($fboptn['hideWpComments'])) {$fboptn['hideWpComments'] = "selected_types";}
if (!isset($fboptn['selected_types'])) {$fboptn['selected_types'] = "";}
if (!isset($fboptn['postshideWpComments'])) {$fboptn['postshideWpComments'] = "";}
if (!isset($fboptn['pageshideWpComments'])) {$fboptn['pageshideWpComments'] = "";}
if (!isset($fboptn['lang'])) {$fboptn['lang'] = "en_Us";}
if (!is_numeric($fboptn['pagesid'])) {$fboptn['pagesid'] = 00;}
$domainname = get_option('siteurl');
$domainname = str_replace('http://', '', $domainname);
$domainname = str_replace('www.', '', $domainname);?>
<!-- end get domain name -->

<!-- facebook App Id settings -->
<?php if ($fboptn['appID'] == "") { 
?>
<div class="error">
<h3 class="setup"><?php _e("Set Up Your Facebook App ID","facebook-comment-by-vivacity") ?></h3> <!-- ----Set Up Your Facebook App ID -->
	<table class="form-table admintbl">
	  <tr>
	   <th>
      <strong><a class="fcv_a" href="https://developers.facebook.com/apps" target="_blank"><?php _e("Create an App","facebook-comment-by-vivacity") ?></a></strong><br>
      <small><?php _e("To get App Id click on","facebook-comment-by-vivacity") ?> " <a class="fcv_a" href="https://developers.facebook.com/apps" target="_blank"> <?php _e("Create an App","facebook-comment-by-vivacity") ?></a>".</small>
      </th>
	   <td><small><?php _e("Enter App Id into below textbox.","facebook-comment-by-vivacity") ?></small><br>
	       <input id="appID" type="text" name="fbcomment[appID]" value="<?php echo $fboptn['appID']; ?>" />
	        <strong><?php _e("APP ID","facebook-comment-by-vivacity") ?></strong><br>
      </td>
	  </tr>
	</table> <!-- -----End Set Up Your Facebook App ID -->
</div>
<?php } else { ?>
	<h3 class="title" id="fbappsettings"><?php _e("Facebook App Setting","facebook-comment-by-vivacity") ?></h3> <!-- ----Facebook App Setting -->
	<div id="fbappsettingstbl" class="togglediv">
	<table class="form-table admintbl">
		<tr>
		  <th><small><?php _e("To edit your App ID click on below link:","facebook-comment-by-vivacity") ?></small><br>
		  <strong><a class="fcv_a" href="https://developers.facebook.com/apps<?php if ($fboptn['appID'] != "") { echo "/".$fboptn['appID']."/summary"; } ?>" target="_blank"><?php _e("Your App Setup","facebook-comment-by-vivacity") ?></a></strong></th>
		  <td><small><?php _e("choose your App and click","facebook-comment-by-vivacity") ?> <strong>  <?php _e("Edit Settings","facebook-comment-by-vivacity") ?></strong>. <?php _e("Please Enter","facebook-comment-by-vivacity") ?> <strong><?php echo $domainname; ?></strong> <?php _e('in both "App Domains" and "Site URL"',"facebook-comment-by-vivacity") ?></small></td>
		</tr>
		<tr>
		 <th>
		 <a class="fcv_a" href="https://developers.facebook.com/apps" target="_blank"><?php _e("Create a New App","facebook-comment-by-vivacity") ?></a>     
		 </th>
		 <td><small><?php _e("If you want to set up a new App Id click","facebook-comment-by-vivacity") ?> <strong><?php _e("Create a New App","facebook-comment-by-vivacity") ?></strong> </small>
		 </td>
		</tr>
	</table> 
	</div>
 
	
<?php } ?>	
	<h3 class="title" id="mainsettings"><?php _e("Main Settings","facebook-comment-by-vivacity") ?></h3>  
	<div  id="mainsettingstbl" class="togglediv">
	<table class="form-table admintbl">
<?php if ($fboptn['appID']!="") { ?>
		<tr><th><label for="appID"><?php _e("Facebook App ID","facebook-comment-by-vivacity") ?></label></th>
			 <td><input id="appID" type="text" name="fbcomment[appID]" value="<?php echo $fboptn['appID']; ?>" /></td>
		</tr>
<?php } ?>
		<tr><th><label for="fbml"><?php _e("Enable XFBML","facebook-comment-by-vivacity") ?></label></th>
			<td><input id="fbml" name="fbcomment[fbml]" type="checkbox" value="on" <?php checked('on', $fboptn['fbml']); ?> /> <small><?php _e("only disable this if you already have XFBML enabled elsewhere","facebook-comment-by-vivacity") ?></small></td>
		</tr>
		<tr><th><label for="fbns"><?php _e("Use Facebook NameServer","facebook-comment-by-vivacity") ?></label></th>
			<td><input id="fbns" name="fbcomment[fbns]" type="checkbox" value="on" <?php checked('on', $fboptn['fbml']); ?> /> <small><?php _e("only enable this if Facebook Comments do not appear","facebook-comment-by-vivacity") ?></small></td>
		</tr>
		<tr><th><label for="opengraph"><?php _e("Use Open Graph NameServer","facebook-comment-by-vivacity") ?></label></th>
			<td><input id="opengraph" name="fbcomment[opengraph]" type="checkbox" value="on" <?php checked('on', $fboptn['opengraph']); ?> /> <small><?php _e("only enable this if Facebook comments are not appearing, not all information is being passed to Facebook or if you have not enabled Open Graph elsewhere within WordPress","facebook-comment-by-vivacity") ?></small></td>
		</tr>
		<tr><th><label for="html5"><?php _e("Use HTML5","facebook-comment-by-vivacity") ?></label></th>
			<td><input id="html5" name="fbcomment[html5]" type="checkbox" value="on" <?php checked('on', $fboptn['html5']); ?> /></td>
		</tr>
		<tr><th><label for="pluginsite"><?php _e("Show plugin site url","facebook-comment-by-vivacity") ?>:</label></th>
		   <td><input id="credit" name="fbcomment[pluginsite]" type="checkbox" value="on" <?php checked('on', $fboptn['pluginsite']); ?> /> <small><?php _e("only enable this if you want to show plugin site","facebook-comment-by-vivacity") ?>.</small>
		</td>
		</tr>
   </table> 
   </div> <!-- ------End Main Settings--------- -->
	<h3 id="displaysettings" class="title"><?php _e("Display Settings","facebook-comment-by-vivacity") ?></h3> <!-- ---Display Settings -->
	<div id="displaysettingstbl" class="togglediv">
	 <table class="form-table admintbl">
		<tr><th><label for="posts"><?php _e( 'Posts', 'facebook-comment-by-vivacity' ); ?></label></th>
		<td><input id="posts" name="fbcomment[posts]" type="checkbox" value="on" <?php checked('on', $fboptn['posts']); ?> /> </td>
		</tr>
		<tr><th><label for="pages"><?php _e( 'Pages', 'facebook-comment-by-vivacity' ); ?></label></th>
		<td><input id="pages" name="fbcomment[pages]" type="checkbox" value="on" <?php checked('on', $fboptn['pages']); ?> />
			<span><input id="pageid" name="fbcomment[pagesid]" type="text"  value="<?php  echo $fboptn['pagesid']; ?>"  /> 
			<small><?php _e( "Enter page id's where you dont want to show FB comments (Ex:-73,37)." ); ?></small> </span>		
		</td>
		</tr>
		<tr><th><label for="homepage"><?php _e( 'Home', 'facebook-comment-by-vivacity' ); ?></label></th>
		<td><input id="home" name="fbcomment[homepage]" type="checkbox" value="on" <?php checked('on', $fboptn['homepage']); ?> />
		</td>
		</tr>
		<tr><th><label for="scheme"><?php _e( 'Colour Scheme', 'facebook-comment-by-vivacity' ); ?></label></th>
		<td>
				<select name="fbcomment[scheme]">
					<option value="light" <?php if ($fboptn['scheme'] == 'light') { echo ' selected="selected"'; } ?> ><?php _e( 'Light', 'facebook-comment-by-vivacity' ); ?></option>
					<option value="dark" <?php if ($fboptn['scheme'] == 'dark') { echo ' selected="selected"'; } ?> ><?php _e( 'Dark', 'facebook-comment-by-vivacity' ); ?></option>
				</select>
		</td>
		</tr>
		<tr><th><label for="num"><?php _e( 'Number of Comments', 'facebook-comment-by-vivacity' ); ?></label></th>
			<td><input id="num" type="text" name="fbcomment[num]" value="<?php echo $fboptn['num']; ?>" /> <small> - <?php _e( 'Default no of comments is', 'facebook-comment-by-vivacity' ); ?> <strong>6
			</strong></small>
			</td>
		</tr>
		<tr><th><label for="width"><?php _e( 'Width', 'facebook-comment-by-vivacity' ); ?></label></th>
			<td><input id="width" type="text" name="fbcomment[width]" value="<?php echo $fboptn['width']; ?>" />px <small> - <?php _e( 'For Responsive Look use 100% as width', 'facebook-comment-by-vivacity' ); ?> <?php _e( 'Default comment box width is', 'facebook-comment-by-vivacity' ); ?> <strong>500px</strong></small>
			</td>
		</tr>
		<tr><th><label for="title"><?php _e( 'Title', 'facebook-comment-by-vivacity' ); ?></label></th>
			<td><input id="title" type="text" name="fbcomment[title]" value="<?php echo $fboptn['title']; ?>" />
			</td>
		</tr>
		<tr><th><label for="count"><?php _e( 'Show Comment Count', 'facebook-comment-by-vivacity' ); ?></label></th>
			<td><input id="count" name="fbcomment[count]" type="checkbox" value="on" <?php checked('on', $fboptn['count']); ?> />
			</td>
		</tr>
		<tr><th><label for="countmsg"><?php _e( 'Comment text', 'facebook-comment-by-vivacity' ); ?></label></th>
			<td><input id="countmsg" type="text" name="fbcomment[countmsg]" value="<?php echo $fboptn['countmsg']; ?>" />
			</td>
		</tr>
	</table>
	</div>		<!-- -----End Display Settings---- -->
	
	<h3 id="moderation" class="title"><?php _e( 'Moderation Settings', 'facebook-comment-by-vivacity' ); ?></h3>   <!-- ------ Moderation--------- --> 
	<div id="moderationtbl" class="togglediv">
   <table class="form-table admintbl">
	<tr><th><a class="fcv_a" href="https://developers.facebook.com/tools/comments<?php if ($fboptn['appID'] != "") { echo "?id=".$fboptn['appID']."&view=queue"; } ?>" target="_blank"><?php _e( 'Moderation Area', 'facebook-comment-by-vivacity' ); ?></a></th>
					<td><small><?php _e( 'If you are a moderator, you will see notifications within facebook.com. If you don\'t want to have moderator status, click on "Moderation Area" and use this link to left.', 'facebook-comment-by-vivacity' ); ?></small></td>
	</tr>
		<tr><th><label for="appID"><?php _e( 'Moderators', 'facebook-comment-by-vivacity' ); ?></label></th>
		<td><input id="mods" type="text" name="fbcomment[mods]" value="<?php echo $fboptn['mods']; ?>" size="50" /><br><small><?php _e( 'All admins to the App ID can moderate comments,By default. To add moderators, enter each Facebook User ID by a comma without spaces. To find your Facebook User ID', 'facebook-comment-by-vivacity' ); ?>,<a class="fcv_a" href="https://developers.facebook.com/tools/explorer/?method=GET&path=me" target="blank"><?php _e( 'click here', 'facebook-comment-by-vivacity' ); ?></a> <?php _e( 'where you will see your own. To view someone else\'s, replace "me" with their username in the input provided', 'facebook-comment-by-vivacity' ); ?></small></td>
		</tr>
  </table>
  </div>  <!-- ------End Moderation--------- --> 
 
 <h3 id="" class="title"><?php _e( 'Hide/Show default wp comments', 'facebook-comment-by-vivacity' ); ?></h3>   <!-- ------ default wp comments--------- --> 
	<div id="" class="togglediv">
 <table class="form-table admintbl">
<!-- ------everywhere--------- --> 
<div class="everywhere">
		<tr>
		<th class="rgtth"><input type="radio"  class="mode" id="fbComments_hideWpComments" name="fbcomment[hideWpComments]" onchange="setFries()" value="on" <?php checked('on', $fboptn['hideWpComments']); ?>   /></th>
		<td>
		<label for="fbComments_hideWpComments"> <?php _e('Hide WordPress Default Comments System From Website', 'facebook-comment-by-vivacity'); ?></label>

		</td>
		</tr>
		
</div>		
<div class="clr"></div>
<tr>
<th class="rgtth"><input type="radio" class="mode" id="selected_types" name="fbcomment[hideWpComments]" onchange="setFries()" value="selected_types" <?php checked( 'selected_types', $fboptn['hideWpComments']); ?> /> </th>
<td>
<label for="selected_types"> <?php _e('Hide WordPress Default Comments System On Certain Post Types', 'facebook-comment-by-vivacity'); ?></label>
</td>
</tr>
  </table>
     <table class="form-table admintbl posts-pages">
<!-- ------post--------- --> 		
		<tr><th><label for="posts_hideWpComments"> <?php _e('Hide WordPress Default Comments System On Posts', 'facebook-comment-by-vivacity'); ?></label>
		</th>
		<td>
		<input type="checkbox" class="checkmode" id="posts_hideWpComments" name="fbcomment[postshideWpComments]"  value="on" <?php checked('on', $fboptn['postshideWpComments']); ?>
<?php if($fboptn['hideWpComments'] == 'on'){ ?> disabled="true"  <?php	} ?> />
				</td>
		</tr>
<!-- ------page--------- --> 		
		<tr><th><label for="fbComments_hideWpComments"> <?php _e('Hide WordPress Default Comments System On Pages', 'facebook-comment-by-vivacity'); ?></label>
		</th>
		<td>
		<input type="checkbox" class="checkmode" id="pages_hideWpComments" name="fbcomment[pageshideWpComments]"  value="on" <?php checked('on', $fboptn['pageshideWpComments']); ?>
		<?php if($fboptn['hideWpComments'] == 'on'){ ?> disabled="true"  <?php	} ?> />
				</td>
		</tr>
  </table>
  </div>  <!-- ------End default wp comments--------- --> 
  
  	<h3 id="lang" class="title"><?php _e( 'Language Settings', 'facebook-comment-by-vivacity' ); ?></h3>   
  	<!-- ------ Language--------- --> 
	<div id="langtbl" class="togglediv">
   <table class="form-table admintbl">
			<tr><th><label for="Language"><?php _e( 'Select Language', 'facebook-comment-by-vivacity' ); ?></label></th>
		<td>
		 <?php $lang=array();
								$lang['af_ZA']='Afrikaans';
								$lang['sq_AL']='Albanian';
								$lang['ar_AR']='Arabic';
								$lang['hy_AM']='Armenian';
								$lang['ay_BO']='Aymara';
								$lang['az_AZ']='Azeri';
								$lang['eu_ES']='Basque';
								$lang['be_BY']='Belarusian';
								$lang['bn_IN']='Bengali';
								$lang['bs_BA']='Bosnian';
								$lang['bg_BG']='Bulgarian';
								$lang['ca_ES']='Catalan';
								$lang['ck_US']='Cherokee';
								$lang['hr_HR']='Croatian';
								$lang['cs_CZ']='Czech';
								$lang['da_DK']='Danish';
								$lang['nl_NL']='Dutch';
								$lang['nl_BE']='Dutch (Belgi?)';
								$lang['en_GB']='English (UK)';
								$lang['en_PI']='English (Pirate)';
								$lang['en_UD']='English (Upside Down)';
								$lang['en_US']='English (US)';
								$lang['eo_EO']='Esperanto';
								$lang['et_EE']='Estonian';
								$lang['fo_FO']='Faroese';
								$lang['tl_PH']='Filipino';
								$lang['fi_FI']='Finnish';
								$lang['fb_FI']='Finnish (test)';
								$lang['fr_CA']='French (Canada)';
								$lang['fr_FR']='French (France)';
								$lang['gl_ES']='Galician';
								$lang['ka_GE']='Georgian';
								$lang['de_DE']='German';
								$lang['el_GR']='Greek';
								$lang['gn_PY']='Guaran?';
								$lang['gu_IN']='Gujarati';
								$lang['he_IL']='Hebrew';
								$lang['hi_IN']='Hindi';
								$lang['hu_HU']='Hungarian';
								$lang['is_IS']='Icelandic';
								$lang['id_ID']='Indonesian';
								$lang['ga_IE']='Irish';
								$lang['it_IT']='Italian';
								$lang['ja_JP']='Japanese';
								$lang['jv_ID']='Javanese';
								$lang['kn_IN']='Kannada';
								$lang['kk_KZ']='Kazakh';
								$lang['km_KH']='Khmer';
								$lang['tl_ST']='Klingon';
								$lang['ko_KR']='Korean';
								$lang['ku_TR']='Kurdish';
								$lang['la_VA']='Latin';
								$lang['lv_LV']='Latvian';
								$lang['fb_LT']='Leet Speak';
								$lang['li_NL']='Limburgish';
								$lang['lt_LT']='Lithuanian';
								$lang['mk_MK']='Macedonian';
								$lang['mg_MG']='Malagasy';
								$lang['ms_MY']='Malay';
								$lang['ml_IN']='Malayalam';
								$lang['mt_MT']='Maltese';
								$lang['mr_IN']='Marathi';
								$lang['mn_MN']='Mongolian';
								$lang['ne_NP']='Nepali';
								$lang['se_NO']='Northern S?mi';
								$lang['nb_NO']='Norwegian (bokmal)';
								$lang['nn_NO']='Norwegian (nynorsk)';
								$lang['ps_AF']='Pashto';
								$lang['fa_IR']='Persian';
								$lang['pl_PL']='Polish';
								$lang['pt_BR']='Portuguese (Brazil)';
								$lang['pt_PT']='Portuguese (Portugal)';
								$lang['pa_IN']='Punjabi';
								$lang['qu_PE']='Quechua';
								$lang['ro_RO']='Romanian';
								$lang['rm_CH']='Romansh';
								$lang['ru_RU']='Russian';
								$lang['sa_IN']='Sanskrit';
								$lang['sr_RS']='Serbian';
								$lang['zh_CN']='Simplified Chinese (China)';
								$lang['sk_SK']='Slovak';
								$lang['sl_SI']='Slovenian';
								$lang['so_SO']='Somali';
								$lang['es_LA']='Spanish';
								$lang['es_CL']='Spanish (Chile)';
								$lang['es_CO']='Spanish (Colombia)';
								$lang['es_MX']='Spanish (Mexico)';
								$lang['es_ES']='Spanish (Spain)';
								$lang['sv_SE']='Swedish';
								$lang['sy_SY']='Syriac';
								$lang['tg_TJ']='Tajik';
								$lang['ta_IN']='Tamil';
								$lang['tt_RU']='Tatar';
								$lang['te_IN']='Telugu';
								$lang['th_TH']='Thai';
								$lang['zh_HK']='Traditional Chinese (Hong Kong)';
								$lang['zh_TW']='Traditional Chinese (Taiwan)';
								$lang['tr_TR']='Turkish';
								$lang['uk_UA']='Ukrainian';
								$lang['ur_PK']='Urdu';
								$lang['uz_UZ']='Uzbek';
								$lang['vi_VN']='Vietnamese';
								$lang['cy_GB']='Welsh';
								$lang['xh_ZA']='Xhosa';
								$lang['yi_DE']='Yiddish';
								$lang['zu_ZA']='Zulu';
							 ?>
							
				<select name="fbcomment[lang]">
				 <option value="en_US" selected="selected" >English (US)</option>
                 <?php 
                foreach($lang as $key=>$val)
							{
							   $selected='';
							  
								if($fboptn['lang']==$key)
									$selected="selected";
									echo '<option value="'.$key.'" '.$selected.' >'.$val.'</option>';
								
							}
								?>

                </select>
		</td>
		</tr>
  </table>
  </div>  <!-- ------End Language--------- --> 
	
		<div class="fcv_submitform">
			<input type="submit" class="button1" value="<?php _e('Save Changes') ?>" />
		</div>
</form>	
<!-- ---End of facebook App Id settings---- -->
	 </div> <!-- --------End of left div--------- -->
 <div class="fcv_right">
	<center>
	<div class="fcv_bottom">
		    <h3 id="shortcodedesc-comments" class="title"><?php _e( 'Shortcode For Templates', 'facebook-comment-by-vivacity' ); ?></h3>
     <div id="shortcodedesctbl-comments" class="togglediv">  
			<table class="right-tbl">
				<tr><td>
<p><?php _e( 'You can also insert FB Comment Box manually in any page or post or', 'facebook-comment-by-vivacity' ); ?> <strong><?php _e( 'template', 'facebook-comment-by-vivacity' ); ?></strong> <?php _e( 'by simply using the shortcode', 'facebook-comment-by-vivacity' ); ?> <strong>[vivafbcomment]</strong>. <br>
<?php _e( 'You can insert', 'facebook-comment-by-vivacity' ); ?> <strong>echo do_shortcode('[vivafbcomment]');</strong> <?php _e( 'code into your templates for use this shortcode', 'facebook-comment-by-vivacity' ); ?>.
</p>
<p><?php _e( 'You can also use below options to override the the settings used above.', 'facebook-comment-by-vivacity' ); ?></p>
<ul>
<li><strong>url</strong> - <?php _e( 'leave blank for current URL', 'facebook-comment-by-vivacity' ); ?></li>
<li><strong>width</strong> -  <?php _e( 'minimum must be', 'facebook-comment-by-vivacity' ); ?> <strong>350</strong></li>
<li><strong>num</strong> - <?php _e( 'number of comments', 'facebook-comment-by-vivacity' ); ?></li>
<li><strong>count</strong> - <?php _e( 'comment count on/off', 'facebook-comment-by-vivacity' ); ?></li>
<li><strong>scheme</strong> - <?php _e( 'color scheme: light/dark', 'facebook-comment-by-vivacity' ); ?></li>
<li><strong>pluginsite</strong> - <?php _e( 'enter', 'facebook-comment-by-vivacity' ); ?> "1" <?php _e( 'to link to the plugin', 'facebook-comment-by-vivacity' ); ?></li>
</ul>
<p><strong><?php _e( 'For Example', 'facebook-comment-by-vivacity' ); ?>:</strong></p>
<p>[vivafbcomment url="http://vivacityinfotech.net/" width="375" count="on" num="6" countmsg="awesome comments"]</p>
			</td>
				</tr>
		</table>
	</div> 
</div>
<div class="bottom">
		    <h3 id="download-comments" class="title"><?php _e( 'Download Free Plugins', 'facebook-comment-by-vivacity' ); ?></h3>
     <div id="downloadtbl-comments" class="togglediv">  
	<h3 class="company">
<strong>Vivacity InfoTech Pvt. Ltd. </strong>
<?php _e( 'has following plugins for you', 'facebook-comment-by-vivacity' ); ?> :
</h3>
<ul class="">
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-twitter-feeds/">WP Twitter Feeds</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-fb-share-like-button/">WP Facebook Like Button</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-facebook-fanbox-widget/">WP Facebook FanBox</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-google-analytics-scripts/">WP Google Analytics Scripts</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-xml-sitemap/">WP XML Sitemap</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-facebook-auto-publish/">WP Facebook Auto Publish</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-twitter-autopost/">WP Twitter Autopost</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-responsive-jquery-slider/">WP Responsive Jquery Slider</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-google-plus-one-button/">WP Google Plus One Button</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-qr-code-generator/">WP QR Code Generator</a></li>
<li><a class="fcv_a" target="_blank" href="http://wordpress.org/plugins/wp-inquiry-form/">WP Inquiry Form</a></li>
</ul>
  </div> 
</div>		
<div class="bottom">
		    <h3 id="donatehere-comments" class="title"><?php _e( 'Donate Here', 'facebook-comment-by-vivacity' ); ?></h3>
     <div id="donateheretbl-comments" class="togglediv">  
     <p><?php _e( 'If you want to donate , please click on below image.', 'facebook-comment-by-vivacity' ); ?></p>
	<a class="fcv_a" href="http://vivacityinfotech.net/paypal-donation/" target="_blank"><img class="donate" src="<?php echo plugins_url( 'assets/paypal.gif' , __FILE__ ); ?>" width="150" height="50" title="<?php _e( 'Donate Here', 'facebook-comment-by-vivacity' ); ?>"></a>		
  </div> 
</div>	
<div class="bottom">
 <h3 id="aboutauthor-comments" class="title">About The Author</h3>
     <div id="aboutauthortbl-comments" class="togglediv">  
	<p> <strong>Vivacity InfoTech Pvt. Ltd. , an ISO 9001:2008 Certified Company,</strong>is a Global IT Services company with expertise in outsourced product development and custom software development with focusing on software development, IT consulting, customized development.We have 200+ satisfied clients worldwide.</p>	
<h3 class="company">
<strong>Vivacity InfoTech Pvt. Ltd.</strong>
has expertise in :
</h3>
<ul class="">
<li>Outsourced Product Development</li>
<li>Customized Solutions</li>
<li>Web and E-Commerce solutions</li>
<li>Multimedia and Designing</li>
<li>ISV Solutions</li>
<li>Consulting Services</li>
<li>
<a class="fcv_a" target="_blank" href="http://www.lemonpix.com/">
<span class="colortext">Web Hosting</span>
</a>
</li>
 <h3>
 <strong><a class="fcv_a" target="_blank" href="http://vivacityinfotech.net/contact-us/" >Contact Us Here</a></strong>
 </h3>
</ul>
  </div> 
</div>	
	</center>
 </div><!-- --------End of right div--------- -->
</div> <!-- --------End of inner_wrap--------- -->

<?php } ?>
