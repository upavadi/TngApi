<?php

class Upavadi_Repository_TngRepository
{

    /**
     * @var Upavadi_TngContent 
     */
    private $content;
    private $people = array();
    private $events = array();
    private $families = array();
    private $childFamilies = array();

    public function __construct(Upavadi_TngContent $content)
    {
        $this->content = $content;
    }

    public function getPerson($id, $tree = null)
    {
        if (!isset($this->people[$id])) {
            $this->people[$id] = $this->content->getPerson($id, $tree);
        }
        return $this->people[$id];
    }

    public function getFamily($id, $tree = null)
    {
        if (!isset($this->families[$id])) {
            $this->families[$id] = $this->content->getFamilyById($id, $tree);
        }
        return $this->families[$id];
    }

    public function getChildFamily($personID, $familyID, $tree = null)
    {
        if (!isset($this->childFamilies[$personID]) || (isset($this->childFamilies[$personID]) && !isset($this->childFamilies[$personID][$familyID]))) {
            $this->childFamilies[$personID][$familyID] = $this->content->getChildFamily($personID, $familyID, $tree);
        }
        return $this->childFamilies[$personID][$familyID];
    }

    public function execute($sql, $args)
    {
        $db = $this->content->getDbLink();
        $types = '';
        foreach ($args as $value) {
            if (is_int($value) || is_bool($value)) {
                $types .= 'i';
            } else {
                $types .= 's';
            }
        }
        var_dump($sql);
        $stmnt = $db->prepare($sql);
        if (!$stmnt) {
            var_dump($db->error);
            return;
        }
        array_unshift($args, $types);
        var_dump(array($sql, $args));
        call_user_func_array(array($stmnt, 'bind_param'), $args);
        $stmnt->execute();
        var_dump($db->error);
    }

    public function updatePerson($id, $fields, $tree)
    {
        $tables = $this->content->getTngTables();
        $sql = "UPDATE {$tables['people_table']} SET ";
        $args = array();
        $sets = array();

        foreach ($fields as $name => $value) {
            $sets[] = "$name = ?";
            $args[] = & $fields[$name];
        }
        $sql .= join(', ', $sets);
        $sql .= ' WHERE personID = ? AND gedcom = ?';
        $args[] = & $id;
        $args[] = & $tree;
        $this->execute($sql, $args);
        unset($this->people[$id]);
    }

    public function updateFamily($id, $fields, $tree)
    {
        $db = $this->content->getDbLink();
        $tables = $this->content->getTngTables();
        $sql = "UPDATE {$tables['families_table']} SET ";
        $args = array();
        $sets = array();
        foreach ($fields as $name => $value) {
            $sets[] = "$name = ?";
            $args[] = & $fields[$name];
        }
        $sql .= join(', ', $sets);
        $sql .= ' WHERE familyID = ? AND gedcom = ?';
        $args[] = & $id;
        $args[] = & $tree;
        $this->execute($sql, $args);
        unset($this->family[$id]);
    }

    public function updateChildren($id, $fields, $tree)
    {
        $db = $this->content->getDbLink();
        $tables = $this->content->getTngTables();
        $sql = "UPDATE {$tables['children_table']} SET ";
        $args = array();
        $sets = array();
        foreach ($fields as $name => $value) {
            $sets[] = "$name = ?";
            $args[] = & $fields[$name];
        }
        $sql .= join(', ', $sets);
        $sql .= ' WHERE personId = ? AND gedcom = ?';
        $args[] = & $id;
        $args[] = & $tree;
        $this->execute($sql, $args);
        unset($this->childFamilies[$id]);
    }

