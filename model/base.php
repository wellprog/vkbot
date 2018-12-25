<?php

class Base_Model {

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

    /**
     * @var Responce $Responce
     */
    protected $Responce;
    public function SetResponce ($responce) {
        $this->Responce = $responce;
    }

    public function constructClass() {
        
    }

    

}