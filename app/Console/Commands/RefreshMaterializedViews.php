<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshMaterializedViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:refresh_materialized_views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Postgres Materialized Views';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $matviews = db_get_simple_list("SELECT oid::regclass::text FROM pg_class WHERE  relkind = 'm'");

        foreach ($matviews as $matview)
        {
            \DB::unprepared('REFRESH MATERIALIZED VIEW CONCURRENTLY ' . $matview . ';');
        }

        return true;
    }
}
