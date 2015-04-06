<?php

class Upavadi_Update_FamilyAdd extends Upavadi_Update_FamilyUpdate
{

    public function process($data)
    {
        $data['person']['personID'] = $data['personID'];
        $spouse = $data['spouse'];
        $spouse['familyID'] = 'NewSpouseFamily';
        $data['family'] = array(
            '1' => array('spouse' => array($spouse))
        );
        $children = $data['child'];
        $data['child'] = array(array());
        foreach ($children as $index => $child) {
            $child['personID'] = 'NewChild' . ($index + 1);
            $child['famc'] = 'NewSpouseFamily';
            $child['familyID'] = 'NewSpouseFamily';
            $child['order'] = ($index + 1);
            $data['family'][1]['child'][$index] = $child;
        }

        return parent::process($data);
    }
}
