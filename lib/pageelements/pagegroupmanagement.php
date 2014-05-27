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
        $element->addChild($this->createGroupList()->toXML());
        $element->addChild($this->createInsertDialog()->toXML());
        return $element;
    }

    private function createGroupList() {
        $list = new PageContainer('div', 'class', 'group-list');
        $list->addChild($header = new PageTextContainer(PageTextContainer::H2, 'Gruppen bearbeiten'));
        $list->addChild($content = new PageContainer('div', 'class', 'group-list-container'));

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
        $groupItem = new PageContainer('div', 'class', 'group-list-item group');
        $groupItem->addChild($name = new PageTextContainer('div', $row['name']));
		$name->setProperty('class', 'group-list-item-name entry stretch flexible');

        if ($isGroupOwner) {

            $editUrl = URL::createStatic();
            $editUrl->setDynamicQueryParameter('action', 'edit-group');
            $editUrl->setDynamicQueryParameter('id', $groupId);

            $edit = new PageContainer('form', 'class', 'groupitem-edit entry', 'action', $editUrl, 'method', 'post');
            $editButton = new PageButton('Bearbeiten', PageButton::STYLE_EDIT, PageFontIcon::create('edit', PageFontIcon::NORMAL, TRUE));
			$editButton->setProperty('class', 'fill');

            $deleteUrl = URL::createStatic();
            $deleteUrl->setDynamicQueryParameter('action', 'delete-group');
            $deleteUrl->setDynamicQueryParameter('id', $groupId);
            $deleteUrl->setDynamicQueryParameter('referrer', URL::createCurrent());

            $delete = new PageContainer('form', 'class', 'groupitem-delete entry', 'action', $deleteUrl, 'method', 'post');
            $deleteButton = new PageButton('Löschen', PageButton::STYLE_DELETE, PageFontIcon::create('trash-o', PageFontIcon::NORMAL, TRUE));
			$deleteButton->setProperty('class', 'fill');

            $buttonGroup = new PageContainer('div', 'class', 'button-group entry group');

            $groupItem->addChild($buttonGroup);
				$buttonGroup->addChild($edit);
					$edit->addChild($editButton);
				$buttonGroup->addChild($delete);
					$delete->addChild($deleteButton);
        }
        return $groupItem;
    }

    private function createInsertDialog() {
        $submitUrl = URL::createStatic();
        $submitUrl->setDynamicQueryParameter('action', 'insert-group');
        $submitUrl->setDynamicQueryParameter('referrer', URL::createCurrent());

        $dialog = new PageContainer('div', 'class', 'group-insert');
        $header = new PageTextContainer('h2', 'Gruppe erstellen');

        $form = new PageContainer('form', 'class', 'group-insert-form group', 'action', $submitUrl, 'method', 'post');
        $groupNameContainer = new PageContainer('div', 'class', 'group-insert-name-container entry stretch flexible');
        $groupButtonContainer = new PageContainer('div', 'class', 'group-insert-button-container entry');

        $groupName = new PageElement('input', 'class', 'group-insert-name fill', 'type', 'text', 'name', 'group-name');
        $submitButton = new PageButton('Erstellen', PageButton::STYLE_SUBMIT, PageFontIcon::create('plus-square', PageFontIcon::NORMAL, TRUE));
		$submitButton->setProperty('class', 'fill');

        $dialog->addChild($header);
        $dialog->addChild($form);
			$form->addChild($groupNameContainer);
				$groupNameContainer->addChild($groupName);
			$form->addChild($groupButtonContainer);
				$groupButtonContainer->addChild($submitButton);

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