    public function addPerson($fields)
    {
        $version = $this->content->guessVersion();
        $db = $this->content->getDbLink();
        $tables = $this->content->getTngTables();
        $db->query("LOCK TABLES {$tables['people_table']}");
        $sql =  "SELECT MAX(CAST(SUBSTRING(personID, 2) AS SIGNED)) as id FROM {$tables['people_table']} WHERE gedcom = '" . $fields['gedcom']. "'";
        $res = $db->query($sql);
        $row = $res->fetch_assoc();
        $newIdInt = intval($row['id']) + 1;
        $newId = 'I' . $newIdInt;
        $sql = "INSERT INTO {$tables['people_table']} ";
        $args = array();
        $sets = array();
        $vals = array();
        $fields['personID'] = $newId;
        $dates = array(
            'altbirthdatetr',
            "burialdatetr",
            "baptdatetr",
            "endldatetr"
        );
        if ($version > 9) {
            $fields['burialtype'] = 0;
            $fields['confdate'] = '';
            $dates[] = 'confdatetr';
            $fields['initdate'] = '';
            $dates[] = 'initdatetr';
            $fields['initplace'] = '';
        }
        foreach ($dates as $date) {
            if (!isset($fields[$date])) {
                $fields[$date] = '0000-00-00';
            }
        }
        foreach ($fields as $name => $value) {
            $sets[] = $name;
            $vals[] = '?';
            $args[] = & $fields[$name];
        }
        $sql .= '(' . join(', ', $sets) . ') VALUES';
        $sql .= '(' . join(', ', $vals) . ')';
        $this->execute($sql, $args);
        $db->query("UNLOCK TABLES");
        return $newId;
    }

    public function addFamily($fields)
    {
        $db = $this->content->getDbLink();
        $tables = $this->content->getTngTables();
        $db->query("LOCK TABLES {$tables['families_table']}");
        $sql =  "SELECT MAX(CAST(SUBSTRING(familyID, 2) AS SIGNED)) as id FROM {$tables['families_table']} WHERE gedcom = '" . $fields['gedcom']. "'";
        $res = $db->query($sql);
        $row = $res->fetch_assoc();
        $newIdInt = intval($row['id']) + 1;
        $newId = 'F' . $newIdInt;
        $sql = "INSERT INTO {$tables['families_table']} ";
        $args = array();
        $sets = array();
        $vals = array();
        $fields['familyID'] = $newId;

        $dates = array(
            'sealdatetr',
            'divdatetr'
        );
        foreach ($dates as $date) {
            if (!isset($fields[$date])) {
                $fields[$date] = '0000-00-00';
            }
        }
        
        foreach ($fields as $name => $value) {
            $sets[] = $name;
            $vals[] = '?';
            $args[] = & $fields[$name];
        }
        $sql .= '(' . join(', ', $sets) . ') VALUES';
        $sql .= '(' . join(', ', $vals) . ')';
        $this->execute($sql, $args);
        $db->query("UNLOCK TABLES");
        return $newId;
    }

    public function addChildren($fields)
    {
        $tables = $this->content->getTngTables();
        $sql = "INSERT INTO {$tables['children_table']} ";
        $args = array();
        $sets = array();
        $vals = array();

        $dates = array(
            'sealdatetr'
        );
        foreach ($dates as $date) {
            if (!isset($fields[$date])) {
                $fields[$date] = '0000-00-00';
            }
        }
        
        foreach ($fields as $name => $value) {
            $sets[] = $name;
            $vals[] = '?';
            $args[] = & $fields[$name];
        }
        $sql .= '(' . join(', ', $sets) . ') VALUES';
        $sql .= '(' . join(', ', $vals) . ')';
        $this->execute($sql, $args);
        $db = $this->content->getDbLink();
        $newId = $db->insert_id;
        return $newId;
    }

    public function getNote($personId, $id, $tree = null)
    {
        if (!isset($this->notes[$personId][$id])) {
            $notes = $this->content->getNotes($personId, $tree);
            foreach ($notes as $note) {
                $xid = $note['xnoteID'];
                $this->notes[$xid] = array(
                    'persfamID' => $note['persfamID'],
                    'eventID' => $note['eventID'],
                    'note' => $note['note'],
                    'xnoteID' => $note['xnoteID']
                );
            }
        }
        return $this->notes[$id];
    }

