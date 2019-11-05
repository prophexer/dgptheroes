<?php

namespace App\Controllers\Hockey;

use CodeIgniter\RESTful\ResourcePresenter;

class Home extends ResourcePresenter
{
    protected $modelName = 'App\Models\Hockey\Players.php';
    
    public function index()
    {
        //Retrieve all player data from Players Model
        $players = new \App\Models\hockey\Players();
        $records = $players->findAll();
        
        
        $table = new \CodeIgniter\View\Table();
    
        $headings = $players->fields;
        
        //Add an extra row to header for column show
        $headings[count($headings)+1] = "Show";
            
        $table->setHeading($headings);

        foreach ($records as $val) {

            //create a new array without the 'show' heading
            $headingsminusshow = array_slice($headings, 0, count($headings)-1);
            //Loop through headers and assign their value to an array for each table row
            foreach ($headingsminusshow as $key => $header) {
                $newRow[$key] = $val->$header;
            }

            //Link to show each player
            $showLink = anchor("hockey/home/show/$val->id","Show");
            $newRow[count($headings)+1] = $showLink;
            
            $table->addRow($newRow);
        }
        
        return $table->generate();
    }
    
    public function show($id = NULL)
    {
        //Retrieve one player data from Players Model with $id
        $players = new \App\Models\hockey\Players();
        $record = $players->find($id);
        
        // get a template parser
        $parser = \Config\Services::parser();
        
        // tell it about the substitions
        return $parser->setData($record)
            // and have it render the template with those
            ->render('hockey/onePlayer');
    }
    
    public function edit($id = NULL)
    {
        //Retrieve the player to edit
        $players = new \App\Models\hockey\Players();
        $record = $players->find($id);
        
        //set up codeigniter forms
        helper('form');
        
        $headings = $players->fields;
        
        $hockeyForm = form_open('#');
        
        //Getting the headings minus id and image
        $headingsToShow = array_slice($headings, 1, count($headings)-2);
        
        //Loop through the fields that can be editted using an input form
        foreach ($headingsToShow as $key => $header) {
            $hockeyForm .= form_label($header. ": ");
            $hockeyForm .= form_input('$header',$record[$header]);
            $hockeyForm .= "</br>";
        }
        
        //Get all the player records to get all the image names
        $records = $players->findAll();
        foreach ($records as $key => $val) {
            $images[$key] = $val->image;
        }
        
        $hockeyForm .= form_dropdown($headings[count($headings)-1],$images,$id);
        
        $hockeyForm .= "</br>";
        $hockeyForm .= form_button($id,"Submit");
        
        $hockeyForm .= form_close();
        
        return $hockeyForm;
    }
}