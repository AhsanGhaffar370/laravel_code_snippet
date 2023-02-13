<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Config;
use Illuminate\Support\Str;

use Exception;
use Twocheckout;
use Twocheckout_Charge;
use App\Traits\CompanySettingsTrait;

class Helper
{
    use CompanySettingsTrait;
    
    public static function getImg($folder = null, $filename = null, $image_type='non_user', $disk='storage')
    {
      $img_path = $disk . $folder . $filename;

      if($filename != null && $filename != '' && file_exists(public_path($img_path)))
        return asset($img_path);
      else
        return ($image_type == 'non_user') ? asset(config('globals.DEFAULT_IMAGE_PATH')) : asset(config('globals.DEFAULT_USER_IMAGE_PATH'));
    }

    public static function getCategories($categories, $userCategories = null, $count = 0, $exclude = false)
    {
        // Condition for create page
        if($userCategories == null) {
            foreach ($categories as $category) {
                
                echo '<option value="'.$category->id.'">'.str_repeat('&nbsp;&nbsp;', $count) . ' ' . $category->name . '</option>';
                
                if (count($category->children) > 0)
                    Helper::getCategories($category->children, $userCategories, $count + 1, $exclude);
            }
        } 
        // Condition for edit and show page
        else {
            foreach ($categories as $category) {
                $selected = '';
                // dd($category->id, ($userCategories->parent_id ?? $userCategories));
                if($category->id == ($userCategories->parent_id ?? $userCategories)){
                    $selected = 'selected';
                }
                if ($category->id !== ($userCategories->id ?? $userCategories) || $exclude)
                    echo '<option value="'.$category->id.'" '.$selected.'>'.str_repeat('&nbsp;&nbsp;', $count) . ' ' . $category->name . '</option>';
            
                if (count($category->children) > 0 && ($category->id !== ($userCategories->id ?? $userCategories) || $exclude))
                    Helper::getCategories($category->children, $userCategories, $count + 1, $exclude);
            }
        }
    }
}
