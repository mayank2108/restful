<?php
/**
 * @class Products
 * A simple application controller extension
 */
class Products extends ApplicationController {
    /**
     * view
     * Retrieves rows from database.
     */
    public function view() {
        $res = new Response();
        $res->success = true;
        $res->message = "Loaded data";
        $res->data = Product::all();
        //print_r($res);
        //print_r($_REQUEST);
        return $res->to_json();
    }
    /**
     * create
     */
    public function create() {
        $res = new Response();
        $rec = Product::create($this->params);
        if ($rec) {
            $res->success = true;
            $res->message = "Created new Product" . $rec->id;
            $res->data = $rec->to_hash();

            //print_r($res);
        } else {
            $res->message = "Failed to create Product";
        }
        return $res->to_json();
    }

    public function update() {
        $res = new Response();
        $rec = Product::update($this->id, $this->params);
        if ($rec) {
            $res->data = $rec->to_hash();
            $res->success = true;
            $res->message = 'Updated Product ' . $this->id;
        } else {
            $res->message = "Failed to find that Product";
        }
        //print_r($res);
        return $res->to_json();
    }

	 
	 public function destroy() {
        $res = new Response();
        $rec = Product::destroy($this->id, $this->params);
        if ($rec) {
            $res->data = $rec->to_hash();
            $res->success = true;
            $res->message = 'Destroyed Product ' . $this->id;
        } else {
            $res->message = "Failed to destroy Product";
        }
         //print_r($res);
        return $res->to_json();
    }
}

