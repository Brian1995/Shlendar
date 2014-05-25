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
        return $element;
    }
    
    function createMemberItem($id, $name, $rights){
        $element = new XMLElement('div');
        $element->addAttribute('class', 'calendar-member-item');
        
        
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
        $element = new XMLElement('div');
        $element->setAttribute('class', 'calendar-non-member-item');
        $name = new PageText($name);
        $add = new XMLElement('form');
        $addButton = new PageButton('Hinzufügen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square', PageFontIcon::NORMAL, TRUE));
        $add->addChild($addButton->toXML());
        
        $element->addChild($name->toXML());
        $element->addChild($add);
        return $element;
    }
    
    function toXML() {
        $element = parent::toXML();
        $element->addChild(new XMLText("Kalender bearbeiten"));
        $element->addChild($this->createNonMembers());
        return $element;
    }
}
