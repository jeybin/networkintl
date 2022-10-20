<?php

namespace Jeybin\Networkintl\App\Console;

use Illuminate\Console\Command;

class PublishNgeniusWebhooks extends Command {

    protected $signature = 'ngenius:ngenius-webhooks';

    protected $description = 'Copy webhook jobs to laravel';

    public function handle() {
        $this->info('Copying webhook files');

        $this->call('vendor:publish', ['--tag' => "ngenius-webhooks"]);

        $this->info('Webhook files copied');
    }
}