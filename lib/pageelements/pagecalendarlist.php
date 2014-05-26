<?php

require_once 'lib/utils.php';

class PageCalendarList extends PageContainer {
    
    /**
     *@var DatabaseConnection
     */
    private $dbConnection;
    
    private $userID;
    
    public function __construct($dbConnection) {
		parent::__construct();
        $this->dbConnection = $dbConnection;
        $this->userID = Session::getUserID();
    }
    
    public function toXML() {
		$element = parent::toXML();
		$element->setAttribute('id', 'sidebar-calendars');
		
        $result = $this->dbConnection->query("
            SELECT DISTINCT c.id, c.name 
            FROM ( 
                SELECT gcr.calendar_id 
                FROM group_calendar_relations as gcr 
                JOIN ( 
                    SELECT gur.group_id 
                    FROM group_user_relations as gur 
                    WHERE gur.user_id = '%s'
                    ) as groups 
                ON gcr.group_id = groups.group_id 
                ) as t 
            JOIN calendars as c		ON c.id = t.calendar_id
			UNION DISTINCT 
				SELECT ca.id, ca.name
				FROM calendars as ca
				WHERE ca.user_id = '%s';", Session::getUserID(), Session::getUserID());
        
        $container = new XMLElement('div');
        $container->addAttribute('class', 'action-container');
        $rowCount = $this->dbConnection->countRows($result);
        while($a = mysql_fetch_row($result)) {
            $item = new PageCalendarListItem($a[0], $a[1]);
            $container->addChild($item->toXML());
        }
        
		$header = new PageTextContainer(PageTextContainer::H2, 'Kalender');
		$element->addChild($header->toXML());
        $element->addChild($container);
        return $element;
    }
}
