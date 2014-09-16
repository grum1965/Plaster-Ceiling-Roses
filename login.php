<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License

  Changed for Guest account by BBG Design 15/05/2011
*/	

  require('includes/application_top.php');

// Guest account start - reset to false
   $guest_account = false;
// Guest account end

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $error = false;
  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'process') && isset($HTTP_POST_VARS['formid']) && ($HTTP_POST_VARS['formid'] == $sessiontoken)) {
    $email_address = tep_db_prepare_input($HTTP_POST_VARS['email_address']);
    $password = tep_db_prepare_input($HTTP_POST_VARS['password']);

// Check if email exists
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_customer_query)) {
      $error = true;
    } else {
      $check_customer = tep_db_fetch_array($check_customer_query);
// Check that password is good
      if (!tep_validate_password($password, $check_customer['customers_password'])) {
        $error = true;
      } else {
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }

// migrate old hashed password to new phpass password
        if (tep_password_type($check_customer['customers_password']) != 'phpass') {
          tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . tep_encrypt_password($password) . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");
        }

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

// reset session token
        $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());

// restore cart contents
        $cart->restore_contents();

        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          tep_redirect($origin_href);
        } else {
          tep_redirect(tep_href_link(FILENAME_DEFAULT));
        }
      }
    }
  }

  if ($error == true) {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<div class="contentContainer" style="margin-left:5px;">
<table width="400" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="137" bgcolor="#B2B2B2" valign="bottom">

</td>
  </tr>
</table>
<h1 style="color:#000000; margin-left:5px;"><?php echo HEADING_TITLE; ?></h1>
<em>
<h4 style="margin-left:10px">It is important to log in to manage your order. By creating an account you can view when your order is dispatched. Your personal information will NOT be shared with any third parties or stored for any other reason than your order.</h4></em>
<?php
  if ($messageStack->size('login') > 0) {
    echo $messageStack->output('login');
  }
?>

<div style="width: 38%; float: left; margin-left:20px;">
  <h2><?php echo HEADING_NEW_CUSTOMER; ?></h2>

  <div class="contentText">
    <p><?php echo TEXT_NEW_CUSTOMER; ?></p>
    <p><?php echo TEXT_NEW_CUSTOMER_INTRODUCTION; ?></p>

    <p align="right"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL')); ?></p>
  </div>
</div>

<div class="contentContainer" style="width: 42%; float: left; border-left: 1px dashed #ccc; padding-left: 3%; margin-left: 3%;">
  <h2><?php echo HEADING_RETURNING_CUSTOMER; ?></h2>

  <div class="contentText" style="margin-left:-5px; margin-right:10px;">
    <p><?php echo TEXT_RETURNING_CUSTOMER; ?></p>

    <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', '', true); ?>

    <table border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td class="fieldKey"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
        <td class="fieldValue"><?php echo tep_draw_input_field('email_address'); ?></td>
      </tr>
      <tr>
        <td class="fieldKey"><?php echo ENTRY_PASSWORD; ?></td>
        <td class="fieldValue"><?php echo tep_draw_password_field('password'); ?></td>
      </tr>
    </table>

    <p><?php echo '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></p>

    <p align="right"><?php echo tep_draw_button(IMAGE_BUTTON_LOGIN, 'key', null, 'primary'); ?></p>

<?php
// guest-account start
if (GUEST_ON == 'true') {
?>
	<table>
	    <tr>
              <td colspan="2" class="main" width="100%" valign="top"><b><?php echo HEADING_GUEST_CUSTOMER; ?></b></td>
	    </tr>
	    <tr>
              <td colspan="2"width="100%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="1" class="infoBox">
                <tr>
                  <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBoxContents">
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"class="main" valign="top"><?php echo TEXT_GUEST_CUSTOMER . '<br><br>' . TEXT_GUEST_CUSTOMER_INTRODUCTION; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                  </tr>
                  <tr>
                    <td colspan="2" width="100%" align="right" valign="top"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link(FILENAME_CREATE_ACCOUNT, 'guest_account=true', 'SSL')); 
		
					
					?>
					
					</td>
                  </tr>
	        </table></td>
              </tr>
            </table></td>
           </tr>
	   <tr>
             <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
           </tr>
	   <tr>
	</table>
<?php
}
// guest-account end
?>
    </form>
  </div>
</div>
</div>
<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
