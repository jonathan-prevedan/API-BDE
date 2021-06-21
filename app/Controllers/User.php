<?php


namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use \Firebase\JWT\JWT;

class User extends ResourceController
{
    public function index()
	{
		$model = new UserModel();
      
        $data = $model->findAll();
      
        $response = [
            'status' => 200,
            'error' => null,
            'messages' => "Members Found",
            "data" => $data,
        ];
        return $this->respond($response);
	}



    public function register()
    {
        $rules = [
            "name" => "required",
            "email" => "required|valid_email|is_unique[users.email]|min_length[6]",
            "phone_no" => "required",
            "password" => "required",
        ];

        $messages = [
            "name" => [
                "required" => "Name is required"
            ],
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format"
            ],
            "phone_no" => [
                "required" => "Phone Number is required"
            ],
            "password" => [
                "required" => "password is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {

            $userModel = new UserModel();
            // $email_split = $data["email"];
            $data = [
                "name" => $this->request->getVar("name"),
                "email" => $this->request->getVar("email"),
                "phone_no" => $this->request->getVar("phone_no"),
                "password" => password_hash($this->request->getVar("password"), PASSWORD_DEFAULT),
            ];
            

            if ($userModel->insert($data)) {

                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Successfully, user has been registered',
                    'data' => []
                ];
            } else {

                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to create user',
                    'data' => []
                ];
            }
        }

        return $this->respondCreated($response);
    }



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
		$model = new UserModel();
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



    private function getKey()
    {
        return "my_application_secret";
    }

    public function login()
    {
        $rules = [
            "email" => "required|valid_email|min_length[6]",
            "password" => "required",
        ];

        $messages = [
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format"
            ],
            "password" => [
                "required" => "password is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];

            return $this->respondCreated($response);
            
        } else {
            $userModel = new UserModel();

            $userdata = $userModel->where("email", $this->request->getVar("email"))->first();

            if (!empty($userdata)) {

                if (password_verify($this->request->getVar("password"), $userdata['password'])) {

                    $key = $this->getKey();

                    $iat = time(); // current timestamp value
                    $nbf = $iat + 10;
                    $exp = $iat + 86400;

                    $payload = array(
                        "iss" => "The_claim",
                        "aud" => "The_Aud",
                        "iat" => $iat, // issued at
                        "nbf" => $nbf, //not before in seconds
                        "exp" => $exp, // expire time in seconds
                        "data" => $userdata,
                    );

                    $token = JWT::encode($payload, $key);

                    $response = [
                        'status' => 200,
                        'error' => false,
                        'messages' => 'User logged In successfully',
                        'data' => [
                            'token' => $token
                        ]
                    ];
                    return $this->respondCreated($response);
                } else {

                    $response = [
                        'status' => 500,
                        'error' => true,
                        'messages' => 'Incorrect details',
                        'data' => []
                    ];
                    return $this->respondCreated($response);
                }
            } else {
                $response = [
                    'status' => 500,
                    'error' => true,
                    'messages' => 'User not found',
                    'data' => []
                ];
                return $this->respondCreated($response);
            }
        }
    }

    public function details()
    {
        $key = $this->getKey();
        $authHeader = $this->request->getHeader("Authorization");
        $authHeader = $authHeader->getValue();
        $token = $authHeader;

        try {
            $decoded = JWT::decode($token, $key, array("HS256"));

            if ($decoded) {

                $response = [
                    'status' => 200,
                    'error' => false,
                    'messages' => 'User details',
                    'data' => [
                        'profile' => $decoded
                    ]
                ];
                return $this->respondCreated($response);
            }
        } catch (Exception $ex) {
          
            $response = [
                'status' => 401,
                'error' => true,
                'messages' => 'Access denied',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
    }

    public function delete($id = null)
	{
		$model = new UserModel();

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