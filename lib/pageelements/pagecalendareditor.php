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
        $element = new PageContainer('div');
		$element->addChild(new PageTextContainer(PageTextContainer::H2, "Gruppen entfernen"));
		
		$result = $this->db->query("SELECT * FROM group_calendar_relations JOIN groups ON group_id = groups.id WHERE calendar_id = '%s';", $this->calendarID);
		while ($row = mysql_fetch_row($result)) {
			$item = $this->createMemberItem($row[0], $row[1], $row[5], $row[3]);
			$element->addChild($item);
		}
        return $element;
    }
    
    function createMemberItem($realationID, $groupID, $name, $rights){
        $element = new PageContainer('div', 'class', 'calendar-member-item group');
        
		$deleteUrl = URL::createStatic();
		$deleteUrl->setDynamicQueryParameter('referrer', URL::createCurrent());
		$deleteUrl->setDynamicQueryParameter('action', 'remove-group-from-calendar');
		$deleteUrl->setDynamicQueryParameter('id', $realationID);
		
		$nameText = new PageTextContainer('div', $name);
		$nameText->setProperty('class', 'entry stretch flexible');
		$rightsText = new PageTextContainer('div', $rights == 0 ? 'lesen' : 'lesen/schreiben');
		$rightsText->setProperty('class', 'entry');
		
		$deleteContainer = new PageContainer('form', 'action', $deleteUrl, 'method', 'post', 'class', 'entry');
		$deleteContainer->addChild($deleteButton = new PageButton('Entfernen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE)));
		$deleteButton->setProperty('class', 'fill');
		
        $element->addChild($nameText);
        $element->addChild($rightsText);		
		$element->addChild($deleteContainer);
        return $element;
    }
    
    function createNonMembers(){
        $element = new PageContainer('div');
		$element->addChild($title = new PageTextContainer(PageTextContainer::H2, "Gruppen hinzufügen"));
		
        $result = $this->db->query("SELECT * FROM groups WHERE user_id = '%s' AND id NOT IN( SELECT group_id FROM group_calendar_relations WHERE calendar_id = '%s');", Session::getUserID(), $this->calendarID);
        while($row = mysql_fetch_row($result)){
            $element->addChild($this->createNonMemberItem($row[0], $row[1]));
        }
        return $element;
    }
    
    function createNonMemberItem($id, $name){
		$addUrl = URL::createStatic();
		$addUrl->setDynamicQueryParameter('action', 'add-group-to-calendar');
		$addUrl->setDynamicQueryParameter('group', $id);
		$addUrl->setDynamicQueryParameter('id', $this->calendarID);
		$addUrl->setDynamicQueryParameter('referrer', URL::createCurrent());
		
        $element = new PageContainer('div', 'class', 'calendar-non-member-item group');
        $name = new PageTextContainer('div', $name);
		$name->setProperty('class', 'entry stretch flexible');
		
		$option1 = new PageTextContainer('option', 'lesen');
		$option1->setProperty('value', '0');
		$option2 = new PageTextContainer('option', 'lesen/schreiben');
		$option2->setProperty('value', '1');
				
		$selectContainer = new PageContainer('div', 'class', 'entry');
		$selectContainer->addChild($select = new PageContainer('select', 'name', 'rights', 'class', 'fill'));
		$select->addChild($option1);
		$select->addChild($option2);
		
		$addButtonContainer = new PageContainer('div', 'class', 'entry');
        $addButtonContainer->addChild($addButton = new PageButton('Hinzufügen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square', PageFontIcon::NORMAL, TRUE)));
		$addButton->setProperty('class', 'fill');
		
        $add = new PageContainer('form', 'action', $addUrl, 'method', 'post', 'class', 'entry group');
        $add->addChild($selectContainer);
        $add->addChild($addButtonContainer);
        
        $element->addChild($name);
        $element->addChild($add);
        return $element;
    }
    
    function toXML() {
        $element = parent::toXML();
		$element->addChild($this->createCalendarMembers()->toXML());
        $element->addChild($this->createNonMembers()->toXML());
        return $element;
    }
	
	public static function addGroupToCalendar(DatabaseConnection $db, $calendarID, $groupID, $rights){
		return $db->query("INSERT INTO group_calendar_relations (group_id, calendar_id, rights) VALUES ('%s', '%s', '%s')", $groupID, $calendarID, $rights);
	}
	
	public static function removeGroupFromCalendar(DatabaseConnection $db, $id){
		$result = $db->query("DELETE FROM group_calendar_relations WHERE id = '%s';", $id);
		return $result;
	}
	
	public static function groupCanEdit(DatabaseConnection $db, $calendar, $group){
		$result = $db->query("SELECT rights FROM group_calendar_realtions WHERE calendar_id = '%s' AND group_id = '%s';", $calendar, $group);
		$row = mysql_fetch_array($result);
		if($row[0] == 0){
			return false;
		} else if($row[0] == 1){
			return true;
		} else {
			return false;
		}
	}
}
