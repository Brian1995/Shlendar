<?php

class PageGroupEditor extends PageElement {
	
	/** @var DatabaseConnection */
	private $db;
	
	private $groupId;
	private $userId;
	
	private $groupName;
	private $groupOwner;
	
	function __construct(DatabaseConnection $db, $groupId, $userId) {
		parent::__construct('div');
		$this->setProperty('id', 'group-edit');
		$this->db = $db;
		$this->groupId = $groupId;
		$this->userId = $userId;
		
		$groupNameResult = $this->db->query("SELECT name, user_id AS owner FROM groups WHERE id = '%s';", $this->groupId);
		$groupNameRow = DatabaseConnection::fetchRow($groupNameResult);
		$this->groupName = $groupNameRow['name'];
		$this->groupOwner = $groupNameRow['owner'];
		
		if ($this->groupOwner != $this->userId) {
			$url = URL::createClean();
			$url->redirect();
		}
	}
	
	public function toXML() {
		$element = parent::toXML();
		
		$element->addChild($this->createMemberList()->toXML());
		$element->addChild($this->createMemberAdd()->toXML());
		
		return $element;
	}
	
	private function createMemberList() {		
		$members = $this->db->query(
			"SELECT r.id, u.username
			 FROM group_user_relations AS r
			 JOIN users AS u ON u.id = r.user_id
			 WHERE r.group_id = '%s' AND r.user_id != '%s'
			 ORDER BY u.username;"
			, $this->groupId, $this->userId);
		
		$container = new PageContainer('div', 'class', 'member-list');
		$header = new PageTextContainer(PageTextContainer::H2, 'Gruppenmitglieder von "'.$this->groupName.'"');
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
		$action = URL::createStatic();
		$action->setDynamicQueryParameter('action', 'remove-user-from-group');
		$action->setDynamicQueryParameter('relation_id', $row['id']);
		$form = new PageContainer('form', 'class', 'member-delete', 'action', $action);
		$remove = new PageButton('Entfernen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o'));
		
		$item->addChild($name);
		$item->addChild($form);
			$form->addChild($remove);
		
		return $item;
	}
	
	private function createMemberAdd() {
		$addableUsers = $this->db->query(
			"SELECT u.id, u.username
			 FROM users AS u
			 WHERE u.id NOT IN (
				SELECT r.user_id FROM group_user_relations AS r WHERE r.group_id = '%s'
			 )
			 ORDER BY u.username;"
			, $this->groupId);
		
		$container = new PageContainer('div', 'class', 'member-add');
		$header = new PageTextContainer(PageTextContainer::H2, 'Benutzer zur Gruppe hinzufügen');
		$form = new PageContainer('form', 'class', 'member-add');
		
		
		
		$container->addChild($header);
		$container->addChild($form);
		
		return $container;
	}

	public static function removeMember($group_user_relation_id) {
		
	}
}
