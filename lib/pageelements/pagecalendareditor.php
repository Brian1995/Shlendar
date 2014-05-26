<?php
/**
 * Description of pagecalendareditor
 *
 * @author Brian
 */
class PageCalendarEditor extends PageElement{
    
    /** @var DatabaseConnection */
    private $db;
    
    private $calendarID;
    
    function __construct(DatabaseConnection $db, $calendarID) {
        parent::__construct('div');
        $this->setProperty('id', 'calender-editor');
        $this->db = $db;
        $this->calendarID = $calendarID;
    }
    
    function createCalendarMembers(){
        $element = new XMLElement('div');
		$result = $this->db->query("SELECT * FROM group_calendar_relations WHERE calendar_id = '%s';", $this->calendarID);
		while ($row = mysql_fetch_row($query)) {
			
		}
		
		
        return $element;
    }
    
    function createMemberItem($id, $name, $rights){
        $element = new XMLElement('div');
        $element->addAttribute('class', 'calendar-member-item');
        
		$delete_url = URL::createCurrent();
		$delete_url->setDynamicQueryParameter('referrer', $delete_url);
		$delete_url->setDynamicQueryParameter('action', 'remove-group-from-calendar');
		$delete_url->setDynamicQueryParameter('action', 'remove-group-from-calendar');
		
		$nameText = new PageText($name);
		$rightsText = new PageText($rights);
		
		$deleteButton = new PageButton('Entfernen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE));
		$delete = new XMLElement('form', 'action', $delete-url);
		
        $element->addChild($nameText);
        $element->addChild($rightsText);		
        return $element;
    }
    
    function createNonMembers(){
        $element = new XMLElement('div');
        $result = $this->db->query("SELECT * FROM groups WHERE user_id = '%s';", Session::getUserID());
        while($row = mysql_fetch_row($result)){
            $element->addChild($this->createNonMemberItem($row[0], $row[1]));
        }
        return $element;
    }
    
    function createNonMemberItem($id, $name){
		$addUrl = URL::createCurrent();
		$addUrl->setDynamicQueryParameter('action', 'add-group-to-calendar');
		$addUrl->setDynamicQueryParameter('group', $id);
		$addUrl->setQueryParameter('referrer', URL::createCurrent());
		//TODO referrer
        $element = new XMLElement('div');
        $element->setAttribute('class', 'calendar-non-member-item');
        $name = new PageText($name);
        $add = new XMLElement('form', 'action', $addUrl, 'method', 'post');
        $addButton = new PageButton('Hinzufügen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square', PageFontIcon::NORMAL, TRUE));
        $add->addChild($addButton->toXML());
        
        $element->addChild($name->toXML());
        $element->addChild($add);
        return $element;
    }
    
    function toXML() {
        $element = parent::toXML();
        $element->addChild($this->createNonMembers());
        return $element;
    }
	
	public static function addGroupToCalendar(DatabaseConnection $db, $calendarID, $groupID, $rights){
		$db->query("INSERT INTO group_calendar_relations (group_id, calendar_id, rights) VALUES ('%s', '%s', '%s')", $groupID, $calendarID, $rights);
	}
}
