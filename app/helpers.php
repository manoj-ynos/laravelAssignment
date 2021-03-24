<?php

use Illuminate\Support\Facades\Route;


/*
  |--------------------------------------------------------------------------
  | Detect Active Route
  |--------------------------------------------------------------------------
  |
  | Compare given route with current route and return output if they match.
  | Very useful for navigation, marking if the link is active.
  |
 */

function isActiveRoute($route, $output = "active")
{
    $result = stripos(Route::current()->uri(), $route);
    if ($result !== false)
        return $output;
}

/*
  |--------------------------------------------------------------------------
  | Detect Api OR Web User
  |--------------------------------------------------------------------------
  |
  | Find if user is trying to register/log in through the api or the web.
  |
 */

function isWebUser()
{
    return strpos(Route::current()->uri(), "api") === false;
}

function getAppVersion()
{
    $request = request();
    $appversion = !empty($request->header('appversion')) ? str_replace('.', '', $request->header('appversion')) : 0;
    return $appversion;
}

function getLanguage()
{
    $request = request();
    $lang = !empty(trim($request->header('Accept-Language'))) ? trim($request->header('Accept-Language')) : 'en';

    if ($lang == 'en' || $lang == 'ar' || $lang == 'en_ar') {
        return $lang;
    }
    return 'en';
}

function weekList()
{
    $week_array = [
        1 => 'Sunday',
        2 => 'Monday',
        3 => 'Tuesday',
        4 => 'Wednesday',
        5 => 'Thursday',
        6 => 'Friday',
        7 => 'Saturday',
    ];
    return $week_array;
}

function my_asset($path)
{
    return env('APP_URL', 'https://cdn.bkt.com/') . trim($path);
}

function upload_path($path = "")
{
    return public_path('/upload/' . trim($path));
}

/**
 * 
 * @param type $unix_date
 * @param type $now
 * @return type
 */
function time_ago($unix_date, $now = null)
{

    //        echo 'unix_date = '.$unix_date;
    //        echo '<br>';
    //        echo 'now ='.$now;die;

    $unix_date = strtotime($unix_date);
    $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

    if (!empty($now)) {
        $now = strtotime($now);
    } else {
        $now = time();
    }

    // is it future date or past date
    if ($now > $unix_date) {
        $difference = $now - $unix_date;
        $tense = "ago";
    } else {
        $difference = $unix_date - $now;
        $tense = "from now";
    }
    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference, 0, PHP_ROUND_HALF_UP);
    //                $difference = round($difference,2);
    if ($difference != 1) {
        $periods[$j] .= "s";
    }
    return "$difference $periods[$j] {$tense}";
}

/**
 * 
 * @param type $array
 * @param type $request
 * @param type $page
 * @param type $perPage
 * @return \Illuminate\Pagination\LengthAwarePaginator
 */
function arrayPaginator($array, $request, $page = 1, $perPage = 10)
{
    $offset = ($page * $perPage) - $perPage;
    return new Illuminate\Pagination\LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);
}

function getNotificationType($type = "global")
{
    $notifcationType = [
        "global" => 1,
        "like_profile" => 2,
        "like_job" => 3,
        "like_video" => 4,
        "upload_video" => 8
    ];
    return $notifcationType[$type];
}

function sendNotificationAndroid($fields, $fcm_key)
{

    $url = 'https://fcm.googleapis.com/fcm/send';

    $headers = array(
        'Authorization: key=' . $fcm_key,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);

    $resultArr = json_decode($result, true);

    if ($resultArr['success'] == 1) {
        return true;
    }
    return false;
}

