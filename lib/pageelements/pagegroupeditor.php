<?php

class PageGroupEditor extends PageElement {
	
	/** @var DatabaseConnection */
	private $db;
	
	private $groupId;
	private $userId;
	
	private $groupName;
	private $groupOwnerId;
	
	function __construct(DatabaseConnection $db, $groupId, $userId) {
		parent::__construct('div');
		$this->setProperty('id', 'group-edit');
		$this->db = $db;
		$this->groupId = $groupId;
		$this->userId = $userId;
		
		$this->groupName = self::getGroupName($db, $groupId);
		$this->groupOwnerId = self::getGroupOwnerId($db, $groupId);
		
		if ($this->groupOwnerId != $this->userId) {
			self::redirectToError("Current user (id=$userId) is not the group owner and has no right to view or modify the assignments of this group (id=$groupId)");
		}
	}
	
	public function toXML() {
		$element = parent::toXML();
		
		$element->addChild($this->createMemberList()->toXML());
		$element->addChild($this->createMemberAdd()->toXML());
		$element->addChild($this->createRename()->toXml());
		
		return $element;
	}
	
	private function createRename() {
		$container = new PageContainer('div', 'class', 'group-rename');
		$header = new PageTextContainer(PageTextContainer::H2, 'Gruppe umbenennen');
		
		$action = URL::createStatic();
		$action->setDynamicQueryParameter('action', 'rename-group');
		$action->setDynamicQueryParameter('id', $this->groupId);
		$action->setDynamicQueryParameter('referrer', URL::createCurrent());
		
		$form = new PageContainer('form', 'class', 'group-rename-form group', 'action', $action, 'method', 'post');
		$nameFieldContainer = new PageContainer('div', 'class', 'entry stretch flexible');
		$applyButtonContainer = new PageContainer('div', 'class', 'entry');
		
		$nameField = new PageElement('input', 'class', 'fill', 'type', 'text', 'name', 'name', 'value', $this->groupName );
		$applyButton = new PageButton('Anwenden', PageButton::STYLE_SUBMIT, PageFontIcon::create('check'));
		$applyButton->setProperty('class', 'fill');
		
		$container->addChild($header);
		$container->addChild($form);
			$form->addChild($nameFieldContainer);
				$nameFieldContainer->addChild($nameField);
			$form->addChild($applyButtonContainer);
				$applyButtonContainer->addChild($applyButton);
		return $container;
	}
	
	private function createMemberList() {		
		$members = $this->db->query(
			"SELECT r.id AS relation_id, u.username
			 FROM group_user_relations AS r
			 JOIN users AS u ON u.id = r.user_id
			 WHERE r.group_id = '%s' AND r.user_id != '%s'
			 ORDER BY u.username;"
			, $this->groupId, $this->userId);
		
		$container = new PageContainer('div', 'class', 'member-list');
		$header = new PageTextContainer(PageTextContainer::H2, 'Gruppenmitglieder von "'.$this->groupName.'"');
		$content = new PageContainer('div', 'class', 'member-list-container list');
		if ($members) {
			$index = 1;
			while (($row = DatabaseConnection::fetchRow($members)) != FALSE) {
				$content->addChild($this->createMemberListElement($row, $index++));
			}
		}
		
		$container->addChild($header);
		$container->addChild($content);
		
		return $container;
	}
	
	private function createMemberListElement($row, $index) {
		$item = new PageContainer('div', 'class', 'member-item group');
		$name = new PageTextContainer('div', $row['username']);
		$name->setProperty('class', 'member-name entry stretch flexible');
		
		$action = URL::createStatic();
		$action->setDynamicQueryParameter('action', 'remove-user-from-group');
		$action->setDynamicQueryParameter('relation_id', $row['relation_id']);
		$action->setDynamicQueryParameter('referrer', URL::createCurrent());
		
		$form = new PageContainer('form', 'class', 'member-delete entry', 'action', $action, 'method', 'post');
		$removeButton = new PageButton('Entfernen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o'));
		$removeButton->setProperty('class', 'entry');
		
		$item->addChild($name);
		$item->addChild($form);
			$form->addChild($removeButton);
		
