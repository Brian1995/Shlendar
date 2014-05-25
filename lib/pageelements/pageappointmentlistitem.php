<?php
/**
 * Description of pageappointmentlistitem
 *
 * @author Brian
 */
class PageAppointmentListItem{
    
    private $id;
    private $calendar_id;
    
    /**
     *
     * @var type DateTime
     */
    private $start_date;
    
    /**
     *
     * @var type DateTime
     */
    private $end_date;
    
    private $title;
    private $description;
    
    function __construct($id, $calendar_id, $start_date, $end_date, $title, $description) {
		$this->id = $id;
        $this->calendar_id = $calendar_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->title = $title;
        $this->description = $description;
    }
    
    public function toXML(){
        $title = new XMLElement('p');
        $title->addChild(new XMLText($this->title));
        
        $time = new XMLElement('p');
        $time->addChild(new XMLText("Von: ".$this->start_date." "));
        $time->addChild(new XMLText("Bis: ".$this->end_date." "));
        
        $description = new XMLElement('p');
        $description->addChild(new XMLText($this->description));
        
		$url = URL::createStatic();
		$url->setDynamicQueryParameter('action', 'deleteAppointment');
		$url->setDynamicQueryParameter('appointment', $this->id);
		$deleteBtn = new PageLink(new PageText("löschen"), $url);
		
        $item = new XMLElement('div');
        $item->addChild($title);
        $item->addChild($time);
        $item->addChild($description);
		$item->addChild($deleteBtn->toXML());
        return $item;
    }
}
