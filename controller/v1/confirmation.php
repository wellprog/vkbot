<?php

class V1_Confirmation_Controller extends Base_Controller {

    protected function CanAnonymous($action = null) {
        return true;
    }

    public function IndexAction() {
        $this->Responce->WriteRaw("e730430b");
    }
}