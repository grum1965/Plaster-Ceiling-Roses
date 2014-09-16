<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);

  require(DIR_WS_INCLUDES . 'template_top.php');

  if ($product_check['total'] < 1) {
?>

<div class="contentContainer">
  <div class="contentText" style="">
    <?php echo TEXT_PRODUCT_NOT_FOUND; ?>
  </div>

  <div style="float: right;">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link(FILENAME_DEFAULT)); ?>
  </div>
</div>


<?php
  } else {
    $product_info_query = tep_db_query("select p.products_id, pd.products_name, pd.products_description, p.products_model, p.products_quantity, p.products_image, p.products_weight, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_sample_code from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

    tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $products_price = '<del>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
    } else {
     $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
    }

    if (tep_not_null($product_info['products_model'])) {
      $products_name = $product_info['products_name'] . '<br /><span class="smallText">[' . $product_info['products_model'] . '] </span>';
    } else {
      $products_name = $product_info['products_name'];
    }
?>

<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product')); ?>



  <div class="contentContainer" style="margin-left:10px;"> 
<table border="0" cellspacing="0" cellpadding="0" align="center" width="390" >
  <tr>
    <td height="137" bgcolor="#B2B2B2" valign="bottom"> 

</td>
  </tr>
  <tr>
    <td>


<h1 style="margin-left:0px; margin-top:0px; font-family:'Palatino Linotype'"><font size="+2" color="#5b5b5b"><?php echo $products_name; ?><br /></font></h1>
<br />
  <div class="contentText" >
<?php
    if (tep_not_null($product_info['products_image'])) {
      $pi_query = tep_db_query("select image, htmlcontent from " . TABLE_PRODUCTS_IMAGES . " where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");

      if (tep_db_num_rows($pi_query) > 0) {
?>


    <div id="piGal" style="">
      <ul style="margin-top:-7px;">

<?php
        $pi_counter = 0;
        while ($pi = tep_db_fetch_array($pi_query)) {
          $pi_counter++;

          $pi_entry = '        <li><a href="';

          if (tep_not_null($pi['htmlcontent'])) {
            $pi_entry .= '#piGalimg_' . $pi_counter;
          } else {
            $pi_entry .= tep_href_link(DIR_WS_IMAGES . $pi['image']);
          }

          $pi_entry .= '" target="_blank" rel="fancybox">' . tep_image(DIR_WS_IMAGES . $pi['image']) . '</a>';

          if (tep_not_null($pi['htmlcontent'])) {
            $pi_entry .= '<div style="display: none;"><div id="piGalimg_' . $pi_counter . '">' . $pi['htmlcontent'] . '</div></div>';
          }

          $pi_entry .= '</li>';

          echo $pi_entry;
        }
?>

      </ul>
    </div>
<script type="text/javascript">
$('#piGal ul').bxGallery({
  maxwidth: 340,
  maxheight: 247,
  thumbwidth: <?php echo (($pi_counter > 1) ? '75' : '0'); ?>,
  thumbcontainer: 300,
  load_image: 'ext/jquery/bxGallery/spinner.gif'
});
</script>
<?php
      } else {
?>
    <div id="piGal" style="float:inherit;">
      <?php echo '<a href="' . tep_href_link(DIR_WS_IMAGES . $product_info['products_image']) . '" target="_blank" rel="fancybox">&nbsp;&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . $product_info['products_image'], addslashes($product_info['products_name']), null, null, 'hspace="5" vspace="5"') . '</a>'; ?>
    </div>
<?php
      }
?>
<script type="text/javascript">
$("#piGal a[rel^='fancybox']").fancybox({
  cyclic: true
});
</script>
<?php
    }
?>


</td>
  </tr>
  <tr>
    <td>

 <div class="buttonSet"></div>

<p style="margin-left:10px; margin-right:5px; font-size:14px; color:#5b5b5b">  
<?php echo stripslashes($product_info['products_description']); ?>
</p>
<div align="right">
<table width="380" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
  <tr><td colspan="3"><B>&nbsp;PRODUCT OPTIONS:</B></td></tr>
  <tr bgcolor="#FFFFFF">
    <td><font size="+2" color="#5b5b5b"><?php echo $products_price; ?></font><font size="-1" color="#5b5b5b" ><?php echo $product_info['products_url']; ?></font></td>
    <td align="right"><span class="buttonAction" ><?php echo TEXT_ENTER_QUANTITY . ": " . tep_draw_input_field('cart_quantity', '0', 'size=2'); ?></td><td>	<?php echo tep_draw_hidden_field('products_id', $product_info['products_id']) . tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', null, 'primary'); ?></span>	
</td>
  </tr>
  
<!-- Sample Updated to allow correct quantity to pass through - BBG Design 2011 -->
 <?php if (($product_info['products_sample_code'])>1) { ?>
  <tr bgcolor="#FFFFFF">
    <td><font size="+2" color="#5b5b5b">&pound;2.50</font> SAMPLE</td>
    <td align="right"><span class="buttonAction" ><?php echo TEXT_ENTER_QUANTITY . ": " . tep_draw_input_field('cart_quantity2', '0', 'size=1'); ?></td><td>	<?php echo tep_draw_hidden_field('products_sample_code', $product_info['products_sample_code']) . tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', null, 'primary'); ?></span>	
</td>
  </tr>
  <?php
  } else { ?>
  
  <?php
      }
?>
  
</table>
     
<br />
</div>

<?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
?>

    <p><?php echo TEXT_PRODUCT_OPTIONS; ?></p>

    <p>
<?php
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$HTTP_GET_VARS['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $products_options_array = array();
        $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
        while ($products_options = tep_db_fetch_array($products_options_query)) {
          $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          if ($products_options['options_values_price'] != '0') {
            $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          }
        }

        if (is_string($HTTP_GET_VARS['products_id']) && isset($cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']])) {
          $selected_attribute = $cart->contents[$HTTP_GET_VARS['products_id']]['attributes'][$products_options_name['products_options_id']];
        } else {
          $selected_attribute = false;
        }
?>
      <strong><?php echo $products_options_name['products_options_name'] . ':'; ?></strong><br /><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?><br />
<?php
      }
?>
    </p>

<?php
    }
?>

    <div style="clear: both;"></div>

<?php
    if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
?>

    <p style="text-align: center;"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?></p>

<?php
    }
?>

  </div>

<?php
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS . " where products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and reviews_status = 1");
    $reviews = tep_db_fetch_array($reviews_query);
?>

 

<?php
    if ((USE_CACHE == 'true') && empty($SID)) {
      echo tep_cache_also_purchased(3600);
    } else {
      include(DIR_WS_MODULES . FILENAME_ALSO_PURCHASED_PRODUCTS);
    }
?>

</div>
</td>
  </tr>
</table>
</form>
</div>
<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
