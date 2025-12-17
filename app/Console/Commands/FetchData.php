<?php

namespace App\Console\Commands;

use App\Models\Catalog\Attribute;
use App\Models\CMS\Color;
use Illuminate\Support\Str;
use App\Models\Catalog\Brand;
use Illuminate\Console\Command;
use App\Models\Catalog\Category;
use App\Models\CMS\Packaging;
use App\Models\CMS\Tag;

class FetchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from an external API and store it in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $response = @file_get_contents('products.json');
        if ($response === false) {
            $this->error('Failed to fetch data from the API.');
            return 1;
        }
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON response from the API.');
            return 1;
        }

        $ids = [];
        foreach ($data as $key => $p) {

            $brandId        = null;
            $colorId        = null;
            $categoryIds    = [];
            $tagIds         = [];

            // dd($p);


            if ($p['default_code'] == 'ADF Black-3XL') {
                $ids[] = $p;
            }



            if (!empty($p['product_template_attribute_value_ids']) && count($p['product_template_attribute_value_ids']) > 1) {
                dd($p['product_template_attribute_value_ids'], $p);
            }
            // if (!empty($p['product_template_attribute_value_ids'])) {
            //     $this->storeAttributes($p['product_template_attribute_value_ids']);
            // }



            /**
             * Step 1: Store Packaging Related Data
             */
            if (!empty($p['specifications']['Packing'])) {
                $this->storePackaging($p['specifications']['Packing']);
            }

            /**
             * Step 2: Store Brand Related Data
             * @return int $brandId
             */
            if (!empty($p['brand_id']) && is_array($p['brand_id'])) {
                $brandId = $this->storeBrand($p['brand_id']);
            }

            /**
             * Step 3: Store Color Related Data
             * @return int $colorId
             */
            if (!empty($p['color'])) {
                $colorId = $this->storeColor($p['color']);
            }

            /**
             * Step 4: Store Tag Related Data
             * @return array $tagIds
             */
            if (!empty($p['product_template_tags'])) {
                $tagIds = $this->storeTags($p['product_template_tags']);
            }


            /**
             * Step 5: Store Category Related Data
             * @return array $categoryIds
             */
            $apiCategories = $p['public_categ_ids'];
            if (!empty($apiCategories) && is_array($apiCategories)) {
                $categoryIds = $this->storeCategories($apiCategories);
            }
        }

        dd($ids);


        $this->info('Data fetched and stored successfully.');
        return 0;
    }

    public function storeBrand($apiBrand)
    {
        $apiBrandId = $apiBrand[0];
        $apiBrandName = $apiBrand[1];

        $brand = Brand::firstOrNew(['reference_id' => $apiBrandId]);

        if (!$brand->exists) {
            $brand->name = $apiBrandName;
            $brand->slug = Str::slug($apiBrandName);
            $brand->is_active = 1;
        }

        $brand->reference_name = $apiBrandName;
        $brand->save();

        return $brand->id;
    }

    public function storeCategories($apiCategories)
    {
        $categoryIds = [];
        foreach ($apiCategories as $apiCategory) {
            $apiCategoryId      = $apiCategory['id'];
            $apiCategoryName    = $apiCategory['name'];

            $category = Category::firstOrNew(['reference_id' => $apiCategoryId]);

            if (!$category->exists) {
                $category->slug = Str::slug($apiCategoryName);
                $category->is_visible = 1;
            }

            $category->save();


            $translation = $category->translations()->firstOrNew(['locale' => 'en-ae'], ['reference_name' => $apiCategoryName]);

            if (!$translation->exists) {
                $translation->name = $apiCategoryName;
                $translation->save();
            }

            $translation->save();

            $categoryIds[$category->id] = $category->id; // Unique category IDs
        }

        return $categoryIds;
    }

    function storeColor($apiColor)
    {
        $color = Color::firstOrNew(['name' => $apiColor]);

        if (!$color->exists) {
            $color->is_active = 1;
        }

        $color->save();

        return $color->id;
    }

    function storeTags($apiTags)
    {
        $tagIds = [];
        foreach ($apiTags as $apiTag) {
            $apiTagId   = $apiTag['id'];
            $apiTagName = $apiTag['name'];

            $tag = Tag::firstOrNew(['reference_id' => $apiTagId], ['reference_name' => $apiTagName]);

            if (!$tag->exists) {
                $tag->name = $apiTagName;
                $tag->is_active = 1;
            }

            $tag->save();

            $tagIds[$tag->id] = $tag->id; // Unique tag IDs
        }

        return $tagIds;
    }

    function storePackaging($apiPackaging)
    {
        $packagingNames = collect($apiPackaging)
            ->flatMap(fn($fieldsArray) => array_keys($fieldsArray))
            ->unique()
            ->values();

        $packagingNames->each(function ($packagingName) {
            Packaging::firstOrCreate(
                ['reference_name' => $packagingName],
                [
                    'name' => $packagingName,
                    'description' => null,
                    'is_active' => 1,
                ]
            );
        });
    }

    function storeAttributes($apiAttributes)
    {

        foreach ($apiAttributes as $apiAttribute) {
            $referenceId        = $apiAttribute['id'];
            $referenceName      = $apiAttribute['display_name'];

            $attribute = Attribute::firstOrCreate(['reference_id' => $referenceId], ['reference_name' => $referenceName, 'name' => $referenceName]);
        }
    }
}
