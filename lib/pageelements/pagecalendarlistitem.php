<?php

class PageCalendarListItem {
    
    private $id = NULL;
    private $name = NULL;
    private $owner = NULL;
    
    function __construct($id, $name, $owner) {
        $this->id = $id;
        $this->name = $name;
        $this->owner = $owner;
    }
    
    public function toXML(){
        
        $name = new XMLElement('p');
        $name->addChild(new XMLText($this->name));
        $owner = new XMLElement('p');
        $owner->addChild(new XMLText($this->owner));
        
        $text = new XMLElement('h3');
        $text->addChild($name);
        $text->addChild($owner);
        
        $url = URL::urlFromRelativePath('index.php');
        $url->setQueryParameter('calendar', $this->id);
        $link = new XMLElement('a');
        $link->addAttribute('href', $url);
        $link->addChild($text);
        
        $listItem = new XMLElement('div');
        $listItem->addChild($link);
        $listItem->addAttribute('id', 'calendar-item');
        return $listItem;
    }
}
