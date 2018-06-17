<?php
declare(strict_types = 1);

namespace Websupporter\StageAnonymizer\Repository;

class EmailList
{

    private $wpdb;

    public function __construct(\wpdb $wpdb)
    {

        $this->wpdb = $wpdb;
    }

    public function all() : array
    {

        $emailList = array_unique(array_merge($this->emailsInComments(), $this->emailsFromUsers()));

        return $emailList;
    }

    private function emailsInComments() : array
    {

        $sql    = 'select comment_author_email from ' . $this->wpdb->comments;
        $result = $this->wpdb->get_col(
            $sql
        );

        return $result;
    }

    private function emailsFromUsers() : array
    {

        $sql    = 'select user_email from ' . $this->wpdb->users;
        $result = $this->wpdb->get_col(
            $sql
        );

        return $result;
    }
}