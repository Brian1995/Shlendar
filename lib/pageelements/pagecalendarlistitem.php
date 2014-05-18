<?php

class PageCalendarListItem {
    
    private $id = NULL;
    private $name = NULL;
    
    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public function toXML(){
        
        $name = new XMLElement('p');
        $name->addChild(new XMLText($this->name));
        
        $text = new XMLElement('h3');
        $text->addChild($name);
        
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
