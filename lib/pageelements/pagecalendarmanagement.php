<?php

/**
 * Description of pagecalendarmanagement
 *
 * @author Brian
 */
class PageCalendarManagement extends PageElement{
    
    /** @var DatabaseConnection */
    private $db;
    
    function __construct(DatabaseConnection $db){
        parent::__construct('div');
        $this->setProperties('id', 'calendar-management');
        $this->db =$db;
    }
    
    function toXML() {
        $element = parent::toXML();
        $element->addChild($this->createCalendarList()->toXML());
        $element->addChild($this->createInsertDialog()->toXML());
        return $element;
    }
	
    function createCalendarList(){
        $element = new PageContainer('div');
		$element->addChild($header = new PageTextContainer(PageTextContainer::H2, 'Kalender'));
		$element->addChild($list = new PageContainer('div'));
		
        $result = $this->db->query("SELECT * FROM calendars WHERE user_id = '%s';", Session::getUserID());
        while ($row = mysql_fetch_row($result)){
            $list->addChild($this->createListItem($row[0], $row[1]));
        }
        return $element;
    }
	
    function createListItem($id, $name){
        $element = new PageContainer('div', 'class', 'calendar-list-item group');
        
        $deleteUrl = URL::createStatic();
        $deleteUrl->setDynamicQueryParameter('action', 'delete-calendar');
        $deleteUrl->setDynamicQueryParameter('id', $id);
		$deleteUrl->setDynamicQueryParameter('name', $name);
        $deleteUrl->setDynamicQueryParameter('referrer', URL::createCurrent());
        
        $deleteContainer = new PageContainer('form', 'class', 'calendaritem-delete entry', 'action', $deleteUrl, 'method', 'post');
        $deleteButton = new PageButton('Löschen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE));
		$deleteButton->setProperty('class', 'fill');
        $deleteContainer->addChild($deleteButton);
        
        $editUrl = URL::createStatic();
        $editUrl->setDynamicQueryParameter('action', 'edit-calendar');
		$editUrl->setDynamicQueryParameter('id', $id);
        $editUrl->setDynamicQueryParameter('name', $name);
		
        $editContainer = new PageContainer('form', 'class', 'calendaritem-edit entry', 'action', $editUrl, 'method', 'post');
        $editButton = new PageButton('Bearbeiten', PageButton::STYLE_EDIT, PageFontIcon::create('edit', PageFontIcon::NORMAL, TRUE));
		$editButton->setProperty('class', 'fill');
        $editContainer->addChild($editButton);
		
		$nameContainer = new PageContainer('div', 'class', 'entry stretch flexible');
		$nameContainer->addChild(new PageTextContainer('div', $name));
		
		$buttonContainer = new PageContainer('div', 'class', 'entry group');
		$buttonContainer->addChild($editContainer);
		$buttonContainer->addChild($deleteContainer);
        
        $element->addChild($nameContainer);
        $element->addChild($buttonContainer);
        
        return $element;
    }
    
    function createInsertDialog(){
        $submitUrl = URL::createStatic();
        $submitUrl->setDynamicQueryParameter('action', 'insert-calendar');
        $submitUrl->setDynamicQueryParameter('referrer', URL::createCurrent());
        
        $element = new PageContainer('div');
        $element->addChild($title = new PageTextContainer(PageTextContainer::H2,'Kalender erstellen'));
        $element->addChild($form = new PageContainer('form', 'class', 'group', 'name', 'calendar-insert-from', 'action', $submitUrl, 'method', 'post'));
		$form->addChild($nameContainer = new PageContainer('div', 'class', 'entry stretch flexible'));
		$form->addChild($buttonContainer = new PageContainer('div', 'class', 'entry'));
        $nameContainer->addChild($name = new PageElement('input', 'type', 'text', 'name', 'calendar-name', 'class', 'fill'));
        $buttonContainer->addChild($button = new PageButton('Erstellen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square', PageFontIcon::NORMAL, TRUE)));
		$button->setProperty('class', 'fill');
        return $element;
    }
    
    public static function insertCalendar(DatabaseConnection $db){
        $user = Session::getUserID();
        $calendarName = filter_input(INPUT_POST, 'calendar-name');
        return $db->query("INSERT INTO calendars (name, user_id) VALUES ('%s', '%s')", $calendarName, $user);
    }
    
    public static function deleteCalendar(DatabaseConnection $db, $calendarID){
        return $db->query("DELETE FROM calendars WHERE id = '%s';", $calendarID);
    }
}
