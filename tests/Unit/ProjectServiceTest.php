<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Services\ProjectService;
use Database\Seeders\DefaultProjectSeeder;
use Tests\TestCase;

class ProjectServiceTest extends TestCase {

    public function setUp(): void {
        parent::setUp();
    }

    public function tearDown(): void {
        parent::tearDown();
    }

    /**
     * A basic unit test example.
     */
    public function test_getAllProjects(): void {
        $projectService = new ProjectService();
        $projects = $projectService->getAllProjects();
        self::assertTrue(count($projects) == 1);
    }

    public function test_getProjectWithId(): void {
        $projectService = new ProjectService();
        self::assertNotNull($projectService->findById(1));
        self::assertNull($projectService->findById(127));
    }

 }
