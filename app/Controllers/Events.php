<?php

namespace App\Controllers;

use App\Models\EventsModel;
use CodeIgniter\RESTful\ResourceController;

use App\Models\UserModel;


class Events extends ResourceController
{
	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */

	 // Fonction qui permet de récuperer les informations lié au evenement. 
	public function index()
	{
		$model = new EventsModel();
      
        $data = $model->findAll();
      
	
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => "Events Found",
            "data" => [$data],
        ];
		
        return $this->respond($response);
	}

	

	
	public function create($id = null)
	{
		// Fonction qui permet de crée un evenement
		$model = new EventsModel();

		$user = new UserModel();
		$data = $user->find($id);
		
		$data = $this->request->getRawInput(); 
        // $data = [
        //     'name' => $this->request->getVar('name'),
        //     'created_at' => $this->request->getVar('created_at'),
        //     'end_at' => $this->request->getVar('end_at')
		// 	// 'id_user' => $data
        // ];

        if($model->insert($data))
		{

        $response = [
            'status' => 200,
            'error' => null,
            'messages' => "Event Saved",
			'data' => $data
        ];
	}
	else {

		$response = [
			'status' => 500,
			"error" => true,
			'messages' => 'Failed to create events',
			'data' => []
		];
	}
        return $this->respondCreated($response);
	
	}

	
	public function update($id = null)
	{
		// Fonction qui permet de réaliser un UPDATE des informations lié a l'evenement
		$model = new EventsModel();
        $data = $model->find($id);
        // $data = $request->getRawInput();
        // $method = $request->getMethod();
     
        // $data = [
        //     'id' => $id,
        //     'name' => $this->request->getVar('name'),
        //     'email' => $this->request->getVar('email'),
        //     'phone_no' => $this->request->getVar('phone_no')
           
        // ];

        $data = $this->request->getRawInput(); 

        if($data)
        {

        $model->update($id, $data);

        $response = [
            'status' => 200,
            'error' => null,
            'messages' => "Data Updated",
            'data' => $data
        ];
        return $this->respond($response);
        }
        else{
            return 'hello';
        }
	}

	public function delete($id = null)
	{
		// Fonction qui supprime l'evenement au sein de la base de donnée.
		$model = new EventsModel();

        $data = $model->find($id);

        if ($data) {

            $model->delete($id);

            $response = [
                'status' => 200,
                'error' => null,
                'messages' => "Data Deleted",
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id ' . $id);
        }
	}
}