function sendNotificationIos($userdeviceToken, $body = array(), $pem_file_path = IOS_PEM_PATH, $app_is_live = APP_IS_LIVE)
{

    // Construct the notification payload
    if (!isset($body['aps']['sound'])) {
        $body['aps']['sound'] = "default";
    }

    // End of Configurable Items
    $payload = json_encode($body);
    $ctx = stream_context_create();
    $filename = $pem_file_path;
    if (!file_exists($filename)) {
        return true;
    }

    if (is_array($userdeviceToken) && count($userdeviceToken) > 0) {
        foreach ($userdeviceToken as $key => $token_rec_id) {
            stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
            if ($app_is_live == 'true')
                $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
            else
                $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);

            if (!$fp) {
                continue;
            } else {
                if ($token_rec_id != '') {
                    $msg = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $token_rec_id)) . pack("n", strlen($payload)) . $payload;
                    fwrite($fp, $msg);
                }
            }
            fclose($fp);
        }
    } else {
        stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
        if ($app_is_live == 'true')
            $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
        else
            $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);

        if (!$fp) {
            return false;
            //    return "Failed to connect $err $errstr";
        } else {
            $token_rec_id = $userdeviceToken;
            if ($token_rec_id != '') {
                $msg = chr(0) . pack("n", 32) . pack('H*', str_replace(' ', '', $token_rec_id)) . pack("n", strlen($payload)) . $payload;
                fwrite($fp, $msg);
            }
        }
        fclose($fp);
    }
    return true;
    // END CODE FOR PUSH NOTIFICATIONS TO ALL USERS
}

