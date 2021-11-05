<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use DB;
use Illuminate\Support\Facades\DB as FacadesDB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['login','store']]);
    }

    public function __invoke()
    {
        #permite que la clase se invocable
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = user::all();
        if (!empty($data)) {
            //$data = $data->load('airplanes');
            $response = array(
                'status' => 'success',
                'code' => 200,
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Recurso Vacio o no Encontrado'
            );
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'idEmployee' => 'required|numeric|unique:users',
                'username' => 'required|unique:users',
                'password' => 'required',
            ];
            $valid = \validator($data, $rules);
            if ($valid->fails()) { //se valida un fallo 
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos son incorrectos',
                    'errors' => $valid->errors()
                );
            } else { //sin fallos y se procede a agregar
                $user = new User();
                #$user->id = $data['id'];
                $user->idEmployee = $data['idEmployee'];
                $user->username = $data['username'];
                $user->role = 'user';
                $user->password = hash('sha256', $data['password']);
                $user->role = $data['role'];
                $user->save();
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos almacenados exitosamente'
                );
            }
        } else {
            $response = array(
                'status' => 'NOT FOUND',
                'code' => 404,
                'message' => 'Datos no encontrados'
            );
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = User::find($id);
        if (is_object($data)) {
            $response = array(
                'status' => 'success',
                'code' => 200,
                'data' => $data
            );
        } else {
            $response = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Recurso no encontrado'
            );
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];
            $valid = \validator($data, $rules);
            if ($valid->fails()) { //se valida un fallo 
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos son incorrectos',
                    'errors' => $valid->errors()
                );
            } else {
                $username = $data['username'];
                unset($data['id']);
                unset($data['idEmployee']);
                unset($data['create_at']);
                $data['password'] = hash('sha256', $data['password']); //se cifra la nueva contraseña
                $updated = User::where('username', $username)->update($data);
                if ($updated > 0) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Actualizado correctamente'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se pudo actualizar, puede que el usuario no exita'
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'NOT FOUND',
                'code' => 404,
                'message' => 'Datos no encontrados'
            );
        }
        return response()->json($response, $response['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $deleted = User::where('id', $id)->delete();
            if ($deleted) {
                $response = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Eliminado correctamente'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'No se pudo eliminar'
                );
            }
        } else {
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Falta el identificador del recurso'
            );
        }
        return response()->json($response, $response['code']);
    }

    #metodo de manejo de login

    public function login(Request $request)
    {
        $jwtAuth = new JwtAuth();
        $json = $request->input('json', null);
        $data = json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rule = [
                'username' => 'required',
                'password' => 'required',
            ];
            $validated = \validator($data, $rule);
            if ($validated->fails()) {
                $response = array(
                    'status' => 'error',
                    'code' => '406',
                    'message' => 'Los datos enviados son incorrectos',
                    'errors' => $validated->errors()
                );
            } else {
                $response = $jwtAuth->signin($data['username'], $data['password']);
            }
            if (isset($response['code'])) {
                return response()->json($response, $response['code']);
            } else {
                return response()->json($response, 200);
            }
        } else {
            $response = array(
                'status' => 'NOT FOUND',
                'code' => 404,
                'message' => 'Datos no encontrados'
            );
        }
        return response()->json($response, $response['code']);
    }

    public function getIdentity(Request $request)
    { //se valida la identidad
        $jwtAuth = new JwtAuth();
        $token = $request->header('token');
        $response = $jwtAuth->verify($token,true);
        return response()->json($response);
    }

}
