<?php
namespace App\Service;

use App\User;
use Illuminate\Http\Request;

class UserService extends Service
{
    private $user;

    /**
     * Construct
     */
    public function __construct() 
    {
        $this->user = new User();
    }

    /**
     * Create a user \App\User
     * @param  \Illuminate\Http\Request  $request
     * @return object $user or false
     */
    public function createUser(Request $request)
    {
        try
        {
            $returnUser = $this->user->create([
                'username' => $request->get('username'),
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                'user_type' => $request->get('user_type'),
            ]);
            
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a user", 0, $e);
        }

        return $returnUser;
    }
}
?>