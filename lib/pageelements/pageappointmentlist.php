<?php
/**
 * Description of pageappointmentlist
 *
 * @author Brian
 */
class PageAppointmentList extends PageContainer{
    
    /**
     *@var DatabaseConnection
     */
    private $dbConnection;
    
    private $calendar_id;
    
    function __construct($dbConnection, $calendar_id) {
        parent::__construct();
        $this->dbConnection = $dbConnection;
        $this->calendar_id = $calendar_id;
    }
    
    public function toXML() {
        $result = $this->dbConnection->query("SELECT * FROM appointments a WHERE a.calendar_id = '%s';", $this->calendar_id);
        
        $list = new XMLElement('div');
        while($a = mysql_fetch_row($result)) {
            $item = new PageAppointmentListItem($a[0], $a[1], $a[2], $a[3], $a[4], $a[5]);
            $list->addChild($item->toXML());
        }
        
        return $list;
    }
}
