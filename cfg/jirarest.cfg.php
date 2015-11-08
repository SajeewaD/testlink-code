<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 *
 * Integration with JIRA Using REST Interface.
**/

//-----------------------------------------------------------------------------------------
/* The following parameters are not in use. */
define('BUG_TRACK_DB_TYPE', '[Not in Use]');
define('BUG_TRACK_DB_NAME', '[Not in Use]');
define('BUG_TRACK_DB_CHARSET', '[Not in Use]');
define('BUG_TRACK_DB_USER', '[Not in Use]');
define('BUG_TRACK_DB_PASS', '[Not in Use]');
//-----------------------------------------------------------------------------------------

/** The Username being used by JIRA logon */
define('BUG_TRACK_USERNAME', 'testlink');

/** The Password being used by JIRA logon*/
define('BUG_TRACK_PASSWORD', 'testlinksqa');
/** link of the web server for JIRA*/
define('BUG_TRACK_HREF',"http://jira/rest/api/latest/");

/** link of the web server for jira ticket*/
define('BUG_TRACK_SHOW_BUG_HREF', "http://jira/browse/");
/** link of the web server for creating new jira ticket*/
define('BUG_TRACK_ENTER_BUG_HREF',"http://jira/secure/CreateIssue!default.jspa");

?>
