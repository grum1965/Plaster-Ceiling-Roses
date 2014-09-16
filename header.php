<?php
/*
  $Id: header.php 1739 2007-12-20 00:52:16Z hpdl $
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
  Released under the GNU General Public License
  OSC to CSS v2 with 960 grid www.niora.com/css-oscommerce.com
*/

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
?>
<!-- START HEADER -->  
<!--Language icons-->
<div class="grid_2 alpha">
	<?php
	 if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'languages.php');
  }
  ?>
</div>

<div class="grid_10 omega">
<!-- Search -->
	<div class="search-box">
		<?php echo tep_draw_form('quick_find', tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'SSL', false), 'get'); ?>
		
		<?php  echo tep_draw_input_field('keywords', '', 'size="10" maxlength="25" class="search-field" value="Search..." onFocus="clearDefault(this)" onBlur="Default(this)"');?>
		<input type="hidden" name="search_in_description" value="1" />
		<input type="hidden" name="inc_subcat" value="1" />
		<?php echo tep_hide_session_id() .'<input type="submit" name="search" value="" class="search-go" border="0" width="79" height="25">';?>	
		</form>
	</div>	
<!-- Account Navigaton -->
	<div class="topnav-login">							
		<?php  
			echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">Home</a>';
			if (tep_session_is_registered('customer_id')) { 							
			echo '<a href="'.tep_href_link(FILENAME_LOGOFF, '', 'SSL').'" >'. HEADER_TITLE_LOGOFF.'</a>';
				}
			echo '<a href="'.tep_href_link(FILENAME_ACCOUNT, '', 'SSL').'" >'. HEADER_TITLE_MY_ACCOUNT.'</a><a href="'.tep_href_link(FILENAME_SHOPPING_CART).'" >'.HEADER_TITLE_CART_CONTENTS.'</a><a href="'.tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL').'" >'.HEADER_TITLE_CHECKOUT.'</a>'; 						
		?>						
	</div>
</div>
<div class="clear"></div>



<!-- Logo/Title-->
<div class="headerlogo" >
<?php 
// store logo
echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME) . '</a>';
// store name
//	echo '<div class="tagline"><h3>tableless OSCommerce/960 grid</h3></div>'; 
?>

</div>

<!--Superfish Horizontal Navigation bar-->
<div class="clear"></div>
<div class="cat_navbar">
<?php 
if ( file_exists(DIR_WS_MODULES.'cat_navbar.php') ) {
require(DIR_WS_MODULES.'cat_navbar.php');
}
?>
</div>
<div class="clear"></div>

<!--Breadcrumbs -->
<div class="grid_6 alpha" >
<?php echo $breadcrumb->trail(' &raquo; '); ?>  	
</div>
<!--currencies/manufacturers in header-->
<div class="grid_3 push_3 omega">
<div class="rightfloat width-fifty">
<?php
 	if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
    include(DIR_WS_BOXES . 'currencies.php');
    }
?>
</div>
<div class="rightfloat width-fifty">

<?php 
   	if ((USE_CACHE == 'true') && empty($SID)) {
  	  echo tep_cache_manufacturers_box();
  	} else {
  	  include(DIR_WS_BOXES . 'manufacturers.php');
  	}

 ?>
</div></div>
 <div class="clear"></div>
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<div class="header-error"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></div>  
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<div class="header-info"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></div>
<!-- END HEADER --> 
<?php
  }
?>