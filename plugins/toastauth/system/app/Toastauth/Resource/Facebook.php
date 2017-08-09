<?php

class Toastauth_Resource_Facebook extends Toastauth_Resource_Resource
{
    public function getId()
    {
        //https://graph.facebook.com/me?fields=id
        $id = json_decode($this->_getData('id', $this->endpoint));
        if (isset($id->error)) {
            return null;
        }

        return $id->id;
    }
}