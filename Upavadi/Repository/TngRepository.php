<?php

class Upavadi_Repository_TngRepository
{

    /**
     * @var Upavadi_TngContent 
     */
    private $content;
    private $people = array();
    private $families = array();
    private $childFamilies = array();

    public function __construct(Upavadi_TngContent $content)
    {
        $this->content = $content;
    }

    public function getPerson($id)
    {
        if (!isset($this->people[$id])) {
            $this->people[$id] = $this->content->getPerson($id);
        }
        return $this->people[$id];
    }

    public function getFamily($id)
    {
        if (!isset($this->families[$id])) {
            $this->families[$id] = $this->content->getFamilyById($id);
        }
        return $this->families[$id];
    }

    public function getChildFamily($personID, $familyID)
    {
        if (!isset($this->childFamilies[$personID]) || (isset($this->childFamilies[$personID]) && !isset($this->childFamilies[$personID][$familyID]))) {
            $this->childFamilies[$personID][$familyID] = $this->content->getChildFamily($personID, $familyID);
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
        $stmnt = $db->prepare($sql);
        array_unshift($args, $types);
        var_dump(array($sql, $args));
        call_user_func_array(array($stmnt, 'bind_param'), $args);
        $stmnt->execute();
        var_dump($db->error);
    }

    public function updatePerson($id, $fields)
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
        $sql .= ' WHERE personID = ?';
        $args[] = & $id;
        $this->execute($sql, $args);
        unset($this->people[$id]);
    }

    public function updateFamily($id, $fields)
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
        $sql .= ' WHERE familyID = ?';
        $args[] = & $id;
        $this->execute($sql, $args);
        unset($this->family[$id]);
    }

    public function updateChildren($id, $fields)
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
        $sql .= ' WHERE personId = ?';
        $args[] = & $id;
        $this->execute($sql, $args);
        unset($this->childFamilies[$id]);
    }

    public function addPerson($fields)
    {
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
        return $newId;
    }

}
