<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 *
 * Integration with JIRA Using REST Interface.
 * @author Sajeewa Dayaratne
 *
**/
/** Interface name */
define('BUG_INTERFACE_CLASSNAME',"jirarestInterface");

require_once(TL_ABS_PATH . "/third_party/fayp-jira-rest/RestRequest.php");
require_once(TL_ABS_PATH . "/third_party/fayp-jira-rest/Jira.php");


class jirarestInterface extends bugtrackingInterface
{
    // Not used
    var $dbHost = null;
    var $dbConnection = null;
    var $dbCharSet = null;
    var $dbType = null;

    // New Connections Variables
    private $JiraClient;
    var $JiraUsername = BUG_TRACK_USERNAME;
    var $JiraPassword = BUG_TRACK_PASSWORD;
    var $JiraHost = BUG_TRACK_HREF;
    var $connected = false;

    /**
     * Constructor of bugtrackingInterface
     *
     **/
    function jirarestInterface()
    {
        $this->enterBugURL  = BUG_TRACK_ENTER_BUG_HREF;
        $this->showBugURL   = BUG_TRACK_SHOW_BUG_HREF;

        if ( !$this->connect() )
        {
            tLog('Connect to Bug Tracker URL ['.$this->JiraHost.'] failed!!! ', 'ERROR');
        }
    }

    // Helper functions (not called by Testlink)

    /**
     * Establishes a connection to Jira
     * 
     * @return bool returns true if the connection was established to Jira
     * 
     */
    function connect()
    {
        try
        { 
            $par = array('username' => (string)trim($this->JiraUsername),
                         'password' => (string)trim($this->JiraPassword),
                         'host'     => (string)trim($this->JiraHost));
            
            $this->JiraClient = new JiraApi\Jira($par);

            $this->JiraConnected = $this->JiraClient->testLogin();        

        }
        catch(Exception $e)
        {
            $this->JiraConnected = false;
            tLog("Got Exception from Lib ". $e->getMessage(), 'ERROR');
        }
            
        return $this->JiraConnected;
    }
    
    // End - Helpfer functions

    // Functions called by TestLink
    
    /**
      * Determine if we are connected to a Jira instance
      *
      * @return true if connected false otherwise
      *
      **/
    function isConnected()
    {
        return $this->JiraConnected;
    }
      
    /**
      * Fetch the bug summary from Jira for the given issue
      *
      * @param int id the issue key
      *
      * @return string returns the bug summary (if bug is found), or false
      *
      **/
    function getBugSummaryString($id)
    {
        if ( !$this->JiraConnected )
        {
            tLog('Cannot fetch details of issue ['.$id.'] Not connected to Jira!', 'ERROR');
            return false;
        }
      
        $issue = null;

        $issue = $this->JiraClient->getIssueStatusAndSummary($id);
      
        if(!is_null($issue) && is_object($issue) && !property_exists($issue,'errorMessages'))
        {
            return $issue->fields->summary;
        }
        else
        {
            return false;
        }
    }

    /**
     * Returns the URL which should be displayed for entering bugs
     *
     * @return string returns a complete URL
     *
     **/
    function getEnterBugURL()
    {
        return $this->enterBugURL;
    }

    /**
     * checks a bug id for validity, that means numeric only
     *
     * @return bool returns true if the bugid has the right format, false else
     **/
    function checkBugID($id)
    {
        //No error checking for now
        return true;
    }

    /**
     * return the maximum length in chars of a bug id
     * @return int the maximum length of a bugID
     */
    function getBugIDMaxLength()
    {
        return 16; // Arbitary
    }
    
    /**
     * Return the URL to the bugtracking page for viewing
     * the bug with the given id.
     *
     * @param int id the bug id
     *
     * @return string returns a complete URL to view the bug
     *
     **/
    function buildViewBugURL($id)
    {
        return ($this->showBugURL.$id);
    }

    /**
     * Returns the status in a readable form (HTML context) for the bug with the given id
     *
     * @param int id the bug id
     *
     * @return string returns the status (in a readable form) of the given bug if the bug
     *         was found, else false
     *
     **/
    function getBugStatusString($id)
    {
        if ( !$this->JiraConnected )
        {
            tLog('Cannot fetch status of issue ['.$id.'] Not connected to Jira!', 'ERROR');
            return "<unknown>";
        }
      
        $issue = null;
        $JiraKey = null;

        $issue = $this->JiraClient->getIssueStatusAndSummary($id);
      
        if(!is_null($issue) && is_object($issue) && !property_exists($issue,'errorMessages'))
        {
            $JiraKey = $issue->key;
            $JiraStatus = $issue->fields->status->name;

        }

        $status_desc = null;

        //if the bug wasn't found the status is null else we simply display the bugID with status
        if (!is_null($JiraKey))
        {
            if (strcasecmp($JiraStatus, 'closed') == 0 || strcasecmp($JiraStatus, 'resolved') == 0 )
            {
                $JiraStatus = "<del>" . $JiraStatus . "</del>";
            }
            $status_desc = "<b>" . $id . ": </b>[" . $JiraStatus  . "] " ;
        }
        else
        {
            $status_desc = "The BUG Id-".$id." does not exist in Jira";
        }
        return $status_desc;

    }

    /**
     * default implementation for generating a link to the bugtracking page for viewing
     * the bug with the given id in a new page
     *
     * @param int id the bug id
     *
     * @return string returns a complete URL to view the bug (if found in db)
     *
     **/
    function buildViewBugLink($bugID,$bWithSummary = false)
    {
        $link = "<a href='" .$this->buildViewBugURL($bugID) . "' target='_blank'>";
        $status = $this->getBugStatusString($bugID);

        if (!is_null($status))
        {
            $link .= $status;
        }
        else
        {
            $link .= $bugID;
        }
        
        if ($bWithSummary)
        {
            $summary = $this->getBugSummaryString($bugID);

            if (!is_null($summary))
            {
                $summary = iconv($this->dbCharSet,$this->tlCharSet,$summary);
                $link .= " - " . $summary;

            }
        }

        $link .= "</a>";

        return $link;
    }

    /**
    * checks if bug id is present in Jira
    * Function has to be overloaded on child classes
    *
    * @return bool
    **/
    function checkBugID_existence($id)
    {
        //For now assume it exists
        return true;
    }
    // End - Functions called by TestLink

}
?>
