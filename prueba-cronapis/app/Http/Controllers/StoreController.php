<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreCollection;
use App\Http\Resources\StoreResource;
use Illuminate\Support\Facades\URL;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Buscar y almacenar los datos en una collection formateada
        $stores = StoreResource::collection(Store::all());

        if (count($stores) < 1) {
            $return = [
                'message' => 'No hay registros en la base de datos',
                'code'    => 401  
            ];
        }else{
            $return = [
                'data' => $stores,
                'code'    => 200  
            ];
        }

        // Retornar los datos
        return response($return, $return['code']);
    }

    /**
     * Display a paginate listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginate(Request $request)
    {

            // Verificar si existe una variable GET
            // que coincida con 'perp' con la cantidad
            // de items por pagina, por defecto en 5
            $perPage = is_numeric($request->has('perp')) ? $request->by : 5;
    
            // Buscar y almacenar los datos en una collection formateada
            $stores = new StoreCollection(Store::paginate($perPage));

            // Contar los resultados
            if (COUNT($stores) < 1) {
                
                // Variable de respuesta en caso
                // de ser 0
                return response([
                    'message' => 'No hay registros'                    
                ], 404);

            }else{

                // Retornar la paginacion, con todos
                // los datos necesarios.
                return $stores;
            }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        // Validar la request
        $request->validated();

        try {

            // Almacenar la imagen
            $image_url = URL::to('/') . '/storage/' . $request->file('image')->store('stores');   
            
            // Crear una nueva instancia de Store
            $store = new Store();

            // Almacenar los datos
            $store->nombre = $request->nombre;
            $store->direccion = $request->direccion;
            $store->email = $request->email;
            $store->ubicacion = json_encode([
                'longitud' => floatval($request->longitud), 
                'latitud' => floatval($request->latitud)
            ]);  
            $store->telefono = $request->telefono;
            $store->image_url = $image_url;

            // Guardar en la base de datos
            $store->save();  
            
            // Variable de respuesta
            $response = [
                'status' => 'ok',
                'code' => 200,
                'message' => 'La tienda se ha almacenado correctamente'
            ];

        } catch (\Exception $e) {

            // Variable de respuesta
            $response = [
                'status' => 'ok',
                'code' => 400,
                'message' => 'Ha ocurrido un error'
            ];
            
        }
        
        // Retornar respuesta en Json
        return response()->json($response,$response['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show($store)
    {
        try {
            // Buscar el registro en la base de datos
            $store = new StoreResource(Store::findOrFail($store));

            // Variable de retorno con el registro
            $response = [
                'data' => $store,
                'code' => 200
            ];
        } catch (\Throwable $th) {

            // Variable de retorno con el mensaje de error
            $response = [
                'message' => $th->getMessage(),
                'code'    => 404
            ];
        }

        // Retornar una respuesta
        return response($response);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        // Obtener la ruta de la imagen a eliminar
        $path = parse_url($store->image_url)['path'];
        
        try {

            // Eliminar el archivo con la funcion 'unlink'
            if(unlink(public_path($path))){

                // Eliminar el registro de la base de datos
                $store->delete();

                // Retornar una respuesta
                $return = [
                    'message' => 'Tienda eliminada',
                    'code'    => 200
                ];
            }else{

                // Retornar respuesta en caso de no
                // poder eliminar la imagen
                $return = [
                    'message' => 'No se puede eliminar la imagen',
                    'code'    => 200
                ];
            };
        } catch (\Throwable $th) {

            // Retornar una respuesta en caso de 
            // otro error.
            $return = [
                'message' => 'Ocurrio un error',
                'error' => $th->getMessage(),
                'code'    => 400
            ];
        }

        return response($return,$return['code']);
    }
}
