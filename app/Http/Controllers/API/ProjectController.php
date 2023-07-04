<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Projects\CreateProjectRequest;
use App\Http\Requests\API\Projects\PatchProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller {

    private ProjectService $projectService;

    public function __construct() {
        $this->projectService = new ProjectService();
    }

    /**
     * @OA\Get(
     * path="/api/projects",
     * summary="Get all projects",
     * description="Get all projects",
     * operationId="getAll",
     * tags={"ProjectController"},
     *         @OA\Parameter(
     *         name="Authorization",
     *         description="Provide user authentication token",
     *         in="header",
     *         required=true
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Everything is ok.",
     *              @OA\JsonContent(
     *                  example={{"id" : 123, "name" : "Project name", "desicription" : "Project description", "created_at" : "2023-04-05 11:33:22", "updated_at" : "2023-04-05 11:33:22"}}
     *              ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Authorization is invalid."
     *      )
     * )
     */
    public function getAll() {
        $projects = $this->projectService->getAllProjects();
        return response()->json($projects);
    }

    /**
     * @OA\Post(
     * path="/api/projects",
     * summary="Create a project",
     * description="Create a project",
     * operationId="create",
     * tags={"ProjectController"},
     *         @OA\Parameter(
     *         name="Authorization",
     *         description="Provide user authentication token",
     *         in="header",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Body to create a new project",
     *          @OA\JsonContent(
     *               required={"name", "description"},
     *                @OA\Property(property="name", type="string", format="string", example="Project name"),
     *                @OA\Property(property="description", type="string", format="string", example="Description"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Everything is ok.",
     *              @OA\JsonContent(
     *                  example={"id" : 123, "name" : "Project name", "desicription" : "Project description", "created_at" : "2023-04-05 11:33:22", "updated_at" : "2023-04-05 11:33:22"}
     *              ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request, read the msg of the status object.",
     *          @OA\JsonContent(
     *              example={"status":{"code":-1,"msg":"name already taken."}}
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Authorization is invalid."
     *      )
     * )
     */
    public function create(CreateProjectRequest $request) {
        $project = $this->projectService->createProject($request);
        return response()->json($project, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     * path="/api/projects/{id}",
     * summary="Get a project",
     * description="Get a project",
     * operationId="getById",
     * tags={"ProjectController"},
     *         @OA\Parameter(
     *         name="Authorization",
     *         description="Provide user authentication token",
     *         in="header",
     *         required=true
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Everything is ok.",
     *              @OA\JsonContent(
     *                  example={"id" : 123, "name" : "Project name", "desicription" : "Project description", "created_at" : "2023-04-05 11:33:22", "updated_at" : "2023-04-05 11:33:22"}
     *              ),
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Authorization is invalid."
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Object not found.",
     *      ),
     * )
     */
    public function getById($id) {
        $project = $this->projectService->findById($id);
        if ($project == null) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
        return response()->json($project);
    }

    /**
     * @OA\Patch(
     * path="/api/projects/{id}",
     * summary="Patch a project",
     * description="Patch a project",
     * operationId="patch",
     * tags={"ProjectController"},
     *         @OA\Parameter(
     *         name="Authorization",
     *         description="Provide user authentication token",
     *         in="header",
     *         required=true
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Body to Patch a new project",
     *          @OA\JsonContent(
     *               required={"name", "description"},
     *                @OA\Property(property="name", type="string", format="string", example="Project name"),
     *                @OA\Property(property="description", type="string", format="string", example="Description"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Everything is ok.",
     *              @OA\JsonContent(
     *                  example={"id" : 123, "name" : "Project name", "desicription" : "Project description", "created_at" : "2023-04-05 11:33:22", "updated_at" : "2023-04-05 11:33:22"}
     *              ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request, read the msg of the status object.",
     *          @OA\JsonContent(
     *              example={"status":{"code":-1,"msg":"name already taken."}}
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Authorization is invalid."
     *      )
     * )
     */
    public function patch($id, PatchProjectRequest $request) {
        Log::debug('updating: ' . $id);
        $project = $this->projectService->patchProject($id, $request);
        return response()->json($project);
    }

    /**
     * @OA\Delete(
     * path="/api/projects/{id}",
     * summary="Delete a project",
     * description="Delete a project",
     * operationId="delete",
     * tags={"ProjectController"},
     *         @OA\Parameter(
     *         name="Authorization",
     *         description="Provide user authentication token",
     *         in="header",
     *         required=true
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Everything is ok."
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request, read the msg of the status object.",
     *          @OA\JsonContent(
     *              example={"status":{"code":-1,"msg":"name already taken."}}
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Authorization is invalid."
     *      )
     * )
     */
    public function delete($id) {
        $this->projectService->delete($id);
        return response()->json();
    }
}
