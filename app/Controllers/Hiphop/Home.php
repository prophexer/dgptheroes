<?php
namespace App\Controllers\Hiphop;
use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        // connect to the model
        $musicians = new \App\Models\hiphop\Musicians();
        // retrieve all the records
        $records = $musicians->findAll();     
        // get a template parser
        $parser = \Config\Services::parser();       
        // tell it about the substitions
        return $parser->setData(['records' => $records])
            // and have it render the template with those
            ->render('hiphop/musicianList');
    }
    
    public function showme($id)
    {
        // connect to the model
        $musicians = new \App\Models\hiphop\Musicians();
        // retrieve all the records
        $record = $musicians->find($id);      
        // get a template parser
        $parser = \Config\Services::parser();        
        // tell it about the substitions
        return $parser->setData($record)
            // and have it render the template with those
            ->render('hiphop/musicianIntro');
    }
}