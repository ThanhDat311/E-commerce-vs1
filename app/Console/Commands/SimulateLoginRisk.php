<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SimulateLoginRisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:simulate-login {path : Path to the simulation JSON file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate a login attempt and evaluate risk using AI';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path = $this->argument('path');

        if (! file_exists($path)) {
            $this->error("File not found: {$path}");

            return 1;
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true);
        if (! $data) {
            $this->error('Invalid JSON file.');

            return 1;
        }

        $userId = $data['user_id'] ?? null;
        $user = \App\Models\User::find($userId);

        if (! $user) {
            $this->error("User with ID {$userId} not found.");

            return 1;
        }

        $this->info("Simulating login for user: {$user->email} ({$user->name})");

        // --- NEW: Seed failed attempts based on JSON ---
        $failedAttempts = $data['recent_failed_attempts'] ?? 0;
        if ($failedAttempts > 0) {
            $this->info("Seeding {$failedAttempts} failed login attempts to trigger AI risk...");
            $riskEngine = app(\App\Services\Auth\RiskEngineService::class);
            for ($i = 0; $i < $failedAttempts; $i++) {
                // Score in 0.0–1.0 scale (matching what the AI microservice returns)
                $riskScore = rand(30, 70) / 100.0;
                \App\Models\AuthLog::create([
                    'user_id' => $user->id,
                    'session_id' => 'simulated-failed-session',
                    'ip_address' => $data['ip_address'],
                    'user_agent' => $data['user_agent'],
                    'risk_score' => $riskScore,
                    'risk_level' => $riskEngine->calculateRiskLevelPublic($riskScore),
                    'auth_decision' => $riskEngine->calculateDecisionPublic($riskScore),
                    'is_successful' => false,
                    'created_at' => now()->subMinutes(rand(1, 20)),
                ]);
            }
        }

        // Mock a request
        $request = new \Illuminate\Http\Request;

        $request->setMethod('POST');
        // Set the request IP and User Agent from the JSON data
        $request->server->set('REMOTE_ADDR', $data['ip_address']);
        $request->headers->set('User-Agent', $data['user_agent']);

        // Set up the risk engine
        $riskEngine = app(\App\Services\Auth\RiskEngineService::class);

        $this->output->writeln('Evaluating risk via AI microservice...');

        try {
            // This will call the AI microservice and create an AuthLog entry
            $mfaRequired = $riskEngine->evaluate($user, $request);

            if ($mfaRequired) {
                $this->warn('Result: MFA Required (High Risk detected)');
            } else {
                $this->info('Result: Login Allowed (Safe or Trusted)');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error("Result: Login Blocked! Message: {$e->getMessage()}");
        } catch (\Throwable $e) {
            $this->error("An error occurred: {$e->getMessage()}");
            $this->output->writeln($e->getTraceAsString(), \Symfony\Component\Console\Output\OutputInterface::VERBOSITY_VERBOSE);

            return 1;
        }

        $this->info('Success! Now you can refresh the AI Risk Dashboard to see the new log entry.');

        return 0;
    }
}
