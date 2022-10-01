<?php

use App\Models\Backend\AccessControl;
use App\Models\Backend\APIManager;
use App\Models\Backend\ManagerAuth;
use App\Models\Backend\ManagerInfo;
use App\Models\Backend\PageManager;
use App\Models\Backend\RestaurantCategory;
use App\Models\Backend\SystemLog;
use App\Models\Grocery\AccountGroups;
use App\Models\Grocery\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // SystemLog::create(array('message' => 'System initialized successfully!'));
        // $UID = strtoupper(substr('Super Admin', 0, 3)) . strtoupper(Str::random(7));
        // ManagerInfo::create(array(
        //     'uid' => $UID,
        //     'name' => 'Rafat Hossain',
        //     'email' => 'admin@khaidaitoday.com',
        //     'mobile' => '01704005054',
        //     'photo' => '/storage/defaults/images/avatar.png',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default super admin account created by System.'));
        // ManagerAuth::create(array(
        //     'uid' => $UID,
        //     'email' => 'admin@khaidaitoday.com',
        //     'password' => Hash::make('admin'),
        //     'role_id' => '9517',
        //     'last_login' => '',
        //     'last_login_ip' => '',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default super admin authentication created by System.'));
        // $UID = strtoupper(substr('Super Admin', 0, 3)) . strtoupper(Str::random(7));
        // ManagerInfo::create(array(
        //     'uid' => $UID,
        //     'name' => 'Ar Aminul Islam Shagor',
        //     'email' => 'shagor@khaidaitoday.com',
        //     'mobile' => '01735830494',
        //     'photo' => '/storage/defaults/images/avatar.png',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default super admin account created by System.'));
        // ManagerAuth::create(array(
        //     'uid' => $UID,
        //     'email' => 'shagor@khaidaitoday.com',
        //     'password' => Hash::make('admin'),
        //     'role_id' => '9517',
        //     'last_login' => '',
        //     'last_login_ip' => '',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default super admin authentication created by System.'));
        // RestaurantCategory::create(array(
        //     'category' => 'Fast Food',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Bangla',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Thai',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Chinese',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Biryani',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Kabab',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Fuska',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Sweet',
        //     'updated_by' => 'System,0'
        // ));
        // RestaurantCategory::create(array(
        //     'category' => 'Drinks',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default restaurant category has been added by System.'));
        // PageManager::create(array(
        //     'page_title' => 'Page Manager',
        //     'page_id' => '7531',
        //     'page_view' => 'PageManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Access Control',
        //     'page_id' => '8246',
        //     'page_view' => 'AccessControl',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'City Manager',
        //     'page_id' => '1279',
        //     'page_view' => 'CityManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Restaurant Manager',
        //     'page_id' => '2210',
        //     'page_view' => 'RestaurantManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Menu Manager',
        //     'page_id' => '8119',
        //     'page_view' => 'MenuManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Featured Restaurats',
        //     'page_id' => '7580',
        //     'page_view' => 'FeaturedRestaurants',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Old User Manager',
        //     'page_id' => '9909',
        //     'page_view' => 'OldUserManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'User Manager',
        //     'page_id' => '4533',
        //     'page_view' => 'UserManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'API Manager',
        //     'page_id' => '3438',
        //     'page_view' => 'APIManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Category Manager',
        //     'page_id' => '3043',
        //     'page_view' => 'CategoryManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Notification Manager',
        //     'page_id' => '6709',
        //     'page_view' => 'NotificationManager',
        //     'updated_by' => 'System,0'
        // ));
        // PageManager::create(array(
        //     'page_title' => 'Rider Manager',
        //     'page_id' => '4400',
        //     'page_view' => 'RiderManager',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default page permissions have been set by System.'));
        // APIManager::create(array(
        //     'service_name' => 'Reverse Geocoding by Google',
        //     'service_identifier' => 'REVERSE_GEOCODING',
        //     'used_balance' => '0.00',
        //     'api_key' => 'AIzaSyDDQDODIohgoTCiWGKcQXkh6hYXQwMpxSk',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default API has been set by System.'));
        // AccessControl::create(array(
        //     'role_id' => '9517',
        //     'role_title' => 'Super Admin',
        //     'role_permissions' => '7531_111,8246_111,1279_111,2210_111,8119_111,7580_111,9909_111,4533_111,3438_111,3043_111,6709_111,4400_111',
        //     'updated_by' => 'System,0'
        // ));
        // AccessControl::create(array(
        //     'role_id' => '4520',
        //     'role_title' => 'Content Manager',
        //     'role_permissions' => '2210_111,8119_111',
        //     'updated_by' => 'System,0'
        // ));
        // SystemLog::create(array('message' => 'Default access control has been set by System.'));
        AccountGroups::create(array(
            'group_title' => 'Super Admin',
            'access_control' => ''
        ));
        Admin::create(array(
            'user_id' => "GR" . strtoupper(Str::random(6)),
            'name' => 'Rafat Hossain',
            'email' => 'rafat@khaidaitoday.com',
            'password' => Hash::make('951753'),
            'user_group' => '1'
        ));
        Admin::create(array(
            'user_id' => "GR" . strtoupper(Str::random(6)),
            'name' => 'Aminul Isalm Shagor',
            'email' => 'shagor@khaidaitoday.com',
            'password' => Hash::make('01722850218'),
            'user_group' => '2'
        ));
    }
}
