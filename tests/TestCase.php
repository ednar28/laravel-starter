<?php

namespace Tests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /**
     * URL that will be used for json request.
     *
     * @var string
     */
    protected string $url;

    /**
     * String date for testing now.
     *
     * @var string
     */
    private $now = '2021-05-28 06:00:00';

    /**
     * Setup environment testing.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        Storage::fake();
        Carbon::setTestNow($this->now);
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    public function tearDown(): void
    {
        Carbon::setTestNow(); // clear mock

        parent::tearDown();
    }

    /**
     * Visit the given URI with a GET request, expecting a JSON response.
     *
     * @return \Illuminate\Testing\TestResponse
     */
    public function jsonGet(string $suffix = '', array $data = [], $headers = [])
    {
        return $this->json('get', $this->url . "/$suffix", $data, $headers);
    }

    /**
     * Visit the given URI with a POST request, expecting a JSON response.
     *
     * @param array $data
     * @param String $suffix
     * @return \Illuminate\Testing\TestResponse
     */
    public function jsonPost(array $data = [], string $suffix = '', $headers = [])
    {
        return $this->postJson($this->url . "/$suffix", $data, $headers);
    }

    /**
     * Visit the given URI with a PUT request, expecting a JSON response.
     *
     * @param array $data
     * @param String $suffix
     * @return \Illuminate\Testing\TestResponse
     */
    public function jsonPut(array $data, string $suffix = '', $headers = [])
    {
        return $this->putJson($this->url . "/$suffix", $data, $headers);
    }

    /**
     * Visit the given URI with a DELETE request, expecting a JSON response.
     *
     * @param String $suffix
     * @param array $data
     * @return \Illuminate\Testing\TestResponse
     */
    public function jsonDelete(string $suffix = '', array $data = [], $headers = [])
    {
        return $this->json('delete', $this->url . "/$suffix", $data, $headers);
    }

    /**
     * Test if notification has specified title, url and content and sent to specific user.
     *
     * @param  \App\Models\User  $user
     * @param  string|closure  $title
     * @param  string  $url
     * @param  string  $content
     * @param  bool  $includeFirebase
     * @return void
     */
    public function assertNotificationSent(
        User $user,
        string $notificationClass,
        string $title,
        string $url,
        string $content,
        bool $includeFirebase = false
    ) {
        Notification::assertSentTo($user, $notificationClass, function ($notification) use ($user, $title, $url, $content, $includeFirebase) {
            $dbNotification = $notification->toDatabase($user);

            $this->assertEquals($dbNotification['title'], $title);
            $this->assertEquals($dbNotification['url'], $url);
            $this->assertEquals($dbNotification['content'], $content);

            if ($includeFirebase) {
                $dbNotification = $notification->toFirebase($user);
                $this->assertEquals($dbNotification['title'], $title);
                $this->assertEquals($dbNotification['url'], $url);
                $this->assertEquals($dbNotification['content'], $content);
            }

            return true;
        });
    }
}
