<?php
/**
 * @class ApplicationController
 */
class ApplicationController {
    public $request, $id, $params;

    public function dispatch($request) {
        $this->request = $request;
        $this->id = $request->id;
        $this->params = $request->params;
        //print_r($this);

        if ($request->isRestful()) {
            return $this->dispatchRestful();
        }
        if ($request->action) {
            return $this->{$request->action}();
        }
    }

    protected function dispatchRestful() {
        //print_r($this);
        switch ($this->request->method) {
            case 'GET':
                return $this->view();
                break;
            case 'POST':
                return $this->create();
                break;
            case 'PUT':
                return $this->update();
                break;
            case 'DELETE':
                return $this->destroy();
                break;
        }
    }
}

