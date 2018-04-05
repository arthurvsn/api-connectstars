<?php
namespace App\Service;

use App\User;
use Illuminate\Http\Request;

class UserService extends Service
{
    private $user;

    public function __construct() 
    {
        $this->user = new User();
    }

    public function createUser(Request $request)
    {
        try
        {
            $returnUser = $this->user->create([
                'username' => $request->get('username'),
                'name' => $request->get('name'),
                'name' => $request->get('email'),
                'name' => $request->get('user_type'),
                'password' => bcrypt($request->get('password')),
                'user_type' => $request->get('user_type'),
            ]);
            
            return $returnUser;
        }
        catch(Exception $e)
        {
            return false;
        }

    }
}
?>