<?php

namespace Tests\Feature;

use App\Database\Seeds\DemoSeeder;
use App\Models\TaskModel;
use App\Models\UserModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/** @internal */
final class TaskPagesTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $namespace = 'App';
    protected $seed = DemoSeeder::class;

    protected function setUp(): void
    {
        // UTC is still October 9; the application must use October 10 in Manila.
        Time::setTestNow('2026-10-09 16:30:00', 'UTC');
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Time::setTestNow();
        parent::tearDown();
    }

    public function testSeederMeetsRecordAndDateRequirements(): void
    {
        $tasks = (new TaskModel())->allByDate();
        $this->assertCount(8, $tasks);
        $this->assertSame(['2026-10-09', '2026-10-10', '2026-10-11'], array_values(array_unique(array_column($tasks, 'task_date'))));
        $this->assertSame(1, (new UserModel())->countAllResults());
    }

    public function testSeederCanBeRepeatedWithoutChangingExistingRecords(): void
    {
        $before = (new TaskModel())->allByDate();
        $this->seed(DemoSeeder::class);
        $this->assertSame($before, (new TaskModel())->allByDate());
        $this->assertSame(1, (new UserModel())->countAllResults());
    }

    public function testWelcomeShowsOnlyTodayInPhilippineTime(): void
    {
        $result = $this->get('/');
        $result->assertStatus(200);
        $result->assertSee('Sat, Oct 10, 2026');
        foreach ((new TaskModel())->allByDate() as $task) {
            if ($task['task_date'] === '2026-10-10') {
                $result->assertSee($task['title'], 'tbody');
            } else {
                $result->assertDontSee($task['title'], 'tbody');
            }
        }
        $this->assertSame(4, substr_count($result->getBody(), 'class="task-id"'));
    }

    public function testWelcomeIncludesCompletedTasksScheduledToday(): void
    {
        $this->db->table('tasks')->where('id', 3)->update(['status' => 'completed']);
        $result = $this->get('/');
        $result->assertStatus(200);
        $result->assertSee('Finish my web systems assignment', 'tbody');
        $result->assertSee('Completed', 'tbody');
    }

    public function testFullListIncludesEveryTaskInDateOrder(): void
    {
        // Insert out of order to prove the list orders by date, not insertion ID.
        $this->db->table('tasks')->insert([
            'title' => 'An earlier task inserted last',
            'status' => 'pending',
            'task_date' => '2026-10-08',
            'created_at' => '2026-10-10 00:30:00',
        ]);
        $result = $this->get('/tasks');
        $result->assertStatus(200);
        $lastPosition = -1;
        foreach ((new TaskModel())->allByDate() as $task) {
            $result->assertSee($task['title'], 'tbody');
            $position = strpos($result->getBody(), $task['title']);
            $this->assertGreaterThan($lastPosition, $position);
            $lastPosition = $position;
        }
        $this->assertSame(9, substr_count($result->getBody(), 'class="task-id"'));
    }

    public function testProfileDisplaysTheDemoRecord(): void
    {
        $result = $this->get('/profile');
        $result->assertStatus(200);
        foreach (['Lord Zyon Ronquillo', 'zyon_dev', 'zyon.ronquillo@example.com'] as $value) {
            $result->assertSee($value);
        }
    }

    public function testAboutIdentifiesTheDeveloperWithoutNeedingRecords(): void
    {
        $this->db->table('tasks')->emptyTable();
        $this->db->table('users')->emptyTable();
        $result = $this->get('/about');
        $result->assertStatus(200);
        $result->assertSee('Zyon Ronquillo', '#developer-title');
        $result->assertSee('Technical Summative Assessment 1');
    }

    public function testWelcomeHandlesAnEmptyDay(): void
    {
        Time::setTestNow('2026-10-15 09:00:00', 'Asia/Manila');
        $result = $this->get('/');
        $result->assertStatus(200);
        $result->assertSee('No tasks for today');
        $result->assertDontSeeElement('tbody');
    }

    public function testEmptyTaskListAndMissingProfileAreHandled(): void
    {
        $this->db->table('tasks')->emptyTable();
        $this->db->table('users')->emptyTable();
        $this->get('/tasks')->assertSee('No tasks found');
        $this->get('/profile')->assertSee('No profile yet');
    }

    public function testDatabaseContentIsEscapedInTheViews(): void
    {
        $this->db->table('tasks')->where('id', 3)->update(['title' => '<script>alert("test")</script>']);
        $this->db->table('users')->where('id', 1)->update(['full_name' => '<img src=x onerror=alert(1)>']);
        $taskPage = $this->get('/');
        $taskPage->assertStatus(200);
        $this->assertStringContainsString('&lt;script&gt;', $taskPage->getBody());
        $this->assertStringNotContainsString('<script>alert(', $taskPage->getBody());
        $profilePage = $this->get('/profile');
        $profilePage->assertStatus(200);
        $this->assertStringContainsString('&lt;img', $profilePage->getBody());
        $this->assertStringNotContainsString('<img src=x', $profilePage->getBody());
    }
}
