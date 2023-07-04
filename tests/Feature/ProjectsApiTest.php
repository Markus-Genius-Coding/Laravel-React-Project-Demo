<?php

namespace Tests\Feature;

use App\Services\ProjectService;
use Database\Seeders\DefaultUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProjectsApiTest extends TestCase {
    /**
     * A basic feature test example.
     */
    public function test_getAllProjectsWithoutAuthorization(): void {
        $response = $this->get('/api/projects');
        $response->assertStatus(403);
    }

    public function test_successfulGetAllProjects(): void {

        $authData = $this->login();

        // we expect user data in our database from the defaultprojectseeder!
        $response = $this->get('/api/projects', [
            'Authorization' => $authData['access_token'],
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            [
                'id',
                'name',
                'description',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    public function test_createProjectWithoutAuthorization(): void {
        $response = $this->post('/api/projects');
        $response->assertStatus(403);
    }

    public function test_successfulCreateProject(): void {

        $authData = $this->login();

        // we expect user data in our database from the defaultprojectseeder!
        $response = $this->post('/api/projects', [
            'name' => "Unique project name " . time(),
            'description' => 'My Project Description!'
        ], [
            'Authorization' => $authData['access_token'],
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'name',
            'description',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_PatchProjectWithoutAuthorization(): void {
        $response = $this->patch('/api/projects/1', [
            'name' => time(),
            'description' => time()
        ]);
        $response->assertStatus(403);
    }

    public function test_successfulPatchProject(): void {

        $authData = $this->login();
        // we expect user data in our database from the defaultprojectseeder!
        $response = $this->patch('/api/projects/1', [
            'name' => 'patched name',
            'description' => 'My Project Description!'
        ], [
            'Authorization' => $authData['access_token'],
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'description',
            'created_at',
            'updated_at',
        ]);

        $projectService = new ProjectService();
        $project = $projectService->findById(1);
        self::assertTrue($project->name == 'patched name');
    }

    public function test_GetProjectWithoutAuthorization(): void {
        $response = $this->get('/api/projects/1');
        $response->assertStatus(403);
    }

    public function test_successfulGetProject(): void {

        $authData = $this->login();
        // we expect user data in our database from the defaultprojectseeder!
        $response = $this->get('/api/projects/1', [
            'Authorization' => $authData['access_token'],
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'id',
            'name',
            'description',
            'created_at',
            'updated_at',
        ]);

        //try to get a nonexisting project
        $response = $this->get('/api/projects/127', [
            'Authorization' => $authData['access_token'],
        ]);
        $response->assertStatus(404);

    }

    public function test_DeleteProjectWithoutAuthorization(): void {
        $response = $this->delete('/api/projects/1');
        $response->assertStatus(403);
    }

    public function test_successfulDeleteProject(): void {

        $authData = $this->login();
        Log::debug("auth", [$authData]);
        // we expect user data in our database from the defaultprojectseeder!
        $response = $this->withHeaders([
            'Authorization' => $authData['access_token'],
        ])->delete('/api/projects/1');
        $response->assertStatus(200);

        //try to get the deleted project
        $response = $this->get('/api/projects/1', [
            'Authorization' => $authData['access_token'],
        ]);
        $response->assertStatus(404);

    }

    private function login() : array {
        $response = $this->post('/api/user/login', [
            'email' => DefaultUserSeeder::DEFAULT_EMAIL,
            'password' => DefaultUserSeeder::DEFAULT_PASSWORD,
        ]);
        return (array)json_decode($response->getContent())->authdata;
    }

}
