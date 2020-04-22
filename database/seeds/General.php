<?php

    use Illuminate\Support\Str;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;
    use Modules\Media\Models\MediaFile;

    class General extends Seeder
    {
        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {

            //Setting header,footer
            DB::table('core_menus')->insert([
                'name'        => 'Main Menu',
                'items'       => '[{"name":"Home","url":"/","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_fFqdG","children":[{"name":"Home Tour","url":"/","item_model":"custom","_open":false,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_zCpBW","children":[]},{"name":"Home Space","url":"/page/space","item_model":"custom","_open":false,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_UafsE","children":[]}]},{"name":"Tours","url":"/tour","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_pg7su","children":[{"name":"Tour List","url":"/tour","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_rFcsy","children":[]},{"name":"Tour Map","url":"/tour?_layout=map","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_vCYrZ","children":[]},{"name":"Tour Detail","url":"/tour/paris-vacation-travel","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_V8rhO","children":[]}]},{"name":"Space","url":"/space","item_model":"custom","_open":false,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_r5RWH","children":[{"name":"Space List","url":"/space","item_model":"custom","_open":false,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_nq2pT","children":[]},{"name":"Space Detail","url":"/space/stay-greenwich-village","item_model":"custom","_open":false,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_o4HzK","children":[]}]},{"name":"Pages","url":"#","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_SvHEN","children":[{"name":"News List","url":"/news","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_GEDO1","children":[]},{"name":"News Detail","url":"/news/morning-in-the-northern-sea","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_Pk0gB","children":[]},{"name":"Location Detail","url":"/location/paris","item_model":"custom","_open":false,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_oagHU","children":[]},{"name":"Become a vendor","url":"/page/become-a-vendor","item_model":"custom","_open":true,"open":true,"active":false,"class":" ","innerClass":"","_id":"tree_2_node_3XH23","_treeNodePropertiesCompleted":true,"children":[]}]},{"name":"Contact","url":"/contact","item_model":"custom","_open":false,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_myrSp","children":[]}]',
                'create_user' => '1',
                'created_at'  => date("Y-m-d H:i:s")
            ]);
            DB::table('core_menu_translations')->insert([
                'origin_id'   => '1',
                'locale'      => 'ja',
                'items'       => '[{"name":"ホーム","url":"/","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_EFjp3","children":[{"name":"ホーム ツアー","url":"/ja","item_model":"custom","_open":true,"active":false,"class":"   ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_mt8Ru","children":[]},{"name":"ホームスペース","url":"/ja/page/space","item_model":"custom","_open":false,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_bRGcI","children":[]}]},{"name":"ツアー","url":"/ja/tour","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_eFLDB","children":[{"name":"ツアーリスト","url":"/ja/tour","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":false,"open":true,"_id":"tree_2_node_ieKVC","children":[]},{"name":"ツアーマップ","url":"/ja/tour?_layout=map","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_O513y","children":[]},{"name":"ツアー詳細","url":"/ja/tour/paris-vacation-travel","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_bcoiL","children":[]}]},{"name":"スペース","url":"/ja/space","item_model":"custom","_open":true,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_4Q5Bc","children":[{"name":"スペースリスト","url":"/ja/space","item_model":"custom","_open":true,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_NxQZJ","children":[]},{"name":"スペースの詳細","url":"/ja/space/stay-greenwich-village","item_model":"custom","_open":true,"active":false,"class":" ","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_kstt2","children":[]}]},{"name":"ニュース","url":"/ja/news","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_hSHZ2","children":[{"name":"ニュース一覧","url":"/ja/news","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_V7cTz","children":[]},{"name":"ニュース詳細","url":"/ja/news/morning-in-the-northern-sea","item_model":"custom","model_name":"Custom","is_removed":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"_open":true,"open":true,"_id":"tree_2_node_yWQ0e","children":[]}]},{"name":"接触","url":"/ja/contact","item_model":"custom","_open":true,"active":false,"class":"","innerClass":"","_treeNodePropertiesCompleted":true,"model_name":"Custom","is_removed":true,"open":true,"_id":"tree_2_node_W7YSI","children":[]}]',
                'create_user' => '1',
                'created_at'  => date("Y-m-d H:i:s")
            ]);

            DB::table('core_settings')->insert(
                [
                    [
                        'name'  => 'menu_locations',
                        'val'   => '{"primary":1}',
                        'group' => "general",
                    ],
                    [
                        'name'  => 'admin_email',
                        'val'   => 'contact@bookingcore.com',
                        'group' => "general",
                    ], [
                        'name'  => 'email_from_name',
                        'val'   => 'Booking Core',
                        'group' => "general",
                    ], [
                        'name'  => 'email_from_address',
                        'val'   => 'contact@bookingcore.com',
                        'group' => "general",
                    ],
                    [
                        'name'  => 'logo_id',
                        'val'   => MediaFile::findMediaByName("logo")->id,
                        'group' => "general",
                    ],
                    [
                        'name'  => 'site_favicon',
                        'val'   => MediaFile::findMediaByName("favicon")->id,
                        'group' => "general",
                    ],
                    [
                        'name'  => 'topbar_left_text',
                        'val'   => '<div class="socials">
    <a href="#"><i class="fa fa-facebook"></i></a>
    <a href="#"><i class="fa fa-linkedin"></i></a>
    <a href="#"><i class="fa fa-google-plus"></i></a>
</div>
<span class="line"></span>
<a href="mailto:contact@bookingcore.com">contact@bookingcore.com</a>',
                        'group' => "general",
                    ],
                    [
                        'name'  => 'footer_text_left',
                        'val'   => 'Copyright © 2019 by Booking Core',
                        'group' => "general",
                    ],
                    [
                        'name'  => 'footer_text_right',
                        'val'   => 'Booking Core',
                        'group' => "general",
                    ],
                    [
                        'name'  => 'list_widget_footer',
                        'val'   => '[{"title":"NEED HELP?","size":"3","content":"<div class=\"contact\">\r\n        <div class=\"c-title\">\r\n            Call Us\r\n        <\/div>\r\n        <div class=\"sub\">\r\n            + 00 222 44 5678\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"contact\">\r\n        <div class=\"c-title\">\r\n            Email for Us\r\n        <\/div>\r\n        <div class=\"sub\">\r\n            hello@yoursite.com\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"contact\">\r\n        <div class=\"c-title\">\r\n            Follow Us\r\n        <\/div>\r\n        <div class=\"sub\">\r\n            <a href=\"#\">\r\n                <i class=\"icofont-facebook\"><\/i>\r\n            <\/a>\r\n            <a href=\"#\">\r\n               <i class=\"icofont-twitter\"><\/i>\r\n            <\/a>\r\n            <a href=\"#\">\r\n                <i class=\"icofont-youtube-play\"><\/i>\r\n            <\/a>\r\n        <\/div>\r\n    <\/div>"},{"title":"COMPANY","size":"3","content":"<ul>\r\n    <li><a href=\"#\">About Us<\/a><\/li>\r\n    <li><a href=\"#\">Community Blog<\/a><\/li>\r\n    <li><a href=\"#\">Rewards<\/a><\/li>\r\n    <li><a href=\"#\">Work with Us<\/a><\/li>\r\n    <li><a href=\"#\">Meet the Team<\/a><\/li>\r\n<\/ul>"},{"title":"SUPPORT","size":"3","content":"<ul>\r\n    <li><a href=\"#\">Account<\/a><\/li>\r\n    <li><a href=\"#\">Legal<\/a><\/li>\r\n    <li><a href=\"#\">Contact<\/a><\/li>\r\n    <li><a href=\"#\">Affiliate Program<\/a><\/li>\r\n    <li><a href=\"#\">Privacy Policy<\/a><\/li>\r\n<\/ul>"},{"title":"SETTINGS","size":"3","content":"<ul>\r\n<li><a href=\"#\">Setting 1<\/a><\/li>\r\n<li><a href=\"#\">Setting 2<\/a><\/li>\r\n<\/ul>"}]',
                        'group' => "general",
                    ],
                    [
                        'name'  => 'list_widget_footer_ja',
                        'val'   => '[{"title":"\u52a9\u3051\u304c\u5fc5\u8981\uff1f","size":"3","content":"<div class=\"contact\">\r\n        <div class=\"c-title\">\r\n            \u304a\u96fb\u8a71\u304f\u3060\u3055\u3044\r\n        <\/div>\r\n        <div class=\"sub\">\r\n            + 00 222 44 5678\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"contact\">\r\n        <div class=\"c-title\">\r\n            \u90f5\u4fbf\u7269\r\n        <\/div>\r\n        <div class=\"sub\">\r\n            hello@yoursite.com\r\n        <\/div>\r\n    <\/div>\r\n    <div class=\"contact\">\r\n        <div class=\"c-title\">\r\n            \u30d5\u30a9\u30ed\u30fc\u3059\u308b\r\n        <\/div>\r\n        <div class=\"sub\">\r\n            <a href=\"#\">\r\n                <i class=\"icofont-facebook\"><\/i>\r\n            <\/a>\r\n            <a href=\"#\">\r\n                <i class=\"icofont-twitter\"><\/i>\r\n            <\/a>\r\n            <a href=\"#\">\r\n                <i class=\"icofont-youtube-play\"><\/i>\r\n            <\/a>\r\n        <\/div>\r\n    <\/div>"},{"title":"\u4f1a\u793e","size":"3","content":"<ul>\r\n    <li><a href=\"#\">\u7d04, \u7565<\/a><\/li>\r\n    <li><a href=\"#\">\u30b3\u30df\u30e5\u30cb\u30c6\u30a3\u30d6\u30ed\u30b0<\/a><\/li>\r\n    <li><a href=\"#\">\u5831\u916c<\/a><\/li>\r\n    <li><a href=\"#\">\u3068\u9023\u643a<\/a><\/li>\r\n    <li><a href=\"#\">\u30c1\u30fc\u30e0\u306b\u4f1a\u3046<\/a><\/li>\r\n<\/ul>"},{"title":"\u30b5\u30dd\u30fc\u30c8","size":"3","content":"<ul>\r\n    <li><a href=\"#\">\u30a2\u30ab\u30a6\u30f3\u30c8<\/a><\/li>\r\n    <li><a href=\"#\">\u6cd5\u7684<\/a><\/li>\r\n    <li><a href=\"#\">\u63a5\u89e6<\/a><\/li>\r\n    <li><a href=\"#\">\u30a2\u30d5\u30a3\u30ea\u30a8\u30a4\u30c8\u30d7\u30ed\u30b0\u30e9\u30e0<\/a><\/li>\r\n    <li><a href=\"#\">\u500b\u4eba\u60c5\u5831\u4fdd\u8b77\u65b9\u91dd<\/a><\/li>\r\n<\/ul>"},{"title":"\u8a2d\u5b9a","size":"3","content":"<ul>\r\n<li><a href=\"#\">\u8a2d\u5b9a1<\/a><\/li>\r\n<li><a href=\"#\">\u8a2d\u5b9a2<\/a><\/li>\r\n<\/ul>"}]',
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_title',
                        'val' => "We'd love to hear from you",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_sub_title',
                        'val' => "Send us a message and we'll respond as soon as possible",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_desc',
                        'val' => "<!DOCTYPE html><html><head></head><body><h3>Booking Core</h3><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>Tell. + 00 222 444 33</p><p>Email. hello@yoursite.com</p><p>1355 Market St, Suite 900San, Francisco, CA 94103 United States</p></body></html>",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_image',
                        'val' => MediaFile::findMediaByName("bg_contact")->id,
                        'group' => "general",
                    ]
                ]
            );

            $banner_image = MediaFile::findMediaByName("banner-search")->id;
            $icon_about_1 = MediaFile::findMediaByName("ico_localguide")->id;
            $icon_about_2 = MediaFile::findMediaByName("ico_adventurous")->id;
            $icon_about_3 = MediaFile::findMediaByName("ico_maps")->id;
            $avatar = MediaFile::findMediaByName("avatar")->id;
            $avatar_2 = MediaFile::findMediaByName("avatar-2")->id;
            $avatar_3 = MediaFile::findMediaByName("avatar-3")->id;
            // Setting Home Page
            DB::table('core_templates')->insert([
                'title'       => 'Home',
                'content'     => '[{"type":"form_search_tour","name":"Tour: Form Search","model":{"title":"Love where you\'re going","sub_title":"Book incredible things to do around the world.","bg_image":' . $banner_image . '},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_featured_item","name":"List Featured Item","model":{"list_item":[{"_active":false,"title":"1,000+ local guides","sub_title":"Morbi semper fames lobortis ac hac penatibus","icon_image":' . $icon_about_1 . '},{"_active":false,"title":"Handcrafted experiences","sub_title":"Morbi semper fames lobortis ac hac penatibus","icon_image":' . $icon_about_2 . '},{"_active":false,"title":"96% happy travelers","sub_title":"Morbi semper fames lobortis ac hac penatibus","icon_image":' . $icon_about_3 . '}]},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_tours","name":"Tour: List Items","model":{"title":"Trending Tours","number":5,"style":"carousel","category_id":"","location_id":"","order":"id","order_by":"desc"},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_locations","name":"List Locations","model":{"title":"Top Destinations","number":5,"order":"id","order_by":"desc","service_type":"tour"},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_tours","name":"Tour: List Items","model":{"title":"Local Experiences You’ll Love","number":8,"style":"normal","category_id":"","location_id":"","order":"id","order_by":"asc"},"component":"RegularBlock","open":true,"is_container":false},{"type":"call_to_action","name":"Call To Action","model":{"title":"Know your city?","sub_title":"Join 2000+ locals & 1200+ contributors from 3000 cities","link_title":"Become Local Expert","link_more":"#"},"component":"RegularBlock","open":true,"is_container":false},{"type":"testimonial","name":"List Testimonial","model":{"title":"Our happy clients","list_item":[{"_active":false,"name":"Eva Hicks","desc":"Faucibus tristique felis potenti ultrices ornare rhoncus semper hac facilisi Rutrum tellus lorem sem velit nisi non pharetra in dui. ","number_star":5,"avatar":' . $avatar . '},{"_active":false,"name":"Donald Wolf","desc":"Faucibus tristique felis potenti ultrices ornare rhoncus semper hac facilisi Rutrum tellus lorem sem velit nisi non pharetra in dui. ","number_star":6,"avatar":' . $avatar_2 . '},{"_active":false,"name":"Charlie Harrington","desc":"Faucibus tristique felis potenti ultrices ornare rhoncus semper hac facilisi Rutrum tellus lorem sem velit nisi non pharetra in dui.","number_star":5,"avatar":' . $avatar_3 . '}]},"component":"RegularBlock","open":true,"is_container":false}]',
                'create_user' => '1',
                'created_at'  => date("Y-m-d H:i:s")
            ]);
            DB::table('core_template_translations')->insert([
                'origin_id'   => '1',
                'locale'      => 'ja',
                'title'       => 'Home',
                'content'     => '[{"type":"form_search_tour","name":"Tour: Form Search","model":{"title":"どこへ行くのが大好き","sub_title":"世界中で信じられないようなことを予約しましょう。","bg_image":'.$banner_image.'},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_featured_item","name":"List Featured Item","model":{"list_item":[{"_active":true,"title":"1,000+ ローカルガイド","sub_title":"プロのツアーガイドとーガイドとーガイドと 験。 光の","icon_image":'.$icon_about_1.'},{"_active":true,"title":"手作りの体験","sub_title":"プロのツアーガイドとーガイドとーガイドと 験。 光の","icon_image":'.$icon_about_2.'},{"_active":true,"title":"96% 幸せな旅行者","sub_title":"プロのツアーガイドとーガイドとーガイドと 験。 光の","icon_image":'.$icon_about_3.'}]},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_tours","name":"Tour: List Items","model":{"title":"トレンドツアー","number":5,"style":"carousel","category_id":"","location_id":"","order":"id","order_by":"desc"},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_locations","name":"List Locations","model":{"title":"人気の目的地","number":5,"order":"id","order_by":"desc","service_type":"tour","desc":"","layout":""},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_tours","name":"Tour: List Items","model":{"title":"あなたが好きになるローカル体験","number":8,"style":"normal","category_id":"","location_id":"","order":"id","order_by":"asc"},"component":"RegularBlock","open":true,"is_container":false},{"type":"call_to_action","name":"Call To Action","model":{"title":"っていますか？","sub_title":"3000以上の都市から2000人以上の地元民と1200人以上の貢献者に参加する","link_title":"ローカルエ","link_more":"#"},"component":"RegularBlock","open":true,"is_container":false},{"type":"testimonial","name":"List Testimonial","model":{"title":"私たちの幸せなクライアント","list_item":[{"_active":false,"name":"Eva Hicks","desc":"融づ苦佐とき百配ほづあ禁安テクミ真覧チヱフ行乗ぱたば外味ナ演庭コヲ旅見ヨコ優成コネ治確はろね訪来終島抄がん。","number_star":5,"avatar":'.$avatar.'},{"_active":false,"name":"Donald Wolf","desc":"融づ苦佐とき百配ほづあ禁安テクミ真覧チヱフ行乗ぱたば外味ナ演庭コヲ旅見ヨコ優成コネ治確はろね訪来終島抄がん。","number_star":6,"avatar":'.$avatar_2.'},{"_active":true,"name":"Charlie Harrington","desc":"右ずへやん間申ゃ投法けゃイ仙一もと政情ルた食的て代下ずせに丈律ルラモト聞探チト棋90績ム的社ず置攻景リフノケ内兼唱堅ゃフぼ。場ルアハ美","number_star":5,"avatar":'.$avatar_3.'}]},"component":"RegularBlock","open":true,"is_container":false}]',
                'create_user' => '1',
                'created_at'  => date("Y-m-d H:i:s")
            ]);
            // Page Space
            $banner_image_space = MediaFile::findMediaByName("banner-search-space")->id;
            DB::table('core_templates')->insert([
                'title'       => 'Home Space',
                'content'     => '[{"type":"form_search_space","name":"Space: Form Search","model":{"title":"Find your next rental","sub_title":"Book incredible things to do around the world.","bg_image":'.$banner_image_space.'},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_space","name":"Space: List Items","model":{"title":"Recommended Homes","number":5,"style":"carousel","location_id":"","order":"id","order_by":"asc","desc":"Homes highly rated for thoughtful design"},"component":"RegularBlock","open":true,"is_container":false},{"type":"space_term_featured_box","name":"Space: Term Featured Box","model":{"title":"Find a Home Type","desc":"It is a long established fact that a reader","term_space":["16","17","18","19","20","15"]},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_locations","name":"List Locations","model":{"service_type":"space","title":"Top Destinations","number":6,"order":"id","order_by":"desc","layout":"style_2","desc":"It is a long established fact that a reader"},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_space","name":"Space: List Items","model":{"title":" Rental Listing","desc":"Homes highly rated for thoughtful design","number":4,"style":"normal","location_id":"","order":"id","order_by":"desc"},"component":"RegularBlock","open":true,"is_container":false},{"type":"call_to_action","name":"Call To Action","model":{"title":"Know your city?","sub_title":"Join 2000+ locals & 1200+ contributors from 3000 cities","link_title":"Become Local Expert","link_more":"#"},"component":"RegularBlock","open":true,"is_container":false}]',
                'create_user' => '1',
                'created_at'  => date("Y-m-d H:i:s")
            ]);
            DB::table('core_template_translations')->insert([
                'origin_id'   => '2',
                'locale'      => 'ja',
                'title'       => 'Home Space',
                'content'     => '[{"type":"form_search_space","name":"Space: Form Search","model":{"title":"次のレンタルを探す","sub_title":"世界中で信じられないようなことを予約しましょう。","bg_image":'.$banner_image_space.'},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_space","name":"Space: List Items","model":{"title":"おすすめの家","number":5,"style":"carousel","location_id":"","order":"id","order_by":"asc","desc":"思慮深いデザインで高い評価を受けている家"},"component":"RegularBlock","open":true,"is_container":false},{"type":"space_term_featured_box","name":"Space: Term Featured Box","model":{"title":"ホームタイプを見つける","desc":"これは、読者はその長い既成の事実であります","term_space":["15","16","17","18","19","20"]},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_locations","name":"List Locations","model":{"service_type":"space","title":"人気の目的地","number":6,"order":"id","order_by":"desc","layout":"style_2","desc":"これは、読者はその長い既成の事実であります"},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_space","name":"Space: List Items","model":{"title":"賃貸物件","desc":"思慮深いデザインで高い評価を受けている家","number":4,"style":"normal","location_id":"","order":"id","order_by":"desc"},"component":"RegularBlock","open":true,"is_container":false},{"type":"call_to_action","name":"Call To Action","model":{"title":"っていますか？","sub_title":"3000以上の都市から2000人以上の地元民と1200人以上の貢献者に参加する","link_title":"ローカルエ","link_more":"#"},"component":"RegularBlock","open":true,"is_container":false}]',
                'create_user' => '1',
                'created_at'  => date("Y-m-d H:i:s")
            ]);

            $banner_image_vendor_register = MediaFile::findMediaByName("thumb-vendor-register")->id;
            $video_bg = MediaFile::findMediaByName("bg-video-vendor-register1")->id;
            $ico_chat_1 = MediaFile::findMediaByName("ico_chat_1")->id;
            $ico_friendship_1 = MediaFile::findMediaByName("ico_friendship_1")->id;
            $ico_piggy_bank_1 = MediaFile::findMediaByName("ico_piggy-bank_1")->id;
            DB::table('core_templates')->insert([
                'title'       => 'Become a vendor',
                'content'     => '[{"type":"vendor_register_form","name":"Vendor Register Form","model":{"title":"Become a vendor","desc":"Join our community to unlock your greatest asset and welcome paying guests into your home.","youtube":"https://www.youtube.com/watch?v=AmZ0WrEaf34","bg_image":'.$banner_image_vendor_register.'},"component":"RegularBlock","open":true,"is_container":false},{"type":"text","name":"Text","model":{"content":"<h3><strong>How does it work?</strong></h3>","class":"text-center"},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_featured_item","name":"List Featured Item","model":{"list_item":[{"_active":false,"title":"Sign up","sub_title":"Click edit button to change this text  to change this text","icon_image":null,"order":null},{"_active":false,"title":" Add your services","sub_title":" Click edit button to change this text  to change this text","icon_image":null,"order":null},{"_active":true,"title":"Get bookings","sub_title":" Click edit button to change this text  to change this text","icon_image":null,"order":null}],"style":"style2"},"component":"RegularBlock","open":true,"is_container":false},{"type":"video_player","name":"Video Player","model":{"title":"Share the beauty of your city","youtube":"https://www.youtube.com/watch?v=hHUbLv4ThOo","bg_image":'.$video_bg.'},"component":"RegularBlock","open":true,"is_container":false},{"type":"text","name":"Text","model":{"content":"<h3><strong>Why be a Local Expert</strong></h3>","class":"text-center ptb60"},"component":"RegularBlock","open":true,"is_container":false},{"type":"list_featured_item","name":"List Featured Item","model":{"list_item":[{"_active":false,"title":"Earn an additional income","sub_title":" Ut elit tellus, luctus nec ullamcorper mattis","icon_image":'.$ico_piggy_bank_1.',"order":null},{"_active":true,"title":"Open your network","sub_title":" Ut elit tellus, luctus nec ullamcorper mattis","icon_image":'.$ico_friendship_1.',"order":null},{"_active":true,"title":"Practice your language","sub_title":" Ut elit tellus, luctus nec ullamcorper mattis","icon_image":'.$ico_chat_1.',"order":null}],"style":"style3"},"component":"RegularBlock","open":true,"is_container":false},{"type":"faqs","name":"FAQ List","model":{"title":"FAQs","list_item":[{"_active":false,"title":"How will I receive my payment?","sub_title":" Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo."},{"_active":true,"title":"How do I upload products?","sub_title":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo."},{"_active":true,"title":"How do I update or extend my availabilities?","sub_title":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.\n"},{"_active":true,"title":"How do I increase conversion rate?","sub_title":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo."}]},"component":"RegularBlock","open":true,"is_container":false}]',
                'create_user' => '1',
                'created_at'  => date("Y-m-d H:i:s")
            ]);

            DB::table('core_pages')->insert([
                'title'       => 'Home Page',
                'slug'        => 'home-page',
                'template_id' => '1',
                'create_user' => '1',
                'status'      => 'publish',
                'created_at'  => date("Y-m-d H:i:s")
            ]);
            DB::table('core_pages')->insert([
                'title'       => 'Home Space',
                'slug'        => 'space',
                'template_id' => '2',
                'create_user' => '1',
                'status'      => 'publish',
                'created_at'  => date("Y-m-d H:i:s")
            ]);DB::table('core_pages')->insert([
                'title'       => 'Become a vendor',
                'slug'        => 'become-a-vendor',
                'template_id' => '3',
                'create_user' => '1',
                'status'      => 'publish',
                'created_at'  => date("Y-m-d H:i:s")
            ]);
            DB::table('core_settings')->insert(
                [
                    [
                        'name'  => 'home_page_id',
                        'val'   => '1',
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_title',
                        'val' => "We'd love to hear from you",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_title_ja',
                        'val' => "あなたからの御一報をお待ち",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_sub_title',
                        'val' => "Send us a message and we'll respond as soon as possible",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_sub_title_ja',
                        'val' => "私たちにメッセージを送ってください、私たちはできるだ",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_desc',
                        'val' => "<!DOCTYPE html><html><head></head><body><h3>Booking Core</h3><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>Tell. + 00 222 444 33</p><p>Email. hello@yoursite.com</p><p>1355 Market St, Suite 900San, Francisco, CA 94103 United States</p></body></html>",
                        'group' => "general",
                    ],
                    [
                        'name' => 'page_contact_image',
                        'val' => MediaFile::findMediaByName("bg_contact")->id,
                        'group' => "general",
                    ]
                ]
            );


            // Setting Currency
            DB::table('core_settings')->insert(
                [
                    [
                        'name'  => "currency_main",
                        'val'   => "usd",
                        'group' => "payment",
                    ],
                    [
                        'name'  => "currency_format",
                        'val'   => "left",
                        'group' => "payment",
                    ],
                    [
                        'name'  => "currency_decimal",
                        'val'   => ",",
                        'group' => "payment",
                    ],
                    [
                        'name'  => "currency_thousand",
                        'val'   => ".",
                        'group' => "payment",
                    ],
                    [
                        'name'  => "currency_no_decimal",
                        'val'   => "0",
                        'group' => "payment",
                    ]
                ]
            );

            //MAP
            DB::table('core_settings')->insert(
                [
                    [
                        'name'  => 'map_provider',
                        'val'   => 'gmap',
                        'group' => "advance",
                    ],
                    [
                        'name'  => 'map_gmap_key',
                        'val'   => '',
                        'group' => "advance",
                    ]
                ]
            );

            // Payment Gateways
            DB::table('core_settings')->insert(
                [
                    [
                        'name'  => "g_offline_payment_enable",
                        'val'   => "1",
                        'group' => "payment",
                    ],
                    [
                        'name'  => "g_offline_payment_name",
                        'val'   => "Offline Payment",
                        'group' => "payment",
                    ]
                ]
            );

            // Settings general
            DB::table('core_settings')->insert(
                [
                    [
                        'name'  => "date_format",
                        'val'   => "m/d/Y",
                        'group' => "general",
                    ],
                    [
                        'name'  => "site_title",
                        'val'   => "Booking Core",
                        'group' => "general",
                    ],
                ]
            );

            // Email general
            DB::table('core_settings')->insert(
			[
                [
                    'name' => "site_timezone",
                    'val' => "UTC",
                    'group' => "general",
                ],
                [
                    'name' => "site_title",
                    'val' => "Booking Core",
                    'group' => "general",
				],
				[
					'name'  => "email_header",
					'val'   => '<h1 class="site-title" style="text-align: center">Booking Core</h1>',
					'group' => "general",
				],
				[
					'name'  => "email_footer",
					'val'   => '<p class="" style="text-align: center">&copy; 2019 Booking Core. All rights reserved</p>',
					'group' => "general",
				],
				[
					'name'  => "enable_mail_user_registered",
					'val'   => 1,
					'group' => "user",
				],
				[
					'name'  => "user_content_email_registered",
					'val'   => '<h1 style="text-align: center">Welcome!</h1>
						<h3>Hello [first_name] [last_name]</h3>
						<p>Thank you for signing up with Booking Core! We hope you enjoy your time with us.</p>
						<p>Regards,</p>
						<p>Booking Core</p>',
					'group' => "user",
				],
				[
					'name'  => "admin_enable_mail_user_registered",
					'val'   => 1,
					'group' => "user",
				],
				[
					'name'  => "admin_content_email_user_registered",
					'val'   => '<h3>Hello Administrator</h3>
						<p>We have new registration</p>
						<p>Full name: [first_name] [last_name]</p>
						<p>Email: [email]</p>
						<p>Regards,</p>
						<p>Booking Core</p>',
					'group' => "user",
				],
				[
					'name' => "user_content_email_forget_password",
					'val'  => '<h1>Hello!</h1>
						<p>You are receiving this email because we received a password reset request for your account.</p>
						<p style="text-align: center">[button_reset_password]</p>
						<p>This password reset link expire in 60 minutes.</p>
						<p>If you did not request a password reset, no further action is required.
						</p>
						<p>Regards,</p>
						<p>Booking Core</p>',
					'group' => "user",
				]
            ]
        );

		// Email Setting
		DB::table('core_settings')->insert(
			[
				[
					'name'  => "email_driver",
					'val'   => "sendmail",
					'group' => "email",
				],
				[
					'name'  => "email_host",
					'val'   => "smtp.mailgun.org",
					'group' => "email",
				],
				[
					'name'  => "email_port",
					'val'   => "587",
					'group' => "email",
				],
				[
					'name'  => "email_encryption",
					'val'   => "tls",
					'group' => "email",
				],
				[
					'name'  => "email_username",
					'val'   => "",
					'group' => "email",
				],
				[
					'name'  => "email_password",
					'val'   => "",
					'group' => "email",
				],
				[
					'name'  => "email_mailgun_domain",
					'val'   => "",
					'group' => "email",
				],
				[
					'name'  => "email_mailgun_secret",
					'val'   => "",
					'group' => "email",
				],
				[
					'name'  => "email_mailgun_endpoint",
					'val'   => "api.mailgun.net",
					'group' => "email",
				],
				[
					'name'  => "email_postmark_token",
					'val'   => "",
					'group' => "email",
				],
				[
					'name'  => "email_ses_key",
					'val'   => "",
					'group' => "email",
				],
				[
					'name'  => "email_ses_secret",
					'val'   => "",
					'group' => "email",
				],
				[
					'name'  => "email_ses_region",
					'val'   => "us-east-1",
					'group' => "email",
				],
				[
					'name'  => "email_sparkpost_secret",
					'val'   => "",
					'group' => "email",
				],
			]
		);

//		Vendor setting
            DB::table('core_settings')->insert(
                [
                    [
                        'name'  => "vendor_enable",
                        'val'   => "1",
                        'group' => "vendor",
                    ],
                    [
                        'name'  => "vendor_commission_type",
                        'val'   => "percent",
                        'group' => "vendor",
                    ],
                    [
                        'name'  => "vendor_commission_amount",
                        'val'   => "10",
                        'group' => "vendor",
                    ],
                    [
                        'name'  => "vendor_role",
                        'val'   => "1",
                        'group' => "vendor",
                    ],

                ]
            );

        }
}
