<?php

namespace Jeybin\Networkintl\Console;

use Illuminate\Console\Command;

class InstallNgeniusPackage extends Command {

    protected $signature = 'ngenius:install';

    protected $description = 'Install the ngenius helper packages';

    public function handle() {
        $this->info('Installing ngenius helper...');

        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "Jeybin\Networkintl\Providers\NgeniusServiceProvider",
            '--tag' => "ngenius",
            '--force'=>true
        ]);

        $this->info('Installed ngenius');
    }
}