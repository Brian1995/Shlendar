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
    
    function toXML() {
        $element = parent::toXML();
        $element->addChild(new XMLText('Kalender verwalten'));
        $element->addChild($this->createInsertDialog());
        return $element;
    }
    
    public static function insertCalendar(DatabaseConnection $db){
        $user = Session::getUserID();
        $calendarName = filter_input(INPUT_POST, 'calendar-name');
        return $db->query("INSERT INTO calendars (name, user_id) VALUES ('%s', '%s')", $calendarName, $user);
    }
}