		return $item;
	}
	
	private function createMemberAdd() {
		$addableUsers = $this->db->query(
			"SELECT u.id AS user_id, u.username AS username
			 FROM users AS u
			 WHERE u.id NOT IN (
				SELECT r.user_id FROM group_user_relations AS r WHERE r.group_id = '%s'
			 )
			 ORDER BY u.username;"
			, $this->groupId);
		
		$container = new PageContainer('div', 'class', 'member-add');
		$header = new PageTextContainer(PageTextContainer::H2, 'Benutzer zur Gruppe hinzufügen');
		$content = new PageContainer('div', 'class', 'member-add-container');
		
		if ($addableUsers) {
			$index = 1;
			while (($row = DatabaseConnection::fetchRow($addableUsers)) != FALSE) {
				$content->addChild($this->createMemberAddElement($row, $index++));
			}
		}
		
		$container->addChild($header);
		$container->addChild($content);
		
		return $container;
	}
	
	private function createMemberAddElement($row, $index) {
		$item = new PageContainer('div', 'class', 'member-item group');
		$name = new PageTextContainer('div', $row['username']);
		$name->setProperty('class', 'member-name entry stretch flexible');
		
		$action = URL::createStatic();
		$action->setDynamicQueryParameter('action', 'add-user-to-group');
		$action->setDynamicQueryParameter('group_id', $this->groupId);
		$action->setDynamicQueryParameter('user_id', $row['user_id']);
		$action->setDynamicQueryParameter('referrer', URL::createCurrent());
		
		$form = new PageContainer('form', 'class', 'member-add entry', 'action', $action, 'method', 'post');
		$addButton = new PageButton('Hinzufügen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square'));
		$addButton->setProperty('class', 'fill');
		
		$item->addChild($name);
		$item->addChild($form);
			$form->addChild($addButton);
		
		return $item;
	}
	
	private static function redirectToError($message = '') {
		$url = URL::createClean();
		$url->setDynamicQueryParameter('action', 'error');
		$url->setDynamicQueryParameter('message', $message);
	}
	
	public static function getGroupOwnerId(DatabaseConnection $db, $groupId) {
		$result = $db->query("SELECT user_id FROM groups WHERE id = '%s';", $groupId);
		if ($result && DatabaseConnection::countRows($result) == 1) {
			$row = DatabaseConnection::fetchRow($result);
			return $row['user_id'];
		}
		self::redirectToError("Can't determine owner. Group (id=$groupId) was not found.");
	}
	
	public static function getGroupName(DatabaseConnection $db, $groupId) {
		$result = $db->query("SELECT name FROM groups WHERE id = '%s';", $groupId);
		if ($result && DatabaseConnection::countRows($result) == 1) {
			$row = DatabaseConnection::fetchRow($result);
			return $row['name'];
		}
		self::redirectToError();
	}	

	public static function removeMember(DatabaseConnection $db, $relationId) {
		$relationResult = $db->query("SELECT group_id FROM group_user_relations WHERE id = '%s';", $relationId);
		if ($relationResult && DatabaseConnection::countRows($relationResult) == 1) {
			$relationRow = DatabaseConnection::fetchRow($relationResult);
			$relationGroupId = $relationRow['group_id'];
			$groupOwnerId = self::getGroupOwnerId($db, $relationGroupId);
			if ($groupOwnerId == Session::getUserID()) {
				$db->query("DELETE FROM group_user_relations WHERE id = '%s';", $relationId);
				return;
			}
		}
		self::redirectToError("Selecting the group id for the relation id failed.");
	}
	
	public static function addMember(DatabaseConnection $db, $groupId, $userId) {
		$ownerId = self::getGroupOwnerId($db, $groupId);
		$sessionUserId = Session::getUserID();
		if ($ownerId != $sessionUserId) {
			self::redirectToError("Can't add user (id=$userId) to group (id=$groupId) which does not belong to the logged in user (id=$sessionUserId).");
		}
		$resultUser = $db->query("SELECT id FROM users WHERE id = '%s';", $userId);
		if (!$resultUser || DatabaseConnection::countRows($resultUser) != 1) {
			self::redirectToError("Can't add non existing user (id=$userId) to group (id=$groupId).");
		}
		$resultRelation = $db->query("SELECT id FROM group_user_relations WHERE group_id = '%s' AND user_id = '%s';", $groupId, $userId);
		if (!$resultRelation || DatabaseConnection::countRows($resultRelation) != 0) {
			self::redirectToError("Can't add already existing group-user-relation.");
		}
		$result = $db->query("INSERT INTO group_user_relations (group_id, user_id) VALUES ('%s', '%s');", $groupId, $userId);
		if (!$result) {
			self::redirectToError("Failed to insert group user relation.");
		}
	}
}
