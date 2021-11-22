<?php

namespace App\Http\Controllers;

use App\Models\Pilot;
use Illuminate\Http\Request;

class PilotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Pilot::all();
        if (!empty($data)) {
           //$data = $data->load('employee');
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
        $data = json_decode($json,true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'id' => 'required|numeric',
                'idEmployee' => 'required|numeric'
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
                $pilot = new Pilot();
                $pilot->id = $data['id'];
                $pilot->idEmployee = $data['idEmployee'];
                $save = $pilot->save();
                if ($save > 0) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos actualizados exitosamente'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 409,
                        'message' => 'No se pudo actualizar los datos'
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Pilot::find($id);
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
     * @param  \App\Models\Pilot  $pilot
     * @return \Illuminate\Http\Response
     */
    public function edit(Pilot $pilot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pilot  $pilot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pilot $pilot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int$id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id)) {
            $deleted = Pilot::where('id', $id)->delete();
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
