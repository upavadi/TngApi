<?php

class Upavadi_Update_FamilyAdd extends Upavadi_Update_FamilyUpdate
{

    public function process($data)
    {
        $data['person']['personID'] = $data['personID'];
        $spouse = $data['spouse'];
        $data['family'] = array(
            '1' => array('spouse' => array($spouse))
        );
        $children = $data['child'];
        $data['child'] = array(array());
        foreach ($children as $index => $child) {
            $child['childID'] = 'NewChild' . ($index + 1);
            $child['childfamc'] = 'NewSpouseFamily';
            $child['childfamilyID'] = 'NewSpouseFamily';
            $child['childorder'] = ($index + 1);
            $data['family'][1]['child'][$index] = $child;
        }
        return parent::process($data);
    }

    public function normaliseSpouse($data)
    {
        $data['personID'] = $data['ID'];
        $data['personsex'] = $data['sex'];
        $data['personfamc'] = $data['famc'];
        $data['personliving'] = $data['living'];
        $data['personevent'] = $data['event'];
        $data['B_day'] = $data['birthdate'];
        $data['B_Place'] = $data['birthplace'];
        $data['D_day'] = $data['deathdate'];
        $data['D_Place'] = $data['deathplace'];

        return $data;
    }

    public function extractSpouseFamily($headPerson, $spouse)
    {
        $spouse['familyID'] = 'NewSpouseFamily';
        $spouse['husborder'] = $spouse['husbandorder'];
        return parent::extractSpouseFamily($headPerson, $spouse);
    }

}
