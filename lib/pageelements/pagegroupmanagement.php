<?php

class PageGroupManagement extends PageElement {
	
	/** @var DatabaseConnection */
	private $db;
	
	function __construct(DatabaseConnection $db) {
		parent::__construct('div');
		$this->setProperties('id', 'group-management');
		$this->db = $db;
	}
	
	public function toXML() {
		$element = parent::toXML();
		$element->addChild($headline = new XMLElement('h1'));
		$headline->addChild(new XMLText('Gruppen verwalten'));
		$element->addChild($this->createGroupList());
		return $element;
	}
	
	private function createGroupList() {
		$list = new XMLElement('div', 'class', 'group-list');
		$userId = Session::getUserID();
		
		$result = $this->db->query(
			"SELECT g.name as name, g.id as id 
				FROM group_user_relations AS r
				JOIN groups AS g ON g.id = r.group_id
				WHERE r.user_id = '%s'
				ORDER BY g.name;"
			, $userId);
		if ($result) {
			$count = DatabaseConnection::countRows($result);
			for ($index = 0; $index < $count; $index++) {
				$row = DatabaseConnection::fetchRow($result);
				$list->addChild($this->createGroupElement($row, $index, $userId, $row['id']));
			}
		}
		return $list;
	}
	
	private function createGroupElement($row, $index, $userId, $groupId) {
		$groupItem = new XMLElement('div', 'class', 'groupitem');
		$groupItem->addChild($name = new XMLElement('div', 'class', 'groupitem-name'));
		$name->addChild(new XMLText($row['name']));
		
		if (self::isGroupOwner($this->db, $userId, $groupId)) {
			$submitUrl = URL::urlFromRelativePath("index.php", URL::urlFromBase());
			$submitUrl->setQueryParameter('action', 'delete-group');
			$submitUrl->setQueryParameter('id', $groupId);
			$submitUrl->setQueryParameter('referrer', URL::urlFromCurrent());
			$groupItem->addChild($delete = new XMLElement('form', 'class', 'groupitem-delete', 'action', $submitUrl, 'method', 'post'));
			$delete->addChild($deleteButton = new XMLElement('button', 'type', 'submit'));
			$deleteButton->addChild(new XMLText('Löschen'));
		}
		
		return $groupItem;
	}
	
	public static function isGroupOwner(DatabaseConnection $db, $userId, $groupId) {
		$result = $db->query(
			"SELECT id FROM groups WHERE user_id = '%s' AND id='%s';"
			, $userId, $groupId);
		return $result && DatabaseConnection::countRows($result) == 1;
	}

	public static function deleteGroup(DatabaseConnection $db, $userId, $groupId) {
		if (!self::isGroupOwner($db, $userId, $groupId)) {
			return FALSE;
		}
		$r = $db->query("DELETE FROM groups WHERE user_id = '%s' AND id = '%s';", $userId, $groupId);
		return $r;
	}
	
	public static function insertGroup(DatabaseConnection $db, $userId, $groupName) {
		$r1 = $db->query("INSERT INTO groups (user_id, name) VALUES ('%s', '%s');", $userId, $groupName);
		$groupId = $db->query("SELECT LAST_INSERT_ID();");
		$r2 = $db->query("INSERT INTO group_user_relations (user_id, group_id) VALUES ('%s', '%s');", $userId, $groupId); // TODO
		return $r1 && $r2;
	}
}
