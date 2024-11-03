<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNotebookRequest;
use App\Http\Requests\UpdateNotebookRequest;
use App\Http\Resources\V1\NotebookResource;
use App\Models\Notebook;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class NotebookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/notebooks",
     *     summary="Получить список записей",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Номер страницы для пагинации",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список записей",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/NotebookResource"))
     *     )
     * )
     */
    public function index()
    {
        $notebooks = Notebook::paginate(10);
        return NotebookResource::collection($notebooks);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/notebooks",
     *     summary="Создать новую запись",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreNotebookRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Запись успешно создана",
     *         @OA\JsonContent(ref="#/components/schemas/NotebookResource")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Неверный запрос"
     *     )
     * )
     */
    public function store(StoreNotebookRequest $request)
    {
        $data = $request->all();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            $timestamp = Carbon::now()->format('Ymd_His');
            $fileName = $timestamp . '_' . $file->getClientOriginalName();

            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $data['photo'] = '/storage/' . $filePath;
        }

        $notebook = Notebook::create($data);

        return new NotebookResource($notebook);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/notebooks/{id}",
     *     summary="Получить данные записи по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID записи",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Данные записи",
     *         @OA\JsonContent(ref="#/components/schemas/NotebookResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Запись не найдена"
     *     )
     * )
     */
    public function show(Notebook $notebook)
    {
        return new NotebookResource($notebook);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/notebooks/{id}",
     *     summary="Обновить данные записи по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID записи",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateNotebookRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запись успешно обновлена",
     *         @OA\JsonContent(ref="#/components/schemas/NotebookResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Запись не найдена"
     *     )
     * )
     */
    public function update(UpdateNotebookRequest $request, Notebook $notebook)
    {
        $data = $request->all();

        if ($request->hasFile('photo') && $notebook->photo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $notebook->photo));

            $file = $request->file('photo');
            $timestamp = Carbon::now()->format('Ymd_His');
            $fileName = $timestamp . '_' . $file->getClientOriginalName();

            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $data['photo'] = '/storage/' . $filePath;
        }

        $notebook->update($data);

        return new NotebookResource($notebook);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/notebooks/{id}",
     *     summary="Удалить запись по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID записи",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Запись удалена",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Notebook removed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Запись не найдена"
     *     )
     * )
     */
    public function destroy(Notebook $notebook)
    {
        if ($notebook->photo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $notebook->photo));
        }
        $notebook->delete();
        return response()->json([
            'message' => 'Notebook removed'
        ]);
    }
}
