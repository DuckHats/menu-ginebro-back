<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Models\Policy;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Config;

class AuthRepository
{
    protected $errorRepository;

    public function __construct(ErrorRepository $errorRepository)
    {
        $this->errorRepository = $errorRepository;
    }

    /**
     * Create basic user settings based on the configuration file.
     *
     * @param  int  $userId
     * @return void
     */
    public function createBasicSettings($userId)
    {
        try {
            $settings = Config::get('user.register_settings');

            foreach ($settings as $key => $value) {
                UserSetting::create([
                    'user_id' => $userId,
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        } catch (\Throwable $e) {
            $this->errorRepository->createError(
                $e->getMessage(),
                $e->getCode(),
                $e->getTraceAsString(),
                $userId,
                session()->getId(),
                now()->toDateTimeString()
            );
        }
    }

    /**
     * Set welcome notification.
     *
     * @param  int  $userId
     * @return void
     */
    public function setWelcomeNotification($userId)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => 'info',
                'data' => 'Welcome to our platform!',
                'read_at' => null,
            ]);
        } catch (\Throwable $e) {
            $this->errorRepository->createError(
                $e->getMessage(),
                $e->getCode(),
                $e->getTraceAsString(),
                $userId,
                session()->getId(),
                now()->toDateTimeString()
            );
        }
    }

    /**
     * Set basic policies.
     *
     * @param  int  $userId
     * @return void
     */
    public function setBasicPolicies($userId)
    {
        try {
            Policy::create([
                'user_id' => $userId,
                'accept_newsletter' => 0,
                'accept_privacy_policy' => 1,
                'accept_terms_of_use' => 1,
            ]);
        } catch (\Throwable $e) {
            $this->errorRepository->createError(
                $e->getMessage(),
                $e->getCode(),
                $e->getTraceAsString(),
                $userId,
                session()->getId(),
                now()->toDateTimeString()
            );
        }
    }

    /**
     * Set complete policies.
     *
     * @param  int  $userId
     * @return void
     */
    public function setCompletePolicies($userId)
    {
        try {
            Policy::create([
                'user_id' => $userId,
                'accept_newsletter' => 1,
                'accept_privacy_policy' => 1,
                'accept_terms_of_use' => 1,
            ]);
        } catch (\Throwable $e) {
            $this->errorRepository->createError(
                $e->getMessage(),
                $e->getCode(),
                $e->getTraceAsString(),
                $userId,
                session()->getId(),
                now()->toDateTimeString()
            );
        }
    }

    /**
     * Set admin role.
     *
     * @param  int  $userId
     * @return void
     */
    public function setAdminRole($userId)
    {
        try {
            UserSetting::create([
                'user_id' => $userId,
                'key' => 'role',
                'value' => 'admin',
            ]);
        } catch (\Throwable $e) {
            $this->errorRepository->createError(
                $e->getMessage(),
                $e->getCode(),
                $e->getTraceAsString(),
                $userId,
                session()->getId(),
                now()->toDateTimeString()
            );
        }
    }
}
