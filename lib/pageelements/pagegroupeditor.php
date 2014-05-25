<?php

class PageGroupEditor extends PageElement {
	
	/** @var DatabaseConnection */
	private $db;
	
	private $groupId;
	private $userId;
	
	function __construct(DatabaseConnection $db, $groupId, $userId) {
		parent::__construct('div');
		$this->setProperty('id', 'group-edit');
		$this->db = $db;
		$this->groupId = $groupId;
		$this->userId = $userId;
	}
	
	public function toXML() {
		$element = parent::toXML();
		
		$element->addChild($this->createMemberList()->toXML());
		
		return $element;
	}
	
	private function createMemberList() {
		$groupNameResult = $this->db->query("SELECT name FROM groups WHERE id = '%s';", $this->groupId);
		$groupNameRow = DatabaseConnection::fetchRow($groupNameResult);
		$groupName = $groupNameRow['name'];
		
		$members = $this->db->query(
			"SELECT r.id, u.username
			 FROM group_user_relations AS r
			 JOIN users AS u ON u.id = r.user_id
			 WHERE r.group_id = '%s' AND r.user_id != '%s'
			 ORDER BY u.username;"
			, $this->groupId, $this->userId);
		
		$container = new PageContainer('div', 'class', 'member-list');
		$header = new PageTextContainer(PageTextContainer::H2, 'Gruppenmitglieder von "'.$groupName.'"');
		$content = new PageContainer('div', 'class', 'member-list-container');
		if ($members) {
			$index = 1;
			while (($row = $this->db->fetchRow($members)) != FALSE) {
				$content->addChild($this->createMemberElement($row, $index));
				$index++;
			}
		}
		
		$container->addChild($header);
		$container->addChild($content);
		
		return $container;
	}
	
	private function createMemberElement($row, $index) {
		$item = new PageContainer('div', 'class', 'member-item');
		$name = new PageTextContainer('div', $row['username']);
		$name->setProperty('class', 'member-name');
		$form = new PageContainer('form', 'class', 'member-delete');
		$remove = new PageButton('Entfernen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o'));
		
		$item->addChild($name);
		$item->addChild($form);
			$form->addChild($remove);
		
		return $item;
	}

}
