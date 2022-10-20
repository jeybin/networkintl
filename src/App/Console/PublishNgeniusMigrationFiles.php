<?php

namespace Jeybin\Networkintl\App\Console;

use Illuminate\Console\Command;

class PublishNgeniusMigrationFiles extends Command {

    protected $signature = 'ngenius:migrate';

    protected $description = 'Copy migration files of ngenius package';

    public function handle() {
        $this->info('Copying migration files');

        $this->call('vendor:publish', ['--tag' => "ngenius-migrations"]);

        $this->call('migrate',['--path'=>'database/migrations/ngenius']);

        $this->info('Installed ngenius');
    }
}