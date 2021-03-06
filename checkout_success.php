<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License

  Changed for Guest account by BBG Design 18/05/2011
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the shopping cart page
  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update')) {
    $notify_string = '';

    if (isset($HTTP_POST_VARS['notify']) && !empty($HTTP_POST_VARS['notify'])) {
      $notify = $HTTP_POST_VARS['notify'];

      if (!is_array($notify)) {
        $notify = array($notify);
      }

      for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
        if (is_numeric($notify[$i])) {
          $notify_string .= 'notify[]=' . $notify[$i] . '&';
        }
      }

      if (!empty($notify_string)) {
        $notify_string = 'action=notify&' . substr($notify_string, 0, -1);
      }
    }

    tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
    $orders = tep_db_fetch_array($orders_query);

    $products_array = array();
    $products_query = tep_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);
    }
  }

  require(DIR_WS_INCLUDES . 'template_top.php');
?>
<div class="contentContainer" style="margin-left:10px;">
<table border="0" cellspacing="0" cellpadding="0" align="center" width="390" >
  <tr>
    <td height="137" bgcolor="#B2B2B2" valign="bottom"> 

</td>
  </tr></table>
<font size="+3" color="#000000"><?php echo HEADING_TITLE; ?></font>
<?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?>


  <div class="contentText" style="margin-left:5px;">
    <?php echo TEXT_SUCCESS; ?>
  </div>

  <div class="contentText" style="margin-left:5px">

<? 
if (GUEST_ON == 'true') {
    //delete the temporary account
    tep_db_query("delete from " . TABLE_CUSTOMERS . " where guest_flag = '1'");
  }
?>
<?php
  if ($guest_account == false) { // not a guest Account
    if ($global['global_product_notifications'] != '1') {
      echo TEXT_NOTIFY_PRODUCTS . '<br><p class="productsNotifications">';

      $products_displayed = array();
      for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
        if (!in_array($products_array[$i]['id'], $products_displayed)) {
          echo tep_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br>';
          $products_displayed[] = $products_array[$i]['id'];
        }
      }

      echo '</p>';
    } else {
      echo TEXT_SEE_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_OWNER;
    }
  } else { // guest accounts action
    echo TEXT_GUEST_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_OWNER;
  } // guest account end
?>

  </div>

  <div class="contentText" style="margin-left:5px">
    <h3><?php echo TEXT_THANKS_FOR_SHOPPING; ?></h3>
  </div>

<?php
  if (DOWNLOAD_ENABLED == 'true') {
    include(DIR_WS_MODULES . 'downloads.php');
  }
?>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'primary'); ?></span>
  </div>
</div>

</form>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
