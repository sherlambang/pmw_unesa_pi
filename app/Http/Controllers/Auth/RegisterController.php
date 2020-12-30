<?php

namespace PMW\Http\Controllers\Auth;

use PMW\Models\HakAkses;
use PMW\User;
use PMW\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;
use PMW\Mail\RegisterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use PMW\Models\Mahasiswa;
use PMW\Events\UserTerdaftar;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    private $generatedPassword;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('guest');       
    }

    public function register(Request $request)
    {
        // validasi
        $this->validator($request->all())->validate();

        // membuat user
        $user = $this->create($request->all());

        $this->registered($request,$user);

        // kembali halaman auth
        return redirect()->route('login', [
            'tab' => 'register',
            'message' => 'Berhasil Mendaftar ! Silahkan cek email anda untuk melihat kata sandi'
        ]);
    }

    /**
     * Melakukan pengiriman email ke pengguna dan mengatur hak akses
     * yang akan diterima
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    protected function registered(Request $request, $user) 
    {
        event(new UserTerdaftar($user, [], $this->generatedPassword));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'id' => 'required|unique:pengguna|numeric',
            'email' => 'required|unique:pengguna|email'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $this->generatedPassword = str_random(8);

        return User::create([
            'id' => $data['id'],
            'email' => $data['email'],
            'password' => bcrypt($this->generatedPassword),
            'request' => false
        ]);
    }

}
