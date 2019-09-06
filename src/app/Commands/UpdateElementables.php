<?php

namespace App\Console\Commands;

use Convidera\WYSIWYG\Traits\ProvidesDefaultMediaElements;
use Convidera\WYSIWYG\Traits\ProvidesDefaultTextElements;
use Illuminate\Console\Command;

class UpdateElementables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wysiwyg:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates all default textElements and mediaElements for all Models';

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
     * @return mixed
     */
    public function handle()
    {
        $loader = (include "vendor/autoload.php");
        
        foreach ($loader->getClassMap() as $class => $path) {
            if (strpos(realpath($path), 'vendor') !== false) {
                continue;
            }
            if (is_subclass_of($class, ProvidesDefaultTextElements::class)) {
                $class::all()->each(function ($entry) use ($class){
                    $class::createDefaultTextKeys($entry);
                });
            }

            if (is_subclass_of($class, ProvidesDefaultMediaElements::class)) {
                $class::all()->each(function ($entry) use ($class) {
                    $class::createDefaultMediaKeys($entry);
                });
            }
        }
    }
}
