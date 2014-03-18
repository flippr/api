<?php

require_once("Model/Rest.inc.php");

class API extends REST
{

    public $data = "";

    private $db;

    public function __construct()
    {
        parent::__construct(); // Init parent contructor
        $this->dbConnect(); // Initiate Database connection
    }

    /*
     *  Database connection
    */
    private function dbConnect()
    {
        require_once('Config/Db.php');
        $this->db = new Db();
    }

    /*
     * Public method for access api.
     * This method dynmically call the method based on the query string
     *
     */
    public function processApi()
    {
        $status     = array(
            'request' => array(
                'method' => $_SERVER['REQUEST_METHOD'],
                'format' => 'json'
            ),
            'result'  => array(
                'error' => 'Invalid consumer key'
            )
        );
        $url        = $_SERVER['REQUEST_URI'];
        $url        = ltrim($url, '/');
        $parts      = parse_url($url);
        $path_parts = explode('/', $parts['path']);

        if (count($path_parts) > 1)
        {
            $user = $path_parts[count($path_parts) - 1];
            if ((int)method_exists($this, $user) > 0)
                $this->$user();
            else
                $this->response($this->json($status), 404);

        }
        else
        {
            $this->response($this->json($status), 200);
        }
    }

    /*
     *	Simple login API
     *  Login must be POST method
     *  email : <USER EMAIL>
     *  pwd : <USER PASSWORD>
     */

    /*private function login()
    {
        // Cross validation if the request method is POST else it will return "Not Acceptable" status
        if ($this->get_request_method() != "POST")
        {
            $status = array('status' => http_response_code());
            $this->response('', 406);
        }

        $email    = $this->_request['email'];
        $password = $this->_request['pwd'];

        // Input validations
        if (!empty($email) and !empty($password))
        {
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                $sql = mysql_query("SELECT user_id, user_fullname, user_email FROM users WHERE user_email = '$email' AND user_password = '" . md5($password) . "' LIMIT 1", $this->db);
                if (mysql_num_rows($sql) > 0)
                {
                    $result = mysql_fetch_array($sql, MYSQL_ASSOC);

                    // If success everythig is good send header as "OK" and user details
                    $this->response($this->json($result), 200);
                }
                $this->response('', 204); // If no records "No Content" status
            }
        }

        // If invalid inputs "Bad Request" status message and reason
        $error = array(
            'status' => "Failed",
            "msg"    => "Invalid Email address or Password"
        );
        $this->response($this->json($error), 400);
    }*/

    private function users()
    {
        // Cross validation if the request method is GET else it will return "Not Acceptable" status
        if ($this->get_request_method() != "POST")
        {
            $status = array(
                'request' => array(
                    'method' => $_SERVER['REQUEST_METHOD'],
                    'format' => 'json'
                ),
                'result'  => array(
                    'error' => 'Invalid request method'
                )
            );
            $this->response($this->json($status), 406);
        }
        if ($stmt = $this->db->prepare("SELECT user_id, user_fullname, user_email FROM users WHERE user_status = 1"))
        {
            $stmt->execute();
            $res = $stmt->fetchAll();
            if ($stmt->rowCount() > 0)
            {
                foreach ($res as $row)
                {
                    $result[] = array(
                        'user_id'       => $row['user_id'],
                        'user_fullname' => $row['user_fullname'],
                        'user_email'    => $row['user_email']
                    );
                }
                $output = array(
                    'status' => http_response_code(),
                    'users'  => $result
                );
                $this->response($this->json($output), 200);
            }
        }
        $this->response('', 204); // If no records "No Content" status
    }

    private function deleteUser()
    {
        // Cross validation if the request method is DELETE else it will return "Not Acceptable" status
        if ($this->get_request_method() != "DELETE")
        {
            $this->response('', 406);
        }
        $id = (int)$this->_request['id'];
        if ($id > 0)
        {
            mysql_query("DELETE FROM users WHERE user_id = $id");
            $success = array(
                'status' => "Success",
                "msg"    => "Successfully one record deleted."
            );
            $this->response($this->json($success), 200);
        }
        else
            $this->response('', 204); // If no records "No Content" status
    }

    /*
     *	Encode array into JSON
    */
    private function json($data)
    {
        if (is_array($data))
        {
            return json_encode($data);
        }
    }
}

// Initiiate Library

$api = new API;
$api->processApi();
?>