<?php 
namespace App\Service;

use Illuminate\Http\Request;
use JWTAuth;
use JWTAuthException;

class Service
{
    /**
     * Method to search a user logged
     * @param  \Illuminate\Http\Request  $request
     * @return object $user
     */
    public function getAuthUser(Request $request)
    {
        try
        {
            if (isset($_SERVER['HTTP_TOKEN']))
            {
                $user = JWTAuth::toUser($_SERVER['HTTP_TOKEN']);
            }
            else 
            {
                $user = JWTAuth::toUser($request->token);
            }
            
            return $user;            
        }
        catch (\Exception $e)
        {
            throw new Exception("Error to validate a user", 0, $e);
        }
    }

    /**
     * Method to search a user logged
     * @return object $user
     */
    public function getAuthUserNoRequest()
    {
        try
        {
            $user = JWTAuth::toUser($_SERVER['HTTP_TOKEN']);
            return $user;
        }
        catch (\Exception $e)
        {
            throw new Exception("Error to validate a user", 0, $e);
        }
    }
}
?>