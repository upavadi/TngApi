<?php

class Upavadi_Update_FamilyUpdate
{

    private $db;
    private $tables;

    public function __construct($db, $peopleTable, $familiesTable, $childrenTable)
    {
        $this->db = $db;
        $this->tables = array(
            'people' => $peopleTable,
            'families' => $familiesTable,
            'children' => $childrenTable
        );
    }

    public function process($data)
    {
        $gedcom = $data['gedcom'];
        $user = $data['User'];
        $headPersonId = $data['personID'];
        $date = date('Y-m-d H:i:s');
        $keys = array(
            'headpersonid' => $headPersonId,
            'tnguser' => $user,
            'datemodified' => $date,
            'gedcom' => $gedcom
        );

        $people = array_filter($this->extractPeople($data));
        $families = $this->extractFamilies($data);
        $children = $this->extractChildrenFamily($data);
        $personChild = $this->extractPersonChild($data);
        if ($personChild) {
            $children[] = $personChild;
        }
        $people = $this->addFields($people, $keys);
        $families = $this->addFields($families, $keys);
        $children = $this->addFields($children, $keys);

        $this->db->query('START TRANSACTION');
        try {
            $this->insertRows($this->tables['people'], $people);
            $this->insertRows($this->tables['families'], $families);
            $this->insertRows($this->tables['children'], $children);
            $this->db->query('COMMIT');
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            throw $e;
        }
        return $date;
    }

    public function extractPeople($data)
    {
        $people = array();
        $person = $this->extractPerson($data['person']);
        $parsonFam = $this->extractParentsFamily($data);
        $person['famc'] = $parsonFam['familyid'];
        $people[] = $person;
        $people[] = $this->extractPerson($data['father']);
        $people[] = $this->extractPerson($data['mother']);
        $people = $this->extractSpouses($people, $data);
        $people = $this->extractChildren($people, $data);
        return $people;
    }

    public function extractFamilyId($data)
    {
        if (empty($data['famc'])) {
            $famc = null;
        } else {
            $famc = $data['famc'];
        }

        return $famc;
    }

    public function extractPerson($data)
    {
        if (!$data) {
            return null;
        }
        if (!$data['firstname']) {
            return null;
        }
        $personid = $data['personID'];
        $firstname = $data['firstname'];
        $lastname = $data['surname'];
        $birthdate = $data['birthdate'];
        $birthplace = $data['birthplace'];
        $deathdate = $data['deathdate'];
        $deathplace = $data['deathplace'];
        $sex = $data['sex'];

        $famc = $this->extractFamilyId($data);
        $living = $data['living'];
        $personevent = $data['event'];

        if (isset($data['order']) and ( $personid == "NewChild-")) {

            $personid = "NewChild-" . $data['spouseorder'] . "." . $data['order'];
        }
        return array(
            'personid' => $personid,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'personevent' => $personevent,
            'birthdate' => $birthdate,
            'birthplace' => $birthplace,
            'deathdate' => $deathdate,
            'deathplace' => $deathplace,
            'sex' => $sex,
            'famc' => $famc,
            'living' => $living,
        );
    }

    public function extractSpouses($people, $data)
    {
        if (!isset($data['family'])) {
            return $people;
        }
        foreach ($data['family'] as $family) {
            foreach ($family['spouse'] as $spouse) {
                $people[] = $this->extractPerson($spouse);
            }
        }

        return $people;
    }

    public function extractChildren($people, $data)
    {
        if (!isset($data['family'])) {
            return $people;
        }
        foreach ($data['family'] as $family) {
            foreach ($family['child'] as $child) {
                $people[] = $this->extractPerson($child);
            }
        }

        return $people;
    }

    
    public function extractPersonChild($data)
    {
        $person = $this->extractPerson($data['person']);
        $personFam = $this->extractParentsFamily($data);
        $children = $this->extractChildren(array(), $data);
        if ($personFam['familyid'] !== 'NewParentFamily') {
            return null;
        }
        
        return array(
            'personid' => $person['personid'],
            'familyID' => $personFam['familyid'],
            'haskids' => count($children) > 0,
            'ordernum' => 1,
            'parentorder' => 1
        );
    }
    
