<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\IncidentReport;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(
        IncidentReport $report,
        User $user,
        string $message,
        string $type
    ) {

        if (!config('services.iprog.enabled')) {

            try {

                SmsLog::create([
                    'report_id' =>
                        $report->id,

                    'user_id' =>
                        $user->id,

                    'contact_number' =>
                        $user->contact_number,

                    'message' =>
                        $message,

                    'type' =>
                        $type,

                    'status' =>
                        'DISABLED',

                    'sent_at' =>
                        null,
                ]);

            } catch (\Throwable $e) {

                Log::error(
                    'Unable to create disabled SMS log.',
                    [
                        'error' =>
                            $e->getMessage()
                    ]
                );

            }

            Log::info(
                'SMS notification disabled.'
            );

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Get user's contact number
        |--------------------------------------------------------------------------
        */

        $phoneNumber =
            $this->formatPhilippineNumber(
                $user->contact_number
            );


        try {

            $response = Http::timeout(15)->post(

                config('services.iprog.url'),

                [
                    'api_token' =>
                        config('services.iprog.token'),

                    'phone_number' =>
                        $phoneNumber,

                    'message' =>
                        $message,
                ]

            );

            if ($response->successful()) {

                try {

                    SmsLog::create([
                        'report_id' =>
                            $report->id,

                        'user_id' =>
                            $user->id,

                        'contact_number' =>
                            $phoneNumber,

                        'message' =>
                            $message,

                        'type' =>
                            $type,

                        'status' =>
                            'SENT',

                        'sent_at' =>
                            now(),
                    ]);

                } catch (\Throwable $e) {

                    Log::error(
                        'SMS sent but SMS log could not be created.',
                        [
                            'error' =>
                                $e->getMessage()
                        ]
                    );

                }

                return true;
            }

            try {

                SmsLog::create([
                    'report_id' =>
                        $report->id,

                    'user_id' =>
                        $user->id,

                    'contact_number' =>
                        $phoneNumber,

                    'message' =>
                        $message,

                    'type' =>
                        $type,

                    'status' =>
                        'FAILED',

                    'sent_at' =>
                        null,
                ]);

            } catch (\Throwable $e) {

                Log::error(
                    'Unable to create failed SMS log.',
                    [
                        'error' =>
                            $e->getMessage()
                    ]
                );

            }


            Log::error(
                'IPROG SMS failed',
                [
                    'status' =>
                        $response->status(),

                    'response' =>
                        $response->body(),
                ]
            );

            return false;


        } catch (\Throwable $e) {

            try {

                SmsLog::create([
                    'report_id' =>
                        $report->id,

                    'user_id' =>
                        $user->id,

                    'contact_number' =>
                        $phoneNumber,

                    'message' =>
                        $message,

                    'type' =>
                        $type,

                    'status' =>
                        'FAILED',

                    'sent_at' =>
                        null,
                ]);

            } catch (\Throwable $logException) {

                Log::error(
                    'Unable to create SMS failure log.',
                    [
                        'error' =>
                            $logException->getMessage()
                    ]
                );

            }


            Log::error(
                'IPROG SMS exception',
                [
                    'error' =>
                        $e->getMessage(),
                ]
            );

            return false;
        }
    }


    private function formatPhilippineNumber(
        string $phoneNumber
    ): string {

        $phoneNumber =
            preg_replace(
                '/[^0-9+]/',
                '',
                $phoneNumber
            );


        if (
            str_starts_with(
                $phoneNumber,
                '09'
            )
        ) {

            return '63' .
                substr(
                    $phoneNumber,
                    1
                );
        }


        if (
            str_starts_with(
                $phoneNumber,
                '+63'
            )
        ) {

            return substr(
                $phoneNumber,
                1
            );
        }


        if (
            str_starts_with(
                $phoneNumber,
                '63'
            )
        ) {

            return $phoneNumber;
        }


        return $phoneNumber;
    }
}