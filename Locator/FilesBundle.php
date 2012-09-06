<?php

namespace Netpeople\EmailTemplateBundle\Locator;

/**
 * Description of Bundles
 *
 * @author maguirre
 */
class FilesBundle
{

    public static function getFiles($path)
    {
        $files = array();
        $path = rtrim($path, '/');
        if (is_dir($path)) {
            $dir = scandir($path);
            foreach ($dir as $file) {
                if (strpos($file, '.') === 0) {
                    continue;
                } elseif (is_file("$path/$file")) {
                    $files[] = "$path/$file";
                    continue;
                }
                foreach (self::getFiles("$path/$file") as $f) {
                    $files[] = $f;
                }
            }
        }
        return $files;
    }

}