    public function extractFamilies($data)
    {
        $families = array();
        $families[] = $this->extractParentsFamily($data);
        $families = $this->extractSpousesFamily($families, $data);
        return $families;
    }

    public function extractParentsFamily($data)
    {
        $familyID = $this->extractFamilyId($data['person']);
        
        $father = $data['father'];
        if ($father['firstname']) {
            $husband = $father['personID'];
        } else {
            $husband = null;
        }
        
        $mother = $data['mother'];
        if ($mother['firstname']) {
            $wife = $mother['personID'];
        } else {
            $wife = null;
        }
        $marrInfo = $data['parents'];
        $marrDate = $marrInfo['marrdate'];
        $marrPlace = $marrInfo['marrplace'];

        $husbOrder = $data['parents']['husborder'];
        $wifeOrder = $data['parents']['wifeorder'];
        $living = $data['parents']['living'];

        if (!$familyID && ($husband || $wife)) {
            $familyID = 'NewParentFamily';
        } 
        $family = array(
            'familyid' => $familyID,
            'husband' => $husband,
            'wife' => $wife,
            'marrdate' => $marrDate,
            'marrplace' => $marrPlace,
            'husborder' => $husbOrder,
            'wifeorder' => $wifeOrder,
            'living' => $living
        );

        return $family;
    }

    public function extractSpousesFamily($families, $data)
    {
        if (!isset($data['family'])) {
            return $families;
        }
        foreach ($data['family'] as $family) {
            foreach ($family['spouse'] as $spouse) {
                $families[] = $this->extractSpouseFamily($data, $spouse);
            }
        }

        return $families;
    }

    public function extractSpouseFamily($headPerson, $spouse)
    {
        $familyID = $spouse['familyID'];
        if ($spouse['sex'] == 'M') {
            $husbandPerson = $spouse;
            $wifePerson = $headPerson;
        } else {
            $husbandPerson = $headPerson;
            $wifePerson = $spouse;
        }

        $husband = $husbandPerson['personID'];
        $wife = $wifePerson['personID'];

        $marrDate = $spouse['marrdate'];
        $marrPlace = $spouse['marrplace'];

        $husbOrder = $spouse['husborder'];
        $wifeOrder = $spouse['wifeorder'];
        $living = $spouse['living'];

        $family = array(
            'familyid' => $familyID,
            'husband' => $husband,
            'wife' => $wife,
            'marrdate' => $marrDate,
            'marrplace' => $marrPlace,
            'husborder' => $husbOrder,
            'wifeorder' => $wifeOrder,
            'living' => $living
        );

        return $family;
    }

    public function extractChildrenFamily($data)
    {
        if (!isset($data['family'])) {
            return array();
        }
        $children = array();

        foreach ($data['family'] as $family) {
            foreach ($family['child'] as $child) {
                $children[] = $this->extractChildFamily($child);
            }
        }

        return array_filter($children);
    }

    public function extractChildFamily($data)
    {
        if (empty($data['firstname'])) {
            return null;
        }
        $personID = $data['personID'];
        $familyId = $data['familyID'];
        $hasKids = $data['haskids'];
        $orderNum = $data['order'];
        $parentOrder = $data['parentorder'];
        $spouseorder = $data['spouseorder'];

        if (isset($data['order']) and ( $personID == "NewChild-")) {
            $personID = "NewChild-" . $spouseorder . "." . $orderNum;
        }


        $child = array(
            'personid' => $personID,
            'familyID' => $familyId,
            'haskids' => $hasKids,
            'ordernum' => $orderNum,
            'parentorder' => $parentOrder
        );
        return $child;
    }

    public function addFields($records, $fields)
    {
        $newRecords = array();
        if (!is_array($records)) {
            return $newRecords;
        }
        foreach ($records as $record) {
            $newRecords[] = array_merge($record, $fields);
        }

        return $newRecords;
    }

    public function insertRows($table, $records)
    {
        foreach ($records as $record) {
            if (!$this->db->insert($table, $record)) {
                throw new RuntimeException('Could not insert record into ' . $table);
            }
        }
    }


}
