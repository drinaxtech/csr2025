<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class MailSettingsController extends Controller {
    public function index()
    {
        if (!Auth::id()) {
            abort(404, 'Not found');
        }

        return Inertia::render('Admin/MailSettings', [
             'mailConfig' => [
            'mail_driver' => env('MAIL_MAILER'),
            'mail_host' => env('MAIL_HOST'),
            'mail_port' => env('MAIL_PORT'),
            'mail_username' => env('MAIL_USERNAME'),
            'mail_password' => env('MAIL_PASSWORD'),
            'mail_encryption' => env('MAIL_ENCRYPTION'),
            'mail_from_address' => env('MAIL_FROM_ADDRESS')
             ]
        ]);
    }

    public function update(Request $request)
    {
        if (!Auth::id()) {
            abort(404, 'Not found');
        }

        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|email',
            'mail_password' => 'required|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $this->setEnvironmentValue([
            'MAIL_MAILER' => $request->input('mail_driver'),
            'MAIL_HOST' => $request->input('mail_host'),
            'MAIL_PORT' => $request->input('mail_port'),
            'MAIL_USERNAME' => $request->input('mail_username'),
            'MAIL_PASSWORD' => $request->input('mail_password'),
            'MAIL_ENCRYPTION' => $request->input('mail_encryption'),
            'MAIL_FROM_ADDRESS' => $request->input('mail_from_address'),
        ]);

        // Clear config cache to apply changes immediately
        Artisan::call('config:clear');

        return back()->with('success', 'Mail settings updated successfully!');
    }

    protected function setEnvironmentValue(array $values)
{

    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);
    Log::info("Before update: " . $str); // Add this line

    foreach ($values as $envKey => $envValue) {
        $keyToReplace = strtoupper($envKey);
        $str = preg_replace("/^{$keyToReplace}=(.*)$/m", "{$keyToReplace}={$envValue}", $str);
    }
    file_put_contents($envFile, $str);
    Log::info("After update: " . file_get_contents($envFile)); // Add this line
}
}