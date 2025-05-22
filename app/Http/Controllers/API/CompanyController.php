<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ModelNotDeleteException;
use App\Exceptions\ServerErrorException;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{

    /**
    * @OA\Get(
    *   path="/api/v1/companies",
    *   tags={"Empresas"},
    *   summary="Mostrar el listado de empresas (Paginado)",
    *   @OA\Response(
    *       response="200", 
    *       description="OK",
    *       @OA\JsonContent(
    *           @OA\Property(
    *               @OA\Property(property="message", type="string", example="Empresas traido exitosamente"),
    *               @OA\Property(property="providers", type="string", example="[Listado de proveedores]"),
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="500", 
    *       description="Si algo en el servidor ocurre",
    *       @OA\JsonContent(
    *           @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado... intentelo mas tarde"),
    *       )
    *   ),
    * )
    */
    public function index(): AnonymousResourceCollection
    {
        //
        return CompanyResource::collection( Company::withTrashed()->paginate() );
        // return Company::all()->toResourceCollection();
    }

    /**
    * @OA\Post(
    *   path="/api/v1/companies",
    *   tags={"Empresas"},
    *   summary="Registrar empresa",
    *   @OA\RequestBody(
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(
    *                   type="object",
    *                   @OA\Property(property="nit", type="string"),
    *                   @OA\Property(property="name", type="string"),
    *                   @OA\Property(property="address", type="string"),
    *                   @OA\Property(property="phone", type="string"),
    *               ),
    *               example={
    *                   "nit": "123234234-9",
    *                   "name": "Coordinadora",
    *                   "address": "Cra 22 #13n - 65 Transversal, Ciudad Pereira",
    *                   "phone": "+57 122545545"
    *               }
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="201", 
    *       description="OK",
    *       @OA\JsonContent(
    *           @OA\Property(property="message", type="string", example="Empresa creada exitosamente"),
    *       )
    *   ),
    *   @OA\Response(
    *       response="500", 
    *       description="Si algo en el servidor ocurre",
    *       @OA\JsonContent(
    *           @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado... intentelo mas tarde"),
    *       )
    *   ),
    * )
    */
    public function store(CompanyRequest $companyRequest): JsonResponse
    {
        try {
            $company = Company::create( $companyRequest->validated() );
    
            return (new CompanyResource($company))
                    ->additional([
                        'message' => 'Empresa creada exitosamente',
                    ])
                    ->response()
                    ->setStatusCode(201);
        } catch (\Throwable $th) {
            
            Log::error($th);
            throw new ServerErrorException();
        }

    }

    /**
    * @OA\Get(
    *   path="/api/v1/companies/{nit}",
    *   tags={"Empresas"},
    *   summary="Traer empresa por NIT",
    *   @OA\Parameter(
    *         name="nit",
    *         in="path",
    *         required=true,
    *         @OA\Schema(type="string")
    *   ),
    *   @OA\Response(
    *       response="200", 
    *       description="OK",
    *       @OA\JsonContent(
    *           @OA\Property(
    *               @OA\Property(property="message", type="string", example="Empresa traido exitosamente"),
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="404", 
    *       description="Si el NIT no se encuentra",
    *       @OA\JsonContent(
    *           @OA\Property(
    *               @OA\Property(property="message", type="string", example="El recurso solicitado no fue encontrado."),
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="500", 
    *       description="Si algo en el servidor ocurre",
    *       @OA\JsonContent(
    *           @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado... intentelo mas tarde"),
    *       )
    *   ),
    * )
    */
    public function show(string $nit): CompanyResource
    {
        return new CompanyResource( Company::where('nit', $nit)->firstOrFail() );
    }


    /**
    *  @OA\Put(
    *   path="/api/v1/companies/{id}",
    *   tags={"Empresas"},
    *   summary="Actualizar empresa",
    *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         example=2,
    *         required=true,
    *         @OA\Schema(type="number")
    *   ),
    *   @OA\RequestBody(
    *       @OA\MediaType(
    *           mediaType="application/json",
    *           @OA\Schema(
    *               @OA\Property(
    *                   type="object",
    *                   @OA\Property(property="nit", type="string"),
    *                   @OA\Property(property="name", type="string"),
    *                   @OA\Property(property="address", type="string"),
    *                   @OA\Property(property="phone", type="string"),
    *                   @OA\Property(property="active", type="boolean"),
    *               ),
    *               example={
    *                   "nit": "123234234-9",
    *                   "name": "Coordinadora Envio Nacionales",
    *                   "address": "Cra 22 #13n - 65 Transversal, Ciudad Cali",
    *                   "phone": "+57 3154709447",
    *                   "active": false,
    *               }
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="201", 
    *       description="OK",
    *       @OA\JsonContent(
    *           @OA\Property(property="message", type="string", example="Empresa actualizada exitosamente"),
    *       )
    *   ),
    *   @OA\Response(
    *       response="404", 
    *       description="Si el ID no se encuentra",
    *       @OA\JsonContent(
    *           @OA\Property(
    *               @OA\Property(property="message", type="string", example="El recurso solicitado no fue encontrado."),
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="500", 
    *       description="Si algo en el servidor ocurre",
    *       @OA\JsonContent(
    *           @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado... intentelo mas tarde"),
    *       )
    *   ),
    * )
    */
    public function update(CompanyRequest $companyRequest, Company $company): JsonResponse
    {
        try {

            $company->update( $companyRequest->validated() );

            return (new CompanyResource($company))
                ->additional(['message' => 'Empresa actualizada exitosamente'])
                ->response();

        } catch (\Throwable $th) {

            Log::error($th);
            throw new ServerErrorException();
        }

    }

    /**
    * @OA\Delete(
    *   path="/api/v1/companies/{id}",
    *   tags={"Empresas"},
    *   summary="Traer empresa por NIT",
    *   @OA\Parameter(
    *         name="id",
    *         in="path",
    *         example=3,
    *         required=true,
    *         @OA\Schema(type="number")
    *   ),
    *   @OA\Response(
    *       response="200", 
    *       description="OK",
    *       @OA\JsonContent(
    *           @OA\Property(
    *               @OA\Property(property="message", type="string", example="Empresa traido exitosamente"),
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="401", 
    *       description="Bad",
    *       @OA\JsonContent(
    *           @OA\Property(
    *               @OA\Property(property="message", type="string", example="No puede eliminar esta empresa ya que se encuentra activa"),
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="404", 
    *       description="Si el ID no se encuentra",
    *       @OA\JsonContent(
    *           @OA\Property(
    *               @OA\Property(property="message", type="string", example="El recurso solicitado no fue encontrado."),
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response="500", 
    *       description="Si algo en el servidor ocurre",
    *       @OA\JsonContent(
    *           @OA\Property(property="message", type="string", example="Ha ocurrido un error inesperado... intentelo mas tarde"),
    *       )
    *   ),
    * )
    */
    public function destroy(Company $company): JsonResponse
    {
        try {

            if ($company->active) {
                return response()->json([
                    'message' => 'No puede eliminar esta empresa ya que se encuentra activa'
                ], 401);
            }
            
            $company->delete();

            return response()->json([
                'message' => 'Empresa eliminada exitosamente'
            ]);

        } catch (\Throwable $th) {

            Log::error($th);
            throw new ServerErrorException();
        }
    }
}
