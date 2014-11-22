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
        $user = $data['User'];
        $headPersonId = $data['personId'];

        $data['father'] = $this->extractPersonData($data, 'father');
        $data['mother'] = $this->extractPersonData($data, 'mother');
        $data['parents'] = $this->extractPersonData($data, 'parents');

        $people = $this->extractPeople($data);
        $families = $this->extractFamilies($data);
        echo str_repeat('*', 50) . PHP_EOL;
        print_r($data);
    }

    public function extractPersonData($data, $personType)
    {
        $personData = array();
        foreach ($data as $key => $value) {
            if (!preg_match("/^{$personType}_?(.*)$/", $key, $m)) {
                continue;
            }
            $personData[$m[1]] = $value;
        }
        return $personData;
    }

    public function extractPeople($data)
    {
        $people = array();
        $people[] = $this->extractPerson($data);
        $people[] = $this->extractPerson($this->normaliseParent($data['father']));
        $people[] = $this->extractPerson($this->normaliseParent($data['mother']));
        $people = $this->extractSpouses($people, $data);
        $people = $this->extractChildren($people, $data);
        return $people;
    }

    public function extractPerson($data)
    {
        $personid = $data['personId'];
        $firstname = $data['firstname'];
        $lastname = $data['surname'];
        $birthdate = $data['B_day'];
        $birthdatetr = $birthdate;
        $birthplace = $data['B_Place'];
        $deathdate = $data['D_day'];
        $deathdatetr = $deathdate; // do we need this?
        $deathplace = $data['D_Place'];
        $sex = $data['personsex'];

        if (empty($data['personfamc']) && (empty($data['fathername']) || empty($data['mothername']))) {
            $famc = "NewParents";
        } else {
            $famc = $data['personfamc'];
        }
        $living = $data['personliving'];
        $personevent = $data['personevent'];
        $cause = $data['cause_of_death'];

        return array(
            'personid' => $personid,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'personevent' => $personevent,
            'birthdate' => $birthdate,
            'birthdatetr' => $birthdatetr,
            'birthplace' => $birthplace,
            'deathdate' => $deathdate,
            'deathdatetr' => $deathdatetr,
            'deathplace' => $deathplace,
            'sex' => $sex,
            'famc' => $famc,
            'living' => $living,
            'cause' => $cause,
        );
    }

    public function normaliseParent($data)
    {
        $data['firstname'] = $data['name'];
        $data['lastname'] = $data['surname'];
        $data['personfamc'] = $data['famc'];
        $data['personsex'] = $data['sex'];
        $data['personliving'] = $data['living'];
        $data['personevent'] = $data['event'];
        $data['fathername'] = null;
        $data['mothername'] = null;
        return $data;
    }

    public function extractSpouses($people, $data)
    {
        foreach ($data['family'] as $family) {
            foreach ($family as $spouse) {
                $people[] = $this->extractPerson($this->normaliseSpouse($spouse));
            }
        }

        return $people;
    }

    public function normaliseSpouse($data)
    {
        $data = $this->extractPersonData($data, 'spouse');
        foreach ($data as $key => $value) {
            if (preg_match('/^(.)[.](.)(.*)$/', $key, $m)) {
                $newKey = $m[1] . '_' . strtoupper($m[2]) . $m[3];
                $data[$newKey] = $value;
                $newKey = $m[1] . '_' . $m[2] . $m[3];
                $data[$newKey] = $value;
            }
        }
        $data['personId'] = $data['ID'];
        $data['firstname'] = $data['name'];
        $data['personsex'] = $data['sex'];
        $data['personliving'] = $data['living'];
        $data['personevent'] = $data['event'];

        return $data;
    }

    public function extractChildren($people, $data)
    {
        foreach ($data['child'] as $family) {
            foreach ($family as $child) {
                $people[] = $this->extractPerson($this->normaliseChild($child));
            }
        }

        return $people;
    }

    public function normaliseChild($data)
    {
        $data = $this->extractPersonData($data, 'child');
        
        $data['personId'] = $data['ID'];
        $data['personsex'] = $data['sex'];
        $data['personliving'] = $data['living'];
        $data['personevent'] = $data['event'];
        $data['B_day'] = $data['dateborn'];
        $data['B_Place'] = $data['placeborn'];
        $data['D_day'] = $data['datedied'];
        $data['D_Place'] = $data['placedied'];
        $data['cause_of_death'] = $data['cause'];
        
        return $data;
    }

    public function extractFamilies($data)
    {
    }

}
