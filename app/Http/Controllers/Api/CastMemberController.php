<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreCastMemberRequest,
    UpdateCastMemberRequest
};
use App\Http\Resources\CastMemberResource;
use Core\UseCase\CastMember\{
    CreateCastMemberUseCase,
    ListCastMembersUseCase,
    ListCastMemberUseCase,
    UpdateCastMemberUseCase,
    DeleteCastMemberUseCase
};
use Core\UseCase\DTO\CastMember\Create\CreateCastMemberInputDto;
use Core\UseCase\DTO\CastMember\Delete\DeleteCastMemberInputDto;
use Core\UseCase\DTO\CastMember\List\{
    ListCastMemberInputDto,
    ListCastMembersInputDto,
};
use Core\UseCase\DTO\CastMember\Update\UpdateCastMemberInputDto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ListCastMembersUseCase $UseCase)
    {
        $response = $UseCase->execute(
            input: new ListCastMembersInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page'),
                totalPage: (int) $request->get('totalPage')
            )
        );

        return CastMemberResource::collection(collect($response->items))
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
    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CreateCastMemberInputDto(
                name: $request->name,
                type: (int) $request->type,
            )
        );

        return (new CastMemberResource($response))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ListCastMemberUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new ListCastMemberInputDto($id)
        );
        
        return (new CastMemberResource($response))
            ->response();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCastMemberRequest $request, UpdateCastMemberUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new UpdateCastMemberInputDto(
                id: $id,
                name: $request->name,
            )
        );

        return (new CastMemberResource($response))
            ->response();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCastMemberUseCase $useCase, $id)
    {
        $useCase->execute(new DeleteCastMemberInputDto($id));

        return response()->noContent();
    }
}
