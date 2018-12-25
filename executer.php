<?php

class Executer {

    /**
     * @var Responce $responce
     */
    private $responce;

    /**
     * @var Request $request
     */
    private $request;

    public function __construct($responce, $request)
    {
        $this->responce = $responce;
        $this->request = $request;
    }

    public function ExecPath($version = null, $controller = null, $action = null) {
        if ($version === null) $version = $this->request->RequestedVersion();
        if ($controller === null) $controller = $this->request->RequestedController();
        if ($action === null) $action = $this->request->RequestedAction();

        $class = $this->GetControllerClass($version, $controller);
        if ($class === false) $this->responce->WriteError(Errors::ControllerNotExists);

        $class->SetAction($action);
        $class->Exec();
    }



    public function CheckVersion($version = null) {
        if ($version == null) $version = $this->request->RequestedVersion();
        return file_exists(App::BaseAddress . App::Controllers . $version);
    }

    /**
     * @return Base_Controller
     */
    public function GetControllerClass($version, $controller) {
        $path = $this->GetControllerPath($version, $controller);
        if ($path == "") $this->responce->WriteError(Errors::WrongController);
        
        if (!file_exists($path)) $this->responce->WriteError(Errors::WrongControllerFile);
        $this->LoadBaseController();

        require_once $path;
        
        $version = ucfirst(strtolower($version));
        $controller = ucfirst(strtolower($controller));

        $class_name = $version . "_" . $controller . "_Controller";

        if (!class_exists($class_name)) $this->responce->WriteError(Errors::WrongControllerClass);
        
        /**
         * @var Base_Controller $object
         */
        $object = new $class_name();
        $object->SetController($controller);
        $object->SetResponce($this->responce);
        $object->SetRequest($this->request);
        $object->SetExecuter($this);
        $object->SetVersion($version);
        $object->constructClass();

        return $object;
    }

    private function LoadBaseController($version = null) {
        if (class_exists("Base_Controller")) return;
        require_once App::BaseAddress . App::Controllers . "base.php";
    }

    private function GetControllerPath($version, $controller){
        if ($controller == "" || $version == "") return "";

        $controller = strtolower($controller);
        $version = strtolower($version);
        return App::BaseAddress . App::Controllers . $version . "/" . $controller . ".php";
    }


    /**
     * @return Base_Model
     */
    public function GetModelClass($version, $controller, $model = null) {
        if ($model == null) $model = $controller;
        $path = $this->GetModelPath($version, $controller, $model);
        if ($path == "") $this->responce->WriteError(Errors::WrongModel);
        
        if (!file_exists($path)) $this->responce->WriteError(Errors::WrongModelFile);
        $this->LoadBaseModel();

        require_once $path;
        
        $version = ucfirst(strtolower($version));
        $controller = ucfirst(strtolower($controller));
        $model = ucfirst(strtolower($model));

        $class_name = $version . "_" . $controller . $model . "_Model";

        if (!class_exists($class_name)) $this->responce->WriteError(Errors::WrongModelClass);
        
        /** @var Base_Model $object */
        $object = new $class_name();
        $object->SetExecuter($this);
        $object->SetRequest($this->request);
        $object->SetResponce($this->responce);
        $object->constructClass();

        return $object;
    }


    private function LoadBaseModel($version = null) {
        if (class_exists("Base_Model")) return;
        require_once App::BaseAddress . App::Model . "base.php";
    }

    private function GetModelPath($version, $controller, $model = null){
        if ($model === null) $model = $controller;
        if ($controller == "" || $version == "") return "";

        $controller = strtolower($controller);
        $version = strtolower($version);
        $model = strtolower($model);

        return App::BaseAddress . App::Model . $version . "/" . $controller . "/" . $model . ".php";
    }

}