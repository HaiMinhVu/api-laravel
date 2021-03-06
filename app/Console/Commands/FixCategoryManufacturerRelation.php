<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductCategory;

class FixCategoryManufacturerRelation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:category-relations {--id= : Product Category ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $categoryId;
    private $count;

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
        $this->count = 0;
        if($categoryId = $this->option('id')) {
            $this->handleSingleCategory($categoryId);
        } else {
            $this->handleParentCategories();
        }
        $this->info("Updated {$this->count} Categories");
    }

    protected function handleParentCategories()
    {
        ProductCategory::isParent()->select('id')->get()->map(function($parentCategory){
            $this->handleSingleCategory($parentCategory->id);
        });
    }

    protected function handleSingleCategory($categoryId)
    {
        $category = ProductCategory::find($categoryId);
        if($category) {
            $manufacturerId = $category->manufacture;
            $this->recursivelyAssignManufacturer($category, $manufacturerId);
        }
    }

    protected function recursivelyAssignManufacturer(ProductCategory $category, int $manufacturerId)
    {
        if($category->has('subCategories')) {
            $category->subCategories->map(function($subCategory) use ($manufacturerId){
                if($subCategory->manufacture != $manufacturerId) {
                    $this->info("Updating category - {$subCategory->label}");
                    $this->count++;
                    $subCategory->update(['manufacture' => $manufacturerId]);
                }
                $this->recursivelyAssignManufacturer($subCategory, $manufacturerId);
            });
        }
    }
}
