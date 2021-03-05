<?php

/**
 * Returns TRUE if $path is a valid Image File
 *
 * @param string $path
 * @return bool
 */
function isImage(string $path): bool {

    if (!is_readable($path)) {
        return false;
    }

    $supported = [IMAGETYPE_JPEG, IMAGETYPE_PNG];

    $type = exif_imagetype($path);

    // $type can be valid, but not "supported"
    if (!in_array($type, $supported)) {
        return false;
    }

    // check the image content, header check is not enough
    $image = false;
    switch ($type) {
        case IMAGETYPE_PNG:
            $image = @imagecreatefrompng($path);
            break;
        case IMAGETYPE_JPEG:
            $image = @imagecreatefromjpeg($path);
            break;
    }

    return (!!$image);
}

function getImages(): array {
    $images = array();
    $baseImgPath = "img/";
    if ($handle = opendir($baseImgPath)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && $entry != "theme-light-dark.png") {
                if (isImage($baseImgPath . $entry)) {
                    array_push($images,
                        array(
                            "name" => pathinfo($entry, PATHINFO_FILENAME),
                            "image" => $baseImgPath . $entry
                        )
                    );
                }
            }
        }
        closedir($handle);
    }
    usort($images, function ($a, $b) {
        return $a['name'] <=> $b['name'];
    });
    return $images;
}