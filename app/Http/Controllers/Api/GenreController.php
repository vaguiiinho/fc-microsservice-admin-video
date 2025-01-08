<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\UseCase\DTO\Genre\Create\CreateGenreInputDto;
use Core\UseCase\DTO\Genre\Delete\DeleteGenreInputDto;
use Core\UseCase\DTO\Genre\List\ListGenreInputDto;
use Core\UseCase\DTO\Genre\List\ListGenresInputDto;
use Core\UseCase\DTO\Genre\Update\UpdateGenreInputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Core\UseCase\Genre\ListGenresUseCase;
use Core\UseCase\Genre\ListGenreUseCase;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
                page: (int) $request->get('page'),
                totalPage: (int) $request->get('totalPage')
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
                    'from' => $response->from,
                ],
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenreRequest $request, CreateGenreUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CreateGenreInputDto(
                name: $request->name,
                isActive: (bool) $request->is_active ?? true,
                categoriesId: $request->categories_id
            )
        );

        return (new GenreResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ListGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new ListGenreInputDto($id)
        );

        return new GenreResource($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new UpdateGenreInputDto(
                id: $id,
                name: $request->name,
                isActive: (bool) $request->is_active ?? true,
                categoriesId: $request->categories_id
            )
        );

        return new GenreResource($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteGenreUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteGenreInputDto($id));

        return response()->noContent();
    }
}
