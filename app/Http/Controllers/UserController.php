<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator, DB, Hash, Mail;
use App\User;
use App\Address;
use App\Phone;
use JWTAuth;
use JWTAuthException;
use \App\Response\Response;
use \App\Service\UserService;
use \App\Service\CloudinaryService;

class UserController extends Controller
{
    private $address;
    private $cloudinary;
    private $messages;
    private $phone;
    private $response;
    private $user;
    private $userService;

    public function __construct() 
    {
        $this->address      = new Address;
        $this->cloudinary   = new CloudinaryService();
        $this->messages     = \Config::get('messages');
        $this->phone        = new Phone;
        $this->response     = new Response();
        $this->user         = new User;
        $this->userService  = new UserService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();

        $this->response->setDataSet("user", $users);
        return response()->json($this->response->toString(true, $this->messages['user']['show']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $urlPicture = "https://material.angular.io/assets/img/examples/shiba1.jpg";

            if($request->get('profile_picture'))
            {
                $picutre = $this->cloudinary->uploadFile($request);
                $urlPicture = $picutre['url'];
            }

            DB::beginTransaction();
            $returnUser = $this->userService->createUser($request, $urlPicture);

            $returnUser->address = $this->userService->createAddressUser($returnUser->id, $request);
            $returnUser->phone = $this->userService->createPhoneUser($returnUser->id, $request);
            
            $name = $returnUser->name;
            $email = $returnUser->email;
            $urlFront = getenv('FRONT_URL') . "/confirm-verification";
            
            $verification_code = str_random(30);//Generate verification code

            DB::table('user_verifications')->insert(['user_id' => $returnUser->id, 'token' => $verification_code]);

            $subject = "Please verify your email address.";

            Mail::send('email.verify', ['name' => $name, 'url' => $urlFront, 'verification_code' => $verification_code],
                function($mail) use ($email, $name, $subject)
                {
                    $mail->from("noreplay@connectstars.com", "Connect Stars");
                    $mail->to($email, $name);
                    $mail->subject($subject);
                }
            );
            
            DB::commit();

            $this->response->setDataSet("user", $returnUser);
            return response()->json($this->response->toString(true, $this->messages['user']['create']));
            
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json($this->response->toString(false, $e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            $user = $this->user->find($id);

            if(!$user)
            {
                return response()->json($this->response->toString(false, $this->messages['error']));
            }
            else
            {
                $user->addresses = $user->addresses()->get();
                $user->phones = $user->phones()->get();

                $this->response->setDataSet("user", $user);
                
                return response()->json($this->response->toString(true, $this->messages['user']['show']));
            }
        }
        catch (\Exception $e)
        {
            return response()->json($this->response->toString(false, $this->messages['error']));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try
        {
            $user = $this->user->find($id);

            if(!$user)
            {
                return response()->json($this->response->toString(false, $this->messages['error']));
            }

            /**
             * Ainda é gambiarra, organizar isso
             */
            DB::beginTransaction();
            $user->fill([
                $request->all(),
                'password' => bcrypt($request->get('password')),
            ]);
            $user->save();

            $addresses = $request->get('addresses');
            $phones = $request->get('phones');
            
            foreach ($addresses as $address) {
                $user->addresses()->update($address);    
            }

            foreach ($phones as $phone) {
                $user->phones()->update($phone);    
            }
            
            /**
             * Fim da Gambiarra
             */

            $this->response->setDataSet("user", $user);

            DB::commit();
            return response()->json($this->response->toString(true, $this->messages['user']['save']));
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json($this->response->toString(false, $e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $user = $this->user->find($id);
            $user_logged = $this->userService->getAuthUserNoRequest();

            if(!$user || $user_logged->id != $id)
            {
                return response()->json($this->response->toString(false, $this->messages['error']));
            }

            DB::beginTransaction();
             /**
             * Delete all dependencies of a user
             */
            $user->addresses()->delete();
            $user->phones()->delete();
            $user->artistOnEvents()->delete();
            $user->delete();

            DB::commit();
            return response()->json($this->response->toString(true, $this->messages['user']['delete']));

        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json($this->response->toString(false, $e->getMessage()));
        }
    }

    /**
     * Update a profile picture to user 
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePicture($id, Request $request)
    {
        try
        {
            $picutre = $this->cloudinary->uploadFile($request);
            $user = $this->user->find($id);

            if (!$picutre || !$user)
            {
                return response()->json($this->response->toString(false, $this->messages['error']));
            }

            $user->fill([
                'profile_picture' => $picutre['url'],
            ]);

            $user->save();

            $this->response->setDataSet("picture", $picutre);
            return response()->json($this->response->toString(true, $this->messages['user']['picture']));
        }
        catch (\Exception $e)
        {
            return response()->json($this->response->toString(false, $e->getMessage()));
        }
    }

    public function teste()
    {
        return response()->json($this->response->toString(true, $this->messages['user']['delete']));
    }
}
