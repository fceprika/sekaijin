<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance {action : up/down/status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle maintenance mode on/off or check status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'down':
                $this->enableMaintenanceMode();
                break;

            case 'up':
                $this->disableMaintenanceMode();
                break;

            case 'status':
                $this->showMaintenanceStatus();
                break;

            default:
                $this->error('Invalid action. Use: up, down, or status');

                return 1;
        }

        return 0;
    }

    private function enableMaintenanceMode()
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        if (strpos($envContent, 'MAINTENANCE_MODE=') !== false) {
            $envContent = preg_replace('/MAINTENANCE_MODE=.*/', 'MAINTENANCE_MODE=true', $envContent);
        } else {
            $envContent .= "\nMAINTENANCE_MODE=true\n";
        }

        file_put_contents($envPath, $envContent);

        $this->info('🔧 Maintenance mode enabled');
        $this->line('Users will see the maintenance page');
        $this->line('Admins can still access the site');
    }

    private function disableMaintenanceMode()
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        if (strpos($envContent, 'MAINTENANCE_MODE=') !== false) {
            $envContent = preg_replace('/MAINTENANCE_MODE=.*/', 'MAINTENANCE_MODE=false', $envContent);
        } else {
            $envContent .= "\nMAINTENANCE_MODE=false\n";
        }

        file_put_contents($envPath, $envContent);

        $this->info('✅ Maintenance mode disabled');
        $this->line('Site is now accessible to all users');
    }

    private function showMaintenanceStatus()
    {
        $maintenanceMode = config('app.maintenance_mode', false);

        if ($maintenanceMode) {
            $this->warn('🔧 Maintenance mode is ENABLED');
            $this->line('Users see the maintenance page');
            $this->line('Use: php artisan maintenance up');
        } else {
            $this->info('✅ Maintenance mode is DISABLED');
            $this->line('Site is accessible to all users');
            $this->line('Use: php artisan maintenance down');
        }
    }
}
