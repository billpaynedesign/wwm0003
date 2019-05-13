<?php namespace App\Console\Commands;

use App\Category;
use Illuminate\Console\Command;

class TitleCaseCategoryNames extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'category:title-case';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all category names to title case.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $categories = Category::withTrashed()->get();
        $bar = $this->output->createProgressBar($categories->count());

        $this->line('Start - Renaming categories');
        foreach ($categories as $category) {
            $category->name = mb_convert_case($category->name, MB_CASE_TITLE, 'UTF-8');
            $category->save();
            $bar->advance();
        }
        $bar->finish();
        $this->line('');
        $this->line('Done - Renaming categories');
    }

}
