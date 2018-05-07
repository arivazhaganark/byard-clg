<?php
use Illuminate\Database\Seeder;
 
class CategoryTableSeeder extends Seeder {
 
    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('category')->delete();
 
        $cat = array(
            ['id' => 1, 'name' => 'category1', 'description' => 'my content', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => 2, 'name' => 'category2', 'description' => 'my content', 'created_at' => new DateTime, 'updated_at' => new DateTime],
        );
 
        // Uncomment the below to run the seeder
        DB::table('category')->insert($cat);
    }
 
}