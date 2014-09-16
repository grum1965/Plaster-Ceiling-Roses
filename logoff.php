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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');
// guest account start
  tep_session_unregister('guest_account');
// guest account end

  $cart->reset();

  require(DIR_WS_INCLUDES . 'template_top.php');
// guest account start
if (GUEST_ON == 'true') {
    //delete the temporary account
    tep_db_query("delete from " . TABLE_CUSTOMERS . " where guest_flag = '1'");
  }
// guest account end
?>
<div class="contentContainer" style="margin-left:20px;">
<div style="position: relative; margin-left:-5px; " class="robots-nofollow">
    
	<div style="position: relative; padding: 0px;" class="robots-nofollow">
    
	<style type="text/css">
        div.homepage_container{
			position: absolute;            
            top: 0px;
            left: -5px;
            text-align: left;
            text-decoration: none;
            width: 390px;
        }
    </style>
</div>

    <div class="homepage_container" id="homepage0" style='display: block; '>
	
        <img src="images/plasterimg1.jpg" alt="The UKs Leading Supplier of Handmade Plaster Coving" width="390" height="250" /></div>
    
    
    <div class="homepage_container" id="homepage1" style='display: none'>
        <img src="images/pic1.jpg" alt="The UKs Leading Supplier of Handmade Plaster Coving" width="390" height="250" />    </div>
    
    <div class="homepage_container" id="homepage2" style='display: none'>
        <img src="images/pic2.jpg" alt="The UKs Leading Supplier of Handmade Plaster Coving" width="390" height="250" />    </div>
    
    <div class="homepage_container" id="homepage3" style='display: none'>
        <img src="images/pic3.jpg" alt="The UKs Leading Supplier of Handmade Plaster Coving" width="390" height="250" />    </div>
		
    <div class="homepage_container" id="homepage4" style='display: none'>
        <img src="images/pic4.jpg" alt="The UKs Leading Supplier of Handmade Plaster Coving" width="390" height="250" />    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        NextFade();
    });
    var fadeIndex = 0;
    function NextFade() {
        $('#homepage' + fadeIndex).fadeOut(3000);
        fadeIndex = (fadeIndex + 1) % 4;
        $('#homepage' + fadeIndex).fadeIn(3000, function() {
                    setTimeout(NextFade, 3000);
                });
    }
</script>

<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

<h1><?php echo HEADING_TITLE; ?></h1>


  <div class="contentText" style="margin-left:0px">
    <em><?php echo TEXT_MAIN; ?></em>
  </div>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link(FILENAME_DEFAULT)); ?></span>
  </div>
</div>

<?php
  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
