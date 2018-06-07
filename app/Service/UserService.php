<?php
namespace App\Service;

use Illuminate\Http\Request;
use App\User;
use App\Addres;
use App\Phone;

class UserService extends Service
{
    private $user;
    private $address;
    private $phone;

    /**
     * Construct
     */
    public function __construct() 
    {
        $this->user     = new User();
        $this->address  = new Addres();
        $this->phone    = new Phone();
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
                'username'  => $request->get('username'),
                'name'      => $request->get('name'),
                'email'     => $request->get('email'),
                'password'  => bcrypt($request->get('password')),
                'user_type' => $request->get('user_type'),
            ]);
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a user", 0, $e);
        }

        return $returnUser;
    }

    /**
     * Create address to user
     * @param int $userId
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function createAddressUser($userId, Request $request) 
    {
        try 
        {
            foreach ($request->get('addres') as $key => $value)
            {
                $returnAddressUser[] = $this->user->create([
                    'street'    => $request->get('street'),
                    'city'      => $request->get('city'),
                    'state'     => $request->get('state'),
                    'zip_code'  => $request->get('zip_code'),
                    'country'   => $request->get('country'),
                    'user_id'   => $userId,
                ]);
            }
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a address", 0, $e);
        }

        return $returnAddressUser;
    }

    /**
     * Create phone to user
     * @param int $userId
     * @param  \Illuminate\Http\Request  $request
     * @return object
     */
    public function createPhoneUser($userId, Request $request)
    {
        try 
        {
            foreach ($request->get('addres') as $key => $value)
            {
                $returnPhoneUser[] = $this->phone->create([
                    'country_code'  => $request->get('country_code'),
                    'number'        => $request->get('number'),
                    'user_id'       => $userId,
                ]);
            }
        }
        catch(Exception $e)
        {
            throw new Exception("Error to create a phone", 0, $e);
        }

        return $returnPhoneUser;
    }
}
?>