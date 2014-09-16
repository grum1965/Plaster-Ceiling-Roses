<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Changed for Guest account by BBG Design 15/05/2011
*/

define('NAVBAR_TITLE', 'Login');
define('HEADING_TITLE', 'Welcome, Please Sign In');

define('HEADING_NEW_CUSTOMER', 'New Customer');
define('TEXT_NEW_CUSTOMER', 'I am a new customer.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.');

define('HEADING_RETURNING_CUSTOMER', 'Returning Customer');
define('TEXT_RETURNING_CUSTOMER', 'I am a returning customer.');

define('TEXT_PASSWORD_FORGOTTEN', 'Password forgotten? Click here.');

// guest_account start
define('HEADING_GUEST_CUSTOMER', 'Quick Checkout');
// guest_account end
define('TEXT_LOGIN_ERROR', 'Error: No match for E-Mail Address and/or Password.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><strong>Note:</strong></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win();">[More Info]</a>');
define('TEXT_GUEST_CUSTOMER', 'I do not want to create an account.');
define('TEXT_GUEST_CUSTOMER_INTRODUCTION', 'Choose this option if you just want to checkout. You will still need to give us your Billing Address on the following page but we will not create a Password Account for you. <br><br>Please choose <STRONG><font color="#000000">Returning Customer</font></STRONG> if you have already registered your E-Mail address with ' . STORE_NAME . '.');
?>
