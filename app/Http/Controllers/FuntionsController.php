<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FuntionsController extends Controller
{
    public function __construct()
    {
        //middleware  
    }

    //funciones escalares
    //funcion que retorna el numero de aviones que posee una aerolinea
    public function airlineStock(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'alias' => 'required'
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
                $alias = $data['alias'];
                $stock = DB::select("Select dbo.f_AirlineStock ('$alias') as 'stock'");
                if (!empty($stock)) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'data' => $stock[0]
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se pudieron encontrar los datos'
                    );
                }
            }
        } else {
            $response = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Ya wey, me dio amsiedad'
            );
        }
        return response()->json($response, $response['code']);
    }


    //METODO PARA LLAMAR A UNA FUNCION
    // se debe crear una ruta especifica y se envian los datos por params en postman
    public function findEmployee(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'idAirport' => 'required|numeric',
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
                $id = $data['idAirport'];
                $emp = DB::select("select * from f_airportEmployee ('$id')");
                if (!empty($emp[0])) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'data' => $emp[0]
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

    //calculo de promedio de precio a un destino
    //manejar un null desde la vista para mostrar un mensaje de que no se tiene promedio de vuelos para ese pais
    public function priceCountry(Request $request){
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'country' => 'required',
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
                $country = $data['country'];
                $funtion = DB::select("select dbo.f_PriceAverage ('$country') as 'promedio'");
                if (!empty($funtion)) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'data' => $funtion
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se cuenta con vuelos a este destino'
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
        return response()->json($funtion, $response['code']);
    }

    //metodo que retorna los vuelos mas baratos segun su criterio de gasto
    public function OfertFlight(Request $request){
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'country' => 'required',
                'price' => 'required||numeric'
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
                $funtion = DB::select("select * from dbo.f_price_offers ('$data[country]',$data[price])");
                if (!empty($funtion)) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'data' => $funtion
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se cuenta con vuelos a este destino'
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
        return response()->json($funtion, $response['code']);
    }


    //Pilotos de una aerolina por medio de su alias
    public function pilotAirline (Request $request){
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'alias' => 'required',
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
                $funtion = DB::select("select * from dbo.f_pilot_airline ('$data[alias]')");
                if (!empty($funtion)) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'data' => $funtion
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Esta aerolinea no posee pilotos ene este momento'
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
        return response()->json($funtion, $response['code']);
    }

    //vuelos que se dirigen a un destino
    public function destinyFly(Request $request){
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [ //se dictan las reglas en cuanto al ingreso de los datos
                'country' => 'required',
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
                $funtion = DB::select("select * from dbo.f_destinyFlights ('$data[country]')");
                if (!empty($funtion)) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'data' => $funtion
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Este destino no posee vuelos ene este momento'
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
        return response()->json($funtion, $response['code']);
    }


    //METODO DE CREACION DE UN RESPALDO
    public function backupBD(Request $request)
    {
        $json = $request->input('json', null);
        $data = json_decode($json, true);
        if (!empty($data)) {
            $data = array_map('trim', $data);
            $rules = [
                'name' => 'required'
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
                $name = [$data['name']];
                $save = DB::insert(DB::raw('EXEC spBackUpDB ?'), $name);
                if ($save) {
                    $response = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Copia de seguridad creada exitosamente'
                    );
                } else {
                    $response = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'No se pudo crear el respaldo de los datos'
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
}
