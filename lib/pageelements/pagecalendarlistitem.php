<?php

class PageCalendarListItem {
    
    private $name = NULL;
    private $owner = NULL;
    
    function __construct($name, $owner) {
        $this->name = $name;
        $this->owner = $owner;
    }
    
    public function toXML(){
        $listItem = new XMLElement('div');
        $name = new XMLElement('p');
        $name->addChild(new XMLText('Name: '.$this->name));
        $h3 = new XMLElement('h3');
        $h3->addChild($name);
        $owner = new XMLElement('p');
        $owner->addChild(new XMLText('Besitzer: '.$this->owner));
        $h5 = new XMLElement('h5');
        $h5->addChild($owner);
        
        $listItem->addChild($h3);
        $listItem->addChild($h5);
        $listItem->addAttribute('id', 'calendar-item');
        return $listItem;
    }
}
