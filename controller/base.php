<?php

class Base_Controller {
    /**
     * @var string $Controller
     */
    protected $Controller;
    public function SetController($value) {
        $this->Controller = $value;
    }

    /**
     * @var string $Action
     */
    protected $Action;
    public function SetAction($value) {
        $this->Action = $value;
    }

    /**
     * @var string $Version
     */
    protected $Version;
    public function SetVersion($value) {
        $this->Version = $value;
    }


    /**
     * @var Responce $Responce
     */
    protected $Responce;
    public function SetResponce($value) {
        $this->Responce = $value;
    }

    /**
     * @var Executer $Executer
     */
    protected $Executer;
    public function SetExecuter($value) {
        $this->Executer = $value;
    }

    /**
     * @var Request $Request
     */
    protected $Request;
    public function SetRequest ($request) {
        $this->Request = $request;
    }

    public function constructClass() {
        
    }

    public function Exec($action = null) {
        if ($action === null) $action = $this->Action;

        if (!$this->Request->SessionExists() && !$this->CanAnonymous($action))
            $this->Responce->WriteError(Errors::ACCESS_DENIED); 

        if (!method_exists($this, $action)) {
            $this->Responce->WriteError(Errors::ActionNotExists);
        }

        if (!is_callable([$this, $action])) {
            $this->Render->WriteError(Errors::ActionNotAccess);
        }

        return $this->$action();
    }


    protected function CanAnonymous($action = null) {
        return false;
    }

}