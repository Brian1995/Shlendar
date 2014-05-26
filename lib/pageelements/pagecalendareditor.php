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
		$title = new PageText("Gruppen entfernen");
		$element->addChild($title->toXML());
		
		$result = $this->db->query("SELECT * FROM group_calendar_relations JOIN groups ON group_id = groups.id WHERE calendar_id = '%s';", $this->calendarID);
		var_dump(mysql_num_rows($result));
		while ($row = mysql_fetch_row($result)) {
			$item = $this->createMemberItem($row[0], $row[1], $row[5], $row[3]);
			$element->addChild($item);
		}
        return $element;
    }
    
    function createMemberItem($realationID, $groupID, $name, $rights){
        $element = new XMLElement('div');
        $element->addAttribute('class', 'calendar-member-item');
        
		$deleteUrl = URL::createCurrent();
		$deleteUrl->setDynamicQueryParameter('referrer', URL::createCurrent());
		$deleteUrl->setDynamicQueryParameter('action', 'remove-group-from-calendar');
		$deleteUrl->setDynamicQueryParameter('id', $realationID);
		
		$nameText = new PageText($name);
		$rightsText = new PageText($rights);
		
		$deleteButton = new PageButton('Entfernen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE));
		$delete = new XMLElement('form', 'action', $deleteUrl);
		$delete->addChild($deleteButton->toXML());
		
        $element->addChild($nameText->toXML());
        $element->addChild($rightsText->toXML());		
		$element->addChild($delete);
        return $element;
    }
    
    function createNonMembers(){
        $element = new XMLElement('div');
		$title = new PageText("Gruppen hinzufügen");
		$element->addChild($title->toXML());
		
        $result = $this->db->query("SELECT * FROM groups WHERE user_id = '%s' AND id NOT IN( SELECT group_id FROM group_calendar_relations WHERE calendar_id = '%s');", Session::getUserID(), $this->calendarID);
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
		$element->addChild($this->createCalendarMembers());
        $element->addChild($this->createNonMembers());
        return $element;
    }
	
	public static function addGroupToCalendar(DatabaseConnection $db, $calendarID, $groupID, $rights){
		$db->query("INSERT INTO group_calendar_relations (group_id, calendar_id, rights) VALUES ('%s', '%s', '%s')", $groupID, $calendarID, $rights);
	}
}
