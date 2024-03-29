<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
    class Home extends CI_Controller{
        public function __construct(){
            parent::__construct();
            //$this->load->helper('url');// its in config now
            $this->load->model('Moviesmodel');
        }
        /*
        | -------------------------------------------------------------------------
        | Controller for Home page where it displays database records
        | -------------------------------------------------------------------------
        */
        public function displayRecords(){
            $records['data'] = $this->Moviesmodel->getRecords();
            $this->load->view('home',$records);
        }
        /*
        | -------------------------------------------------------------------------
        | Controller for viewing the page where the user can add a new movie to the list
        | -------------------------------------------------------------------------
        */
        public function createMovie(){
            $this->load->view('addMovie');  
        }
        /*
        | -------------------------------------------------------------------------
        | Controller for viewing the monthly report of the movies watched by the user
        | -------------------------------------------------------------------------
        */
        public function viewReport(){
            $records['data'] = $this->Moviesmodel->getReport();
            $this->load->view('report',$records);  
        }
        /*
        | -------------------------------------------------------------------------
        | Controller for saving the data input by the user into the database
        | -------------------------------------------------------------------------
        */
        public function save(){
            $this->form_validation->set_rules('TITLE', 'Movie Title', 'required');
            $this->form_validation->set_rules('GENRE', 'Genre', 'required');
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if ($this->form_validation->run() == FALSE){
                //echo validation_errors(); -> error displays in new page
                $this->load->view('addMovie');  
            }else{
                $data = $this->input->post();
                $title = $data['TITLE'];
                $genre = $data['GENRE'];
                $status = 'Unwatched';
                
                $record = array(
                    'TITLE' => $title,
                    'GENRE' => $genre,
                    'STATUS' => $status,
                );

                if($this->Moviesmodel->setRecords($record)){
                    $this->session->set_flashdata('response','New movie added successfully.');
                }else{
                    $this->session->set_flashdata('response','Something went wrong! :(');
                }
                return redirect('home');
            }
        }
        /*
        | -------------------------------------------------------------------------
        | Controller for updating the status from unwatched to -> watched
        | -------------------------------------------------------------------------
        */

        public function updateStatus(){
            $id = $this->input->get('id');
            if($this->Moviesmodel->updateToWatched($id)){
                $this->session->set_flashdata('response1','Cool! You watched that movie, finally :P');
            }else{
                $this->session->set_flashdata('response1','Something went wrong! :(');
            }
            return redirect('home');
        }
        /*
        | -------------------------------------------------------------------------
        | Controller for deleting a movie user prefers to remove
        | -------------------------------------------------------------------------
        */

        public function deleteMovie(){
            $id = $this->input->get('id');
            if($this->Moviesmodel->deleteMovie($id)){
                $this->session->set_flashdata('response2','That movie is off your list now!');
            }else{
                $this->session->set_flashdata('response2','Something went wrong! :(');
            }
            return redirect('home');
        }

        
         
    }
?>