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
		$element->addChild($this->createGroupList());
		$element->addChild($this->createInsertDialog());
		return $element;
	}
	
	private function createGroupList() {
		$list = new XMLElement('div', 'class', 'group-list');
		$list->addChild($header = new XMLElement('h2'));
		$header->addChild(new XMLText('Gruppen bearbeiten'));
		$list->addChild($content = new XMLElement('div', 'class', 'group-list-container'));
		
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
				$content->addChild($this->createGroupElement($row, $index, $userId, $row['id']));
			}
		}
		return $list;
	}
	
	private function createGroupElement($row, $index, $userId, $groupId) {
		$isGroupOwner = self::isGroupOwner($this->db, $userId, $groupId);
		$groupItem = new XMLElement('div', 'class', 'group-list-item');
		$groupItem->addChild($name = new XMLElement('div', 'class', 'group-list-item-name'));
		$name->addChild(new XMLText($row['name']));
				
		if ($isGroupOwner) {
			
			$editUrl = URL::createStatic();
			$editUrl->setDynamicQueryParameter('action', 'edit-group');
			$editUrl->setDynamicQueryParameter('id', $groupId);
			
			$edit = new XMLElement('form', 'class', 'groupitem-edit', 'action', $editUrl, 'method', 'post');
			$editButton = new PageButton('Bearbeiten', PageButton::STYLE_EDIT, PageFontIcon::create('edit', PageFontIcon::NORMAL, TRUE));
			
			$deleteUrl = URL::createStatic();
			$deleteUrl->setDynamicQueryParameter('action', 'delete-group');
			$deleteUrl->setDynamicQueryParameter('id', $groupId);
			$deleteUrl->setDynamicQueryParameter('referrer', URL::urlFromCurrent());

			$delete = new XMLElement('form', 'class', 'groupitem-delete', 'action', $deleteUrl, 'method', 'post');
			$deleteButton = new PageButton('Löschen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE));

			$buttonGroup = new XMLElement('div', 'class', 'button-group');
			
			$groupItem->addChild($buttonGroup);
				$buttonGroup->addChild($edit);
					$edit->addChild($editButton->toXML());
				$buttonGroup->addChild($delete);
					$delete->addChild($deleteButton->toXML());
			
		}
		
		return $groupItem;
	}
	
	private function createInsertDialog() {
		$submitUrl = URL::createStatic();
		$submitUrl->setDynamicQueryParameter('action', 'insert-group');
		$submitUrl->setDynamicQueryParameter('referrer', URL::urlFromCurrent());
		
		$dialog = new XMLElement('div', 'class', 'group-insert');
		$header = new PageTextContainer('h2', 'Gruppe erstellen');
		
		$form = new XMLElement('form', 'class', 'group-insert-form', 'action', $submitUrl, 'method', 'post');
		$groupNameContainer = new XMLElement('div', 'class', 'group-insert-name-container');
		$groupButtonContainer = new XMLElement('div', 'class', 'group-insert-button-container');
		
		$groupName = new XMLElement('input', 'class', 'group-insert-name', 'type', 'text', 'name', 'group-name');
		$submitButton = new PageButton('Erstellen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square', PageFontIcon::NORMAL, TRUE));
		
		$dialog->addChild($header->toXML());
		$dialog->addChild($form);
			$form->addChild($groupNameContainer);
				$groupNameContainer->addChild($groupName);
			$form->addChild($groupButtonContainer);
				$groupButtonContainer->addChild($submitButton->toXML());
			
		return $dialog;
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
	
	public static function insertGroup(DatabaseConnection $db) {
		$userId = Session::getUserID();
		$groupName = filter_input(INPUT_POST, 'group-name');
		if ($groupName !== NULL) {
			$r1 = $db->query("INSERT INTO groups (user_id, name) VALUES ('%s', '%s');", $userId, $groupName);
			$groupResult = $db->query("SELECT LAST_INSERT_ID() AS id;");
			if ($groupResult) {
				$row = DatabaseConnection::fetchRow($groupResult);
				$groupId = $row['id'];
			} else {
				return FALSE;
			}
			$r2 = $db->query("INSERT INTO group_user_relations (user_id, group_id) VALUES ('%s', '%s');", $userId, $groupId); // TODO
			return $r1 && $r2;
		}
		return FALSE;
	}
	
}