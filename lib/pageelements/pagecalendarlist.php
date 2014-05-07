<?php

require_once 'lib/utils.php';
require_once 'lib/xml.php';

class PageCalendarList extends PageContainer{
    
    /**
     *@var DatabaseConnection
     */
    private $dbConnection;
    
    private $userID;
    
    public function __construct($dbConnection) {
        $this->dbConnection = $dbConnection;
        $this->userID = Session::getUserID();
    }
    
    
    
    public function toXML() {
        /**
         * SELECT   id, name, owner_id 
         * FROM group_calendar_relations as gcr
         * WHERE user_id = Session::getUserID()
         * JOIN calendars as c ON c.id = gcr.calendar_id
         * 
         * SELECT 
         * FROM
         *      (SELECT g.id 
         *      FROM group_user_relations gur
         *      WHERE gur.user_id = Session::getUserID())   
         * JOIN (SELECT );
         */
        
        $result = $this->dbConnection->query('
            SELECT c.id, c.name, c.owner_id 
            FROM ( 
                SELECT gcr.calendar_id 
                FROM group_calendar_relations as gcr 
                JOIN ( 
                    SELECT gur.group_id 
                    FROM group_user_relations as gur 
                    WHERE gur.user_id = '.Session::getUserID().
                    ') as groups 
                ON gcr.group_id = groups.group_id 
                ) as t 
            JOIN calendars as c ON c.id = t.calendar_id 
            ');    
       
        $calendarList = new XMLElement('div');
        for ($i = 0; $i < mysql_num_rows($result); $i++) {
            $a = $this->dbConnection->fetchRow($result);
            $item = new PageCalendarListItem($a[1], $a[2]);
            $calendarList->addChild($item->toXML());
        }
       
        return $calendarList;
    }
    
}
