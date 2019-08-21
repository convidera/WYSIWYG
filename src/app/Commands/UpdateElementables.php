<?php

namespace App\Console\Commands;

use Convidera\WYSIWYG\MediaElement;
use Convidera\WYSIWYG\TextElement;
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
        TextElement::getTextElementables()->each(function ($model) {
            $model::all()->each(function ($entry) use ($model){
                $model::createDefaultTextKeys($entry);
            });
        });

        MediaElement::getMediaElementables()->each(function ($model) {
            $model::all()->each(function ($entry) use ($model){
                $model::createDefaultMediaKeys($entry);
            });
        });
    }
}
