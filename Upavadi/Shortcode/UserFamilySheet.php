<?php

class Upavadi_Shortcode_UserFamilySheet extends Upavadi_Shortcode_AbstractShortcode
{

    const SHORTCODE = 'upavadi_pages_userfamilysheet';

    //do shortcode Add Family form
    public function show()
    {
        $personId = filter_input(INPUT_GET, 'personId', FILTER_SANITIZE_SPECIAL_CHARS);
        $currentuserLogin = wp_get_current_user();
        $UserLogin = $currentuserLogin->user_login;
        $changeSets = $this->getSubmissions($UserLogin);
        $context = array();
        $context['personId'] = $personId;
        $context['changeSets'] = $changeSets;
        $context['tree'] = $tree;
        return $this->templates->render('user-familysheet.html', $context);
    }

    public function getSubmissions($UserLogin)
    {
        global $wpdb;
        $submissions = $wpdb->get_results("SELECT * FROM wp_tng_people where tnguser = '$UserLogin' AND headpersonid = personid ORDER BY datemodified DESC");
        $changeSets = array();

        foreach ($submissions as $submission) {
            $changeSets[] = new Upavadi_Update_ChangeSet($this->content->getRepo(), $wpdb, $submission);
        }
        return $changeSets;
    }

}
