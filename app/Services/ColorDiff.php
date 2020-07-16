<?php


namespace App\Services;

use Illuminate\Support\Collection;

class ColorDiff
{

    public function ColorDiff(object $history):object
    {
        $newVersionDiff = null;

        foreach ($history as $version) {
            if ($newVersionDiff) {

                // get compere data
                $arrayNewVersion = collect($newVersionDiff)->except(['created_at', 'updated_at', 'version_id', 'id'])->toArray();
                $arrayOldVersion = collect($version)->except(['created_at', 'updated_at', 'version_id', 'id'])->toArray();

                // compare array
                $diff = array_diff_assoc($arrayNewVersion, $arrayOldVersion);

                // color diff
                $collection = array();
                if (!empty($diff)) {
                    foreach ($diff as $keyDiff => $value) {

                        // change color in the new version and replace in array
                        foreach ($arrayNewVersion as $key => $value) {
                            if ($keyDiff == $key) {
                                $newVersion[$key] = "<span style=\"background-color:#ddfbe6;\"> " . $value . "</span>";
//                                $var = collect($history->where($key, $value));
                                $newVersion = array_replace($arrayNewVersion, $newVersion);
                                $collection [] = collect($newVersion);
                            }
                        }

                        // change color in the old version and replace in array
                        foreach ($arrayOldVersion as $key => $value) {
                            if ($keyDiff == $key) {
                                $oldVersion[$key] = "<span style=\"background-color:#f9d7dc;\"> " . $value . "</span>";
                                $oldVersion = array_replace($arrayOldVersion, $oldVersion);
                                $collection [] = collect($oldVersion);
                            }
                        }

                    }
                }
                $history = collect($collection);
            }
            $newVersionDiff = $version;
        }
//        dd($history);
        return $history;
    }

}
