<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License

  Changed for Guest account by BBG Design 15/05/2011
*/

  if ($messageStack->size('header') > 0) {
    echo '<div class="grid_24">' . $messageStack->output('header') . '</div>';
  }
?>


<div id="header" class="grid_24">
    <?php /*** Begin Header Tags SEO ***/ ?>
    <div id="storeLogo" style="padding:0px; "><table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="960" >
  <tr>
    <td align="left"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', (tep_not_null($header_tags_array['logo_text']) ? $header_tags_array['logo_text'] : STORE_NAME)) . '</a></td><td align="left"><a href="http://www.plasterceilingroses.com/index.php"><img src="images/tab_home.jpg" alt="PlasterCeilingRoses.com" title=" PlasterCeilingRoses.com " width="102" height="32" /></a></td><td align="left"><a href="http://www.plasterceilingroses.com/aboutus.php"><img src="images/tab_aboutus.jpg" alt="PlasterCeilingRoses.com" title=" PlasterCeilingRoses.com " width="131" height="32" /></a></td><td align="left"><a href="http://www.plasterceilingroses.com/FAQ.php"><img src="images/tab_FAQ.jpg" alt="PlasterCeilingRoses.com" title=" PlasterCeilingRoses.com " width="86" height="32" /></a></td><td align="left"><a href="http://www.plasterceilingroses.com/contact_us.php"><img src="images/tab_contactus.jpg" hspace="0" alt="PlasterCeilingRoses.com" title=" PlasterCeilingRoses.com " width="145" height="32" /></a></td><td align="left"><img src="../images/tab_rightcorner.jpg" /></td>'; ?>
  </tr>
</table></div>
    <?php /*** End Header Tags SEO ***/ ?>

  <div id="headerShortcuts" style="margin-right:63px; margin-top:-30px;">
<?php
  echo tep_draw_button(HEADER_TITLE_CART_CONTENTS . ($cart->count_contents() > 0 ? ' (' . $cart->count_contents() . ')' : ''), 'cart', tep_href_link(FILENAME_SHOPPING_CART));
// Guest account start

	// don't display login button if already logged in - BBG Design 14/5/2011
	  if (!tep_session_is_registered('customer_id')) {
		echo tep_draw_button(HEADER_TITLE_CHECKOUT, 'triangle-1-e', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
	  }

	// do display account and logoff buttons if logged on - BBG Design 14/5/2011
	  if (tep_session_is_registered('customer_id') && $guest_account == false) {  // Not a Guest Account
		echo tep_draw_button(HEADER_TITLE_MY_ACCOUNT, 'person', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
          }
	  if (tep_session_is_registered('customer_id')) {
	    	echo tep_draw_button(HEADER_TITLE_LOGOFF, null, tep_href_link(FILENAME_LOGOFF, '', 'SSL'));
	  }
  echo '';
// Guest account end
?>
  </div>

<script type="text/javascript">
  $("#headerShortcuts").buttonset();
</script>
</div>


<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>
