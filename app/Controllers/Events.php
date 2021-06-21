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
	public function index()
	{
		$model = new EventsModel();
      
        $data = $model->findAll();
      
	
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => "Events Found",
            "data" => $data,
        ];
		
        return $this->respond($response);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		//
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		//
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create($id = null)
	{
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

	/**
	 * Return the editable properties of a resource object
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		//
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
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

	/**
	 * Delete the designated resource object from the model
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
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
