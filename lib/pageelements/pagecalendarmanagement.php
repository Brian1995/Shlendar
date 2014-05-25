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
    
    function createInsertDialog(){
        $submitUrl = URL::createStatic();
        $submitUrl->setDynamicQueryParameter('action', 'insert-calendar');
        $submitUrl->setDynamicQueryParameter('referrer', URL::createCurrent());
        
        $element = new XMLElement('div');
        $element->addChild($title = new XMLText('Kalender ertellen'));
        $element->addChild($form = new XMLElement('form', 'name', 'calendar-insert-from', 'action', $submitUrl, 'method', 'post'));
        $form->addChild(new XMLElement('input', 'type', 'text', 'name', 'calendar-name'));
        $form->addChild(new XMLElement('input', 'type', 'submit', 'action'));
        return $element;
    }
    
    function createListItem($id, $name){
        $element = new XMLElement('div');
        
        $deleteUrl = URL::createStatic();
        $deleteUrl->setDynamicQueryParameter('action', 'delete-calendar');
        $deleteUrl->setDynamicQueryParameter('id', $id);
        $deleteUrl->setDynamicQueryParameter('referrer', URL::createCurrent());
        
        $delete = new XMLElement('form', 'class', 'calendaritem-delete', 'action', $deleteUrl, 'method', 'post');
        $deleteButton = new PageButton('Löschen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE));
        $delete->addChild($deleteButton->toXML());
        
        $element->addChild(new XMLText($name));
        $element->addChild($delete);
        
        return $element;
    }
    
    function createCalendarList(){
        $element = new XMLElement('div');
        $result = $this->db->query("SELECT * FROM calendars WHERE user_id = '%s';", Session::getUserID());
        while ($row = mysql_fetch_row($result)){
            $element->addChild($this->createListItem($row[0], $row[1]));
        }
        return $element;
    }
    
    function toXML() {
        $element = parent::toXML();
        $element->addChild(new XMLText('Kalender verwalten'));
        $element->addChild($this->createInsertDialog());
        $element->addChild($this->createCalendarList());
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
