<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\LLM\LLMTriageService;
use App\Models\Ticket;
use App\Models\User;
use Mockery;

class LLMTriageServiceTest extends TestCase
{
    public function test_suggest_returns_expected_structure(): void
    {
        $ticket = new Ticket(['title' => 'Test Ticket']);

        $userMock = User::factory()->make();
        $userQuery = Mockery::mock('alias:App\Models\User');
        $userQuery->shouldReceive('inRandomOrder->first')->andReturn($userMock);

        $service = new LLMTriageService();
        $result = $service->suggest($ticket);

        $this->assertArrayHasKey('priority', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('tags', $result);
        $this->assertArrayHasKey('assignee_id', $result);
        $this->assertArrayHasKey('reasoning', $result);

        $this->assertContains($result['priority'], array_column(\App\Enums\TicketPriority::cases(), 'value'));
        $this->assertContains($result['status'], array_column(\App\Enums\TicketStatus::cases(), 'value'));
        $this->assertNotEmpty($result['tags']);
        $this->assertLessThanOrEqual(3, count($result['tags']));
        $this->assertStringContainsString($ticket->title, $result['reasoning']);
        $this->assertEquals($userMock->id, $result['assignee_id']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
