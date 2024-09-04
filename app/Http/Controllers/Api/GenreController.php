<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreGenreRequest,
    UpdateGenreRequest
};
use App\Http\Resources\GenreResource;
use Core\UseCase\Genre\{
    CreateGenreUseCase,
    ListGenresUseCase,
    ListGenreUseCase,
    UpdateGenreUseCase,
    DeleteGenreUseCase
};
use Core\UseCase\DTO\Genre\Create\CreateGenreInputDto;
use Core\UseCase\DTO\Genre\Delete\DeleteGenreInputDto;
use Core\UseCase\DTO\Genre\List\ListGenresInputDto;
use Core\UseCase\DTO\Genre\List\GenreInputDto;
use Core\UseCase\DTO\Genre\Update\UpdateGenreInputDto;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ListGenresUseCase $UseCase)
    {
        $response = $UseCase->execute(
            input: new ListGenresInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('totalPage', 10)
            )
        );

        return GenreResource::collection(collect($response->items))
            ->additional([
                'meta' => [
                    'total' => (int) $response->total,
                    'current_page' => $response->current_page,
                    'first_page' => $response->first_page,
                    'last_page' => $response->last_page,
                    'per_page' => $response->per_page,
                    'to' => $response->to,
                    'from' => $response->from
                ]
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
