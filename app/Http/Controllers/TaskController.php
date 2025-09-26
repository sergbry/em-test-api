<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse {

        $tasks = Task::all();

        // Проверка наличия задач
        if($tasks->isEmpty()) {
            // Задачи отсутствуют - отдаём ошибку 404
            return response()->json(['tasks' => []]);
        }

        // Отдаём все задачи
        return response()->json(['tasks'=> $tasks]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : JsonResponse {

        // Получаем данные из запроса
        $data = $request->getPayload()->all();

        // Правила для валидации
        $rules = [
            'title' => 'required|max:255',
        ];

        // Проверяем данные
        $validator = Validator::make($data, $rules);

        // Возвращаем ошибку, если проверка провалилась
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        // Создаём новую запись
        $task = new Task();

        // Присваиваем заголовок
        $task->title = $request->title;

        // Присваиваем описание
        if (!empty($request->description)) {
            // Если оно пришло от клиента
            $task->description = $request->description;
        } else {
            // Если ничего не пришло - явно задаём Null для полного объекта в ответе
            $task->description = null;
        }

        // Явно указываем статус по умолчанию для полного объекта в ответе
        $task->status = 0;

        $task->save();

        // Если явно не задаём дефолтные значения,
        // то для формирования полного объекта в ответе
        // можем получить их из базы после сохранения таким образом (лишний запрос к базе)
//        $task->refresh();

        return response()->json(['message' => 'Task saved successfully.', 'task'=> $task], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) : JsonResponse
    {
        // Проверка айдишника
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Task id must be an integer.'], 400);
        }

        $task = Task::find((integer) $id);
        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) : JsonResponse
    {
        // Проверка айдишника
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Task id must be an integer.'], 400);
        }

        // Получаем данные из запроса
        $data = $request->getPayload()->all();

        // Правила для валидации
        $rules = [
            'title' => 'required|max:255',
            'description' => 'nullable',
            'status' => 'integer:strict',
        ];

        // Проверяем данные
        $validator = Validator::make($data, $rules);

        // Возвращаем ошибку, если проверка провалилась
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $task = Task::find((integer) $id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->status = $data['status'];

        $task->save();

        return response()->json(['message' => 'Task updated successfully.', 'task'=> $task], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : JsonResponse
    {
        // Проверка айдишника
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Task id must be an integer.'], 400);
        }

        $task = Task::find((integer) $id);

        if (!$task) {
            return response()->json(['message' => 'Task not found.'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully.'], 200);

    }
}
