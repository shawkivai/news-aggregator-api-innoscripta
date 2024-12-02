<?php

namespace Tests\Feature\Http\Controllers\V1;

use App\DataTransferObjects\ServiceResponseDTO;
use App\Domain\V1\User\Services\UserPreferenceService as ServicesUserPreferenceService;
use App\Enums\V1\HttpStatus;
use App\Http\Controllers\V1\UserPreferenceController;
use Tests\TestCase;

class UserPreferenceControllerTest extends TestCase
{
    protected $userPreferenceController;

    protected $userPreferenceService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userPreferenceController = new UserPreferenceController(
            $this->userPreferenceService = $this->createMock(ServicesUserPreferenceService::class),
        );
    }

    public function test_get_user_preferences()
    {
        $this->userPreferenceService->expects($this->once())
            ->method('getUserPreferences')
            ->willReturn(new ServiceResponseDTO([
                'status' => HttpStatus::SUCCESS_RESPONSE,
                'httpStatusCode' => HttpStatus::SUCCESS,
                'responseMessage' => 'User preferences fetched successfully',
                'response' => [],
                'headers' => [],
            ]));

        $response = $this->userPreferenceController->index();
        $this->assertEquals(HttpStatus::SUCCESS, $response->getStatusCode());
    }
}
