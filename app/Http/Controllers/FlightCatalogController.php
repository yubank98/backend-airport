<?php

namespace App\Http\Controllers;

use App\Models\FlightCatalog;
use Illuminate\Http\Request;

class FlightCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = FlightCatalog::all();
        if (!empty($data)) {
           //$data = $data->load('aeroline','departure','arrival');
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
                'aeroline' => 'required|numeric',
                'departure' => 'required|numeric',
                'arrival' => 'required|numeric',
                'estimated_time' => 'required',
                'price_ticket' => 'required|numeric',
                'status' => 'required|alpha'
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
                $FC = new FlightCatalog();
                $FC->id = $data['id'];
                $FC->aeroline = $data['aeroline'];
                $FC->departure = $data['departure'];
                $FC->arrival = $data['arrival'];
                $FC->estimated_time = $data['estimated_time'];
                $FC->price_ticket = $data['price_ticket'];
                $FC->status = $data['status'];
                $save = $FC->save();
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
        $data = FlightCatalog::find($id);
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
     * @param  \App\Models\FlightCatalog  $flightCatalog
     * @return \Illuminate\Http\Response
     */
    public function edit(FlightCatalog $flightCatalog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
                'surname' => 'required|alpha',
                'airport' => 'required|numeric'
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
                $updated = FlightCatalog::where('id', $id)->update($data);
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
     * @param  \App\Models\FlightCatalog  $flightCatalog
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if (isset($id)) {
            $deleted = FlightCatalog::where('id', $id)->delete();
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
                    'message' => 'Problemas al eleminar el recurso, puede ser que el recurso no exista'
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
