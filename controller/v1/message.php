<?php

class V1_Message_Controller extends Base_Controller {
    protected function CanAnonymous($action = null) {
        return true;
    }

    public function NewAction () {
        $data = $this->Request->Raw("object");
        try {
            App::$vk->messages()->send(App::$apikey, [
                "user_id" => $data["from_id"],
                "peer_id" => $data["peer_id"],
                "message" => "test",
                "random_id" => mt_rand(),
                "keyboard" => [
                    "one_time" => true,
                    "buttons" => [
                        [
                            [
                                "action" => [
                                    "type" => "text",
                                    "label" => "Ура Ура",
                                ],
                                "color" => "primary",
                            ]
                        ],
                    ],
                ]
            ]);
        } catch (Exception $ex) {
            $txt = var_export($ex, true);
            App::$Logger->Write($txt);
        }
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