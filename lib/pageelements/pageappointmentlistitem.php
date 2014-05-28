<?php
/**
 * Description of pageappointmentlistitem
 *
 * @author Brian
 */
class PageAppointmentListItem extends PageContainer {
    
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
    
    function __construct($id, $calendar_id, $start_date, $end_date, $title, $description, $editable) {
		parent::__construct('div');
		$this->setProperty('class', 'groupv');
		$this->setProperty('style', 'border-bottom: 1px solid #95a5a6; margin-bottom: 0.5em; ');
		
		$this->id = $id;
        $this->calendar_id = $calendar_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->title = $title;
        $this->description = $description;
		
        $title = new PageTextContainer(PageTextContainer::H3, $this->title);
		$title->setProperty('class', 'entry stretch flexible');
        
        $time = new PageContainer('div', 'class', 'entry group fill', 'style', 'font-size:0.8em;');
		$ds = new Date($this->start_date);
        $time->addChild($from = new PageTextContainer(PageTextContainer::P, "Von: ".$ds->formatLocalized(PageAppointmentList::OUTPUT_FORMAT)));
		$from->setProperty('class', 'entry');
		$de = new Date($this->end_date);
        $time->addChild($to = new PageTextContainer(PageTextContainer::P, "Bis: ".$de->formatLocalized(PageAppointmentList::OUTPUT_FORMAT)));
		$to->setProperty('class', 'entry');
        
        $description = new PageTextContainer(PageTextContainer::P, $this->description);
		$description->setProperty('class', 'entry fill');
        
		$url = URL::createStatic();
		$url->setDynamicQueryParameter('action', 'deleteAppointment');
		$url->setDynamicQueryParameter('appointment', $this->id);
		$url->setDynamicQueryParameter('referrer', URL::createCurrent());
		
		$form = new PageContainer('form', 'action', $url, 'method', 'post', 'class', 'entry');
		$form->addChild($deleteBtn = new PageButton('Löschen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE)));
		$deleteBtn->setProperty('class', 'fill');
		
        $this->addChild($top = new PageContainer('div', 'class', 'entry group'));
			$top->addChild($title);
			if ($editable) {$top->addChild($form); }
		$this->addChild($bottom = new PageContainer('div', 'class', 'entry group'));
			$bottom->addChild($time);
			$bottom->addChild($description);
    }
}