    public function addNote($fields)
    {
        $noteLinks = $fields;
        unset($noteLinks['note']);
        $xnotes = array(
            'note' => $fields['note'],
            'gedcom' => $fields['gedcom']
        );
        
        $xnoteId = $this->addXNote($xnotes);
        $noteLinks['xnoteID'] = $xnoteId;
        $this->addNoteLink($noteLinks);
        return $xnoteId;
    }

    public function updateNote($id, $fields, $tree)
    {
        if (!count($fields)) {
            return;
        }
        $tables = $this->content->getTngTables();
        $sql = "UPDATE {$tables['xnotes_table']} SET ";
        $args = array();
        $sets = array();

        foreach ($fields as $name => $value) {
            $sets[] = "$name = ?";
            $args[] = & $fields[$name];
        }
        $sql .= join(', ', $sets);
        $sql .= ' WHERE ID = ? AND gedcom = ?';
        $args[] = & $id;
        $args[] = & $tree;
        $this->execute($sql, $args);
        unset($this->notes[$id]);
    }

    public function addXNote($fields)
    {
        $tables = $this->content->getTngTables();
        $sql = "INSERT INTO {$tables['xnotes_table']} ";
        $args = array();
        $sets = array();
        $vals = array();

        foreach ($fields as $name => $value) {
            $sets[] = $name;
            $vals[] = '?';
            $args[] = & $fields[$name];
        }
        $sql .= '(' . join(', ', $sets) . ') VALUES';
        $sql .= '(' . join(', ', $vals) . ')';
        $this->execute($sql, $args);
        $db = $this->content->getDbLink();
        $newId = $db->insert_id;
        return $newId;
    }

    public function addNoteLink($fields)
    {
        $tables = $this->content->getTngTables();
        $sql = "INSERT INTO {$tables['notelinks_table']} ";
        $args = array();
        $sets = array();
        $vals = array();

        foreach ($fields as $name => $value) {
            $sets[] = $name;
            $vals[] = '?';
            $args[] = & $fields[$name];
        }
        $sql .= '(' . join(', ', $sets) . ') VALUES';
        $sql .= '(' . join(', ', $vals) . ')';
        $this->execute($sql, $args);
        $db = $this->content->getDbLink();
        $newId = $db->insert_id;
        return $newId;
    }

    public function getEvent($id, $tree = null)
    {
        if (!isset($this->events[$id])) {
            $this->events[$id] = $this->content->getEvent($id, $tree);
        }
        return $this->events[$id];
    }

    public function addEvent($fields)
    {
        $tables = $this->content->getTngTables();
        $sql = "INSERT INTO {$tables['events_table']} ";
        $args = array();
        $sets = array();
        $vals = array();
        
        foreach ($fields as $name => $value) {
            $sets[] = $name;
            $vals[] = '?';
            $args[] = & $fields[$name];
        }
        $sql .= '(' . join(', ', $sets) . ') VALUES';
        $sql .= '(' . join(', ', $vals) . ')';
        $this->execute($sql, $args);
        $db = $this->content->getDbLink();
        $newId = $db->insert_id;
        return $newId;
    }

    public function updateEvent($id, $fields, $tree)
    {
        if (!count($fields)) {
            return;
        }
        $tables = $this->content->getTngTables();
        $sql = "UPDATE {$tables['events_table']} SET ";
        $args = array();
        $sets = array();

        foreach ($fields as $name => $value) {
            $sets[] = "$name = ?";
            $args[] = & $fields[$name];
        }
        $sql .= join(', ', $sets);
        $sql .= ' WHERE eventID = ? AND gedcom = ?';
        $args[] =& $id;
        $args[] =& $tree;
        $this->execute($sql, $args);
        unset($this->events[$id]);
    }
}