if (!function_exists('getPhotoURL')) {
    function getPhotoURL($type, $id, $photoName)
    {
        $photoURL = '';
        switch ($type) {
            case 'admins':
                $img_folder = config('constants.UPLOAD_ADMINS_FOLDER');
                $img_path = public_path("upload/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/upload/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/upload/avatar-1.png');
                }
                break;
            case 'users':
                $img_folder = config('constants.UPLOAD_USERS_FOLDER');
                $img_path = public_path("upload/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/upload/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/upload/avatar-1.png');
                }
                break;
            case 'conversation_doc':
                $img_folder = config('constants.UPLOAD_CONVERSATION_DOC_FOLDER');
                $img_path = public_path("upload/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/upload/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/no-image.png');
                }
                break;
                case 'conversation_detail':
                $img_folder = config('constants.UPLOAD_CONVERSATION_DETAIL_FOLDER');
                $img_path = public_path("upload/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/upload/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/no-image.png');
                }
                break;
            case 'themes':
                $img_folder = config('constants.UPLOAD_THEMES_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'recipes':
                $img_folder = config('constants.UPLOAD_RECIPES_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/recipe.jpg');
                }
                break;
            case 'teachers':
                $img_folder = config('constants.UPLOAD_TEACHERS_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'goddess':
                $img_folder = config('constants.UPLOAD_GODDESS_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'journal_prompts':
                $img_folder = config('constants.UPLOAD_JOURNAL_PROMPTS_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'practices':
                $img_folder = config('constants.UPLOAD_PRACTICES_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);

                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'equipments':
                $img_folder = config('constants.UPLOAD_EQUIPMENTS_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'vlc':
                $img_folder = config('constants.UPLOAD_VLC_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'video_libraries':
                $img_folder = config('constants.UPLOAD_VIDEO_LIBRARY_FOLDER');
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'community_opinions':
                $img_folder = "community_opinions";
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
            case 'products':
                $img_folder = "products";
                $img_path = public_path("uploads/" . $img_folder . "/" . $id . "/" . $photoName);
                if (!empty($id) && !empty($photoName) && file_exists($img_path)) {
                    $photoURL = asset('public/uploads/' . $img_folder . '/' . $id . '/' . $photoName);
                } else {
                    $photoURL = asset('public/uploads/default_yoga.png');
                }
                break;
        }

        return $photoURL;
    }
}


if (!function_exists('rmdir_recursive')) {
    function rmdir_recursive($dir)
    {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file)
                continue;
            if (is_dir("$dir/$file"))
                rmdir_recursive("$dir/$file");
            else
                unlink("$dir/$file");
        }

        rmdir($dir);
    }
}


// Function for generate random string for access token generate

if (!function_exists('str_rand_access_token')) {
    function str_rand_access_token($length = 32, $seeds = 'allalphanum')
    {
        // Possible seeds
        $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
        $seedings['numeric'] = '0123456789';
        $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
        $seedings['allalphanum'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqyz0123456789';
        $seedings['upperalphanum'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $seedings['alphanumspec'] = 'abcdefghijklmnopqrstuvwqyz0123456789!@#$%^*-_=+';
        $seedings['alphacapitalnumspec'] = 'abcdefghijklmnopqrstuvwqyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789#@!*-_';
        $seedings['hexidec'] = '0123456789abcdef';
        $seedings['customupperalphanum'] = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; //Confusing chars like 0,O,1,I not included
        // Choose seed
        if (isset($seedings[$seeds])) {
            $seeds = $seedings[$seeds];
        }

        // Seed generator
        list($usec, $sec) = explode(' ', microtime());
        $seed = (float) $sec + ((float) $usec * 100000);
        mt_srand($seed);

        // Generate
        $str = '';
        $seeds_count = strlen($seeds);

        for ($i = 0; $length > $i; $i++) {
            $str .= $seeds{
                mt_rand(0, $seeds_count - 1)};
        }

        return $str;
    }
}

if (!function_exists('get_level_name')) {
    function get_level_name($get_total_point = "", $previous_points = "")
    {
        if (!empty($get_total_point) && !empty($previous_points)) {
            switch (true) {
                case $get_total_point >= 2800 && $previous_points <= 2800:
                    return "Yaay! You reached to highest level Goddess.";
                case $get_total_point >= 2000 && $previous_points <= 2000:
                    return "Yaay! You reached to new level Queen. Complete 800 points to reach to next level Goddess.";
                case $get_total_point >= 1200 && $previous_points <= 1200:
                    return "Yaay! You reached to new level Priestess. Complete 800 points to reach to next level Queen.";
                case $get_total_point >= 500 && $previous_points <= 500:
                    return "Yaay! You reached to new level Maiden. Complete 700 points to reach to next level Priestess.";
                default:
                    return false;
            }
        }

        return false;
    }
}

if (!function_exists('get_order_status')) {
    function get_order_status($order_state)
    {
        $stateArr = getOrderTrackingStatusArr();

        $a =  isset($stateArr[$order_state]) ? $stateArr[$order_state] : '';

        return $a;
    }
}

if (!function_exists('get_user_activity_from')) {
    function get_user_activity_from($activityId)
    {
        $stateArr = [
            '1' => "<a class='text-primary' href='" . route('practices.show', $activityId) . "'>#Practice</a>",
            '2' => "<a class='text-primary' href='" . route('journal_prompts.show', $activityId) . "'>#Journal Prompt</a>",
            '3' => "<a class='text-primary' href='" . route('recipes.show', $activityId) . "'>#Recipe</a>",
            '4' => '#Calendar',
            '5' => "<a class='text-primary' href='" . route('users.show', $activityId) . "'>#User</a>"
        ];

        $a =  isset($stateArr[$activityId]) ? $stateArr[$activityId] : '';

        return $a;
    }
}

if (!function_exists('get_user_activity_type')) {
    function get_user_activity_type($activityId)
    {
        $stateArr = [
            'completing_daily_practice' => 'Completing daily Practice',
            'shared_practice' => 'Shared Practice',
            'shared_journal_prompt_of_the_day' => 'Shared Journal prompt of the day',
            'shared_racipe_food_photo' => 'Shared Racipe food photo',
            'shared_months_calendar' => 'Shared Months Calendar',
            'invite_friend' => 'Invited friend'
        ];

        $a =  isset($stateArr[$activityId]) ? $stateArr[$activityId] : '';

        return $a;
    }
}

if (!function_exists('get_user_subscription_plan')) {
    function get_user_subscription_plan($planName)
    {
        $stateArr = getUserSubscriptionPlanName();

        $a =  isset($stateArr[$planName]) ? $stateArr[$planName] : ' - ';

        return $a;
    }
}

if (!function_exists('get_user_subscription_type')) {
    function get_user_subscription_type($planName)
    {
        $stateArr = getUserSubscriptionType();

        $a =  isset($stateArr[$planName]) ? $stateArr[$planName] : ' - ';

        return $a;
    }
}

if (!function_exists('array_sort_by_column')) {
    function array_sort_by_column(&$array, $column, $direction = SORT_DESC)
    {
        $reference_array = array();

        foreach ($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        array_multisort($reference_array, $direction, $array);
    }
}


if (!function_exists('getRecipeType')) {
    function getRecipeType($arr)
    {
        $stateArr = [
            '1' => 'Breakfast',
            '2' => 'Lunch',
            '3' => 'Dinner'
        ];

        $a = '';
        if (!empty($arr)) {
            foreach ($stateArr as $key => $value) {
                if (in_array($key, $arr)) {
                    $a .= $value . '<br>';
                }
            }
        }

        return $a;
    }
}

if (!function_exists('getOrderTrackingStatusArr')) {
    function getOrderTrackingStatusArr()
    {
        $stateArr = [
            '1' => 'Order Receiver', '2' => 'Preparing', '3' => 'Ready', '4' => 'Dispatch', '5' => 'Delivered', '6' => 'Closed'
        ];
        return $stateArr;
    }
}

if (!function_exists('getUserSubscriptionPlanName')) {
    function getUserSubscriptionPlanName()
    {
        $stateArr = [
            'premium_yearly' => 'Premium Yearly',
            'premium_monthly' => 'Premium Monthly',
            'trial' => 'Trial',
            'basic_monthly' => 'Basic Monthly',
        ];
        return $stateArr;
    }
}

if (!function_exists('getUserSubscriptionType')) {
    function getUserSubscriptionType()
    {
        $stateArr = [
            '1' => 'Free trial',
            '2' => 'Monthly',
            '3' => 'Yearly'
        ];
        return $stateArr;
    }
}

if (!function_exists('displayPrice')) {
    function displayPrice($price, $useNumberFormat = 0)
    {
        if ($useNumberFormat) return defaultCurrency() . number_format($price, 2);
        return defaultCurrency() . $price;
    }
}

if (!function_exists('defaultCurrency')) {
    function defaultCurrency()
    {
        return '$';
    }
}


if (!function_exists('userLevels')) {
    function userLevels()
    {
        $point_system = [
            "beginner" => 'Beginner',
            "maiden" => 'Maiden',
            "priestess" => 'Priestess',
            "queen" => 'Queen',
            "goddess" => 'Goddess',
        ];

        $levelArr = [];

        foreach ($point_system as $key => $value) {
            $points = Settings::where('s_name', $key)->where('s_status', 1)->first();

            $data = [];
            if (!empty($points) && $points->count()) {
                $data = explode('-', $points->s_value);
            }

            if (!empty($data)) {
                $levelArr[$key] = [
                    'name' => $value,
                    'from' => isset($data[0]) ? $data[0] : '',
                    'to' => isset($data[1]) ? $data[1] : '',
                ];
            }
        }

        return $levelArr;
    }
}


if (!function_exists('getUserLevelFromPoint')) {
    function getUserLevelFromPoint($point)
    {
        $levels = userLevels();

        $levelName = '';

        if (!empty($levels) && count($levels)) {
            foreach ($levels as $val) {
                if ($val['from'] != '' && $val['to'] != '') {
                    if ($point >= $val['from'] && $point <= $val['to']) {
                        $levelName = $val['name'];
                        break;
                    }
                } elseif ($val['from'] != '') {
                    if ($point >= $val['from']) {
                        $levelName = $val['name'];
                        break;
                    }
                }
            }
        }

        return $levelName;
    }
}


if (!function_exists('uniqueCode')) {
    function uniqueCode()
    {
        list($usec, $sec) = explode(" ", microtime());
        return str_replace('0.', '', $sec . $usec);
    }
}

if (!function_exists('replace_null_with_empty_string')) {

    function replace_null_with_empty_string($array)
    {
        if (is_object($array)) {
            $array = $array->toArray();
        }
        foreach ($array as $key => $value) {
            if (is_object($value)) {
                $value = $value->toArray();
            }
            if (is_array($value)) {
                $array[$key] = replace_null_with_empty_string($value);
            } else {
                if (is_null($value)) {
                    $array[$key] = "";
                }

            }
        }
        return $array;
    }
}