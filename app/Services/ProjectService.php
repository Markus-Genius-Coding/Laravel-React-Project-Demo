<?php

namespace App\Services;

use App\Http\Requests\API\Projects\CreateProjectRequest;
use App\Http\Requests\API\Projects\PatchProjectRequest;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectService {

    /**
     * @return Collection
     */
    public function getAllProjects() : Collection {
        return Project::get();
    }

    /**
     * @param int $id
     * @return Project|null
     */
    public function findById(int $id) :? Project {
        return Project::where('id', $id)->first();
    }

    /**
     * @param CreateProjectRequest $request
     * @return Project
     */
    public function createProject(CreateProjectRequest $request) : Project {
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->save();
        return $project;
    }

    /**
     * @param int $id
     * @param PatchProjectRequest $request
     * @return Project
     */
    public function patchProject(int $id, PatchProjectRequest $request) : Project {
        $project = $this->findById($id);
        $project->name = $request->name;
        $project->description = $request->description;
        $project->save();
        return $project;
    }

    public function delete(int $id) {
        Project::where('id', $id)->delete();
    }

}
