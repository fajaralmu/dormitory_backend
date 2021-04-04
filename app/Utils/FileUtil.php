<?php

namespace App\Utils;

class FileUtil
{
    private static int $counter = 1;

    public static function delete(string $name, string $code) : bool
    {
        try {
            $path = base_path('public'). '/upload/'.$code.'/'.$name;
            if (!file_exists($path)) {
                out("FILE: ".$path." not exist");
                return false;
            }
            $is_deleted = unlink($path);
            out("FILE: ".$path." is_deleted:" .$is_deleted);
            return $is_deleted;
        } catch (\Throwable $th) {
            return false;
        }
        
    }

    public static function writeBase64File(string $base64_string, string $code, $name = null) : string
    {
        if (is_null($name)) {
            $name = $code.'_'.FileUtil::$counter.now()->getTimestamp();
        }
        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode(',', $base64_string);
        if (sizeof($data) != 2) {
            return "";
        }
        $extensionRaw = explode('/', $data[0]);
        $extension = explode(';', $extensionRaw[1]);

        $directory = base_path('public'). '/upload/'.$code.'/';
        $dir_exist = file_exists($directory);
        if (!$dir_exist) {
            mkdir($directory);
        }
        $output_file =  $directory . $name . ".". $extension[0];
        // open the output file for writing
        $ifp = fopen($output_file, 'wb');
    
        // we could add validation here with ensuring count( $data ) > 1
        fwrite($ifp, base64_decode($data[ 1 ]));
    
        // clean up the file resource
        fclose($ifp);
        FileUtil::$counter++;
        return $name . ".". $extension[0];
    }
}
