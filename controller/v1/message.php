<?php

class V1_Message_Controller extends Base_Controller {
    protected function CanAnonymous($action = null) {
        return true;
    }

    public function NewAction () {
        $data = $this->Request->Raw("data");
        App::$Logger->Write($data);
        return $this->Responce->WriteRaw("OK");
    }

    public function ReplyAction() {
        return $this->Responce->WriteRaw("OK");
    }

    public function EditAction() {
        return $this->Responce->WriteRaw("OK");
    }

    public function AllowAction() {
        return $this->Responce->WriteRaw("OK");
    }

    public function DenyAction() {
        return $this->Responce->WriteRaw("OK");
    }
}