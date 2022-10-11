<?php

namespace Jeybin\Networkintl\App\Console;

use Illuminate\Console\Command;

class PublishNgeniusProviders extends Command {

    protected $signature = 'ngenius:install';

    protected $description = 'Install the ngenius helper packages';

    public function handle() {
        $this->info('Publishing ngenius provider');

        $this->call('vendor:publish', [
            '--provider' => "Jeybin\Networkintl\Providers\NgeniusServiceProvider",
            '--force'=>true
        ]);

        $this->info('Installed ngenius');
    }
}