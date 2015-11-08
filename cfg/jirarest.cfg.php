<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 *
 * Integration with JIRA Using REST Interface.
**/

//-----------------------------------------------------------------------------------------
/* The following parameters are not in use. */
define('BUG_TRACK_DB_TYPE',    '[Not in Use]');
define('BUG_TRACK_DB_NAME',    '[Not in Use]');
define('BUG_TRACK_DB_CHARSET', '[Not in Use]');
define('BUG_TRACK_DB_USER',    '[Not in Use]');
define('BUG_TRACK_DB_PASS',    '[Not in Use]');
//-----------------------------------------------------------------------------------------

/** The Username being used by JIRA logon */
define('BUG_TRACK_USERNAME', '<username>');

/** The Password being used by JIRA logon*/
define('BUG_TRACK_PASSWORD', '<password>');

/** link of the web server for JIRA. This is an example, replace before use */
define('BUG_TRACK_HREF',"http://jira/rest/api/latest/");

/** Link to view an issue in Jira (the key will be appended to this link). This is an example, replace before use*/
define('BUG_TRACK_SHOW_BUG_HREF', "http://jira/browse/");

/** Link to Jira for creating a new issue. This is an example, replace before use*/
define('BUG_TRACK_ENTER_BUG_HREF',"http://jira/secure/CreateIssue!default.jspa");

?>
