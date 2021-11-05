<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use Illuminate\Http\Request;
use DB;
class AirportController extends Controller
{
    public function __construct()
    {
      //middleware  
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Airport::all();
        if (!empty($data)) {
           //$data = $data->load('employee','Input_Catalog','Output_Catalog');
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
      //invalide   
    }

    //funcion store utilizando una funcion escalar
    public function store(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required|numeric',
                'name' => 'required',
                'city' => 'required',
                'country' => 'required'
            ];
            $valid = \validator($data, $rules);
            if ($valid->fails()) {
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos son incorrectos',
                    'errors' => $valid->errors()
                );
            } else {
                $datos = [$data['id'],$data['name'],$data['city'],$data['country']];
                $save = DB::insert('exec spAirportCreate ?, ?, ?, ?',$datos);
                if ($save > 0) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos ingresados exitosamente'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se pudo ingresar los datos'
                    );
                }
            }
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Airport::find($id);
        if(is_object($data)){
            $response = array(
                'status' => 'success',
                'code' => 200,
                'data' => $data
            );
        }else{
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
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'id' => 'required|numeric',
                'name' => 'required',
                'city' => 'required|alpha',
                'country' => 'required|alpha'
            ];
            $validate = \validator($data, $rules);
            if ($validate->fails()) { //determina si los datos siguen las reglas
                $response = array(
                    'status' => 'error',
                    'code' => 406,
                    'message' => 'Los datos enviados son incorrectos',
                    'errors' => $validate->errors()
                );
            } else {
                $id = $data['id'];
                unset($data['id']);
                unset($data['created_at']);
                $updated = Airport::where('id', $id)->update($data);
                if ($updated > 0) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos actualizados exitosamente'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se pudo actualizar los datos'
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Faltan Datos'
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
            $deleted = Airport::where('id', $id)->delete();
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
                    'message' => 'Problemas al eliminar el recurso, puede ser que el recurso no exista'
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
}
