<?php

use Illuminate\Database\Seeder;

class MediaFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //general
        DB::table('media_files')->insert([
            ['file_name' => 'avatar', 'file_path' => 'demo/general/avatar.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'avatar-2', 'file_path' => 'demo/general/avatar-2.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'avatar-3', 'file_path' => 'demo/general/avatar-3.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'ico_adventurous', 'file_path' => 'demo/general/ico_adventurous.png', 'file_type' => 'image/png', 'file_extension' => 'png'],
            ['file_name' => 'ico_localguide', 'file_path' => 'demo/general/ico_localguide.png', 'file_type' => 'image/png', 'file_extension' => 'png'],
            ['file_name' => 'ico_maps', 'file_path' => 'demo/general/ico_maps.png', 'file_type' => 'image/png', 'file_extension' => 'png'],
            ['file_name' => 'ico_paymethod', 'file_path' => 'demo/general/ico_paymethod.png', 'file_type' => 'image/png', 'file_extension' => 'png'],
            ['file_name' => 'logo', 'file_path' => 'demo/general/logo.svg', 'file_type' => 'image/svg+xml', 'file_extension' => 'svg'],
            ['file_name' => 'bg_contact', 'file_path' => 'demo/general/bg-contact.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'favicon', 'file_path' => 'demo/general/favicon.png', 'file_type' => 'image/png', 'file_extension' => 'png'],
            ['file_name' => 'thumb-vendor-register', 'file_path' => 'demo/general/thumb-vendor-register.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'bg-video-vendor-register1', 'file_path' => 'demo/general/bg-video-vendor-register1.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'ico_chat_1', 'file_path' => 'demo/general/ico_chat_1.svg', 'file_type' => 'image/svg', 'file_extension' => 'svg'],
            ['file_name' => 'ico_friendship_1', 'file_path' => 'demo/general/ico_friendship_1.svg', 'file_type' => 'image/svg', 'file_extension' => 'svg'],
            ['file_name' => 'ico_piggy-bank_1', 'file_path' => 'demo/general/ico_piggy-bank_1.svg', 'file_type' => 'image/svg', 'file_extension' => 'svg'],
        ]);



        //Tour
        DB::table('media_files')->insert([
            ['file_name' => 'banner-search', 'file_path' => 'demo/tour/banner-search.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
        ]);
        for ($i=1 ; $i <= 16 ; $i++){
            DB::table('media_files')->insert([
                ['file_name' => 'tour-'.$i, 'file_path' => 'demo/tour/tour-'.$i.'.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ]);
        }
        for ($i=1 ; $i <= 7 ; $i++){
            DB::table('media_files')->insert([
                ['file_name' => 'gallery-'.$i, 'file_path' => 'demo/tour/gallery-'.$i.'.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ]);
        }
        for ($i=1 ; $i <= 17 ; $i++){
            DB::table('media_files')->insert([
                ['file_name' => 'banner-tour-'.$i, 'file_path' => 'demo/tour/banner-detail/banner-tour-'.$i.'.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ]);
        }

        //Space
        DB::table('media_files')->insert([
            ['file_name' => 'banner-search-space', 'file_path' => 'demo/space/banner-search-space.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'banner-search-space-2', 'file_path' => 'demo/space/banner-search-space-2.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
        ]);
        for ($i=1 ; $i <= 13 ; $i++){
            DB::table('media_files')->insert([
                ['file_name' => 'space-'.$i, 'file_path' => 'demo/space/space-'.$i.'.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ]);
        }
        for ($i=1 ; $i <= 7 ; $i++){
            DB::table('media_files')->insert([
                ['file_name' => 'space-gallery-'.$i, 'file_path' => 'demo/space/gallery/space-gallery-'.$i.'.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ]);
        }


        for ($i=1 ; $i <= 3 ; $i++){
            DB::table('media_files')->insert([
                ['file_name' => 'space-single-'.$i, 'file_path' => 'demo/space/space-single-'.$i.'.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ]);
        }
        for ($i=1 ; $i <= 6 ; $i++){
            DB::table('media_files')->insert([
                ['file_name' => 'icon-space-box-'.$i, 'file_path' => 'demo/space/featured-box/icon-space-box-'.$i.'.png', 'file_type' => 'image/png', 'file_extension' => 'jpg'],
            ]);
        }


        //Location
        DB::table('media_files')->insert([
            ['file_name' => 'location-1', 'file_path' => 'demo/location/location-1.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'location-2', 'file_path' => 'demo/location/location-2.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'location-3', 'file_path' => 'demo/location/location-3.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'location-4', 'file_path' => 'demo/location/location-4.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'location-5', 'file_path' => 'demo/location/location-5.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'banner-location-1', 'file_path' => 'demo/location/banner-detail/banner-location-1.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'trip-idea-1', 'file_path' => 'demo/location/trip-idea/trip-idea-1.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'trip-idea-2', 'file_path' => 'demo/location/trip-idea/trip-idea-2.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],

        ]);

        //News
        DB::table('media_files')->insert([
            ['file_name' => 'news-1', 'file_path' => 'demo/news/news-1.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'news-2', 'file_path' => 'demo/news/news-2.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'news-3', 'file_path' => 'demo/news/news-3.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'news-4', 'file_path' => 'demo/news/news-4.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'news-5', 'file_path' => 'demo/news/news-5.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'news-6', 'file_path' => 'demo/news/news-6.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'news-7', 'file_path' => 'demo/news/news-7.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
            ['file_name' => 'news-banner', 'file_path' => 'demo/news/news-banner.jpg', 'file_type' => 'image/jpeg', 'file_extension' => 'jpg'],
        ]);
    }
}
