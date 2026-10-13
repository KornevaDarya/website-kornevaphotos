<?

    /**
     * @param string $file_path
     * @param int $width
     * @param int $height
     * @param bool $crop
     * @throws ImagickException
     */
    function compress_resize_img(string $file_path, int $width = 1280, int $height = 720, bool $crop = true): void
    {
        if (file_exists($file_path)) {
            //$file_type = mb_strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

            $img = new Imagick($file_path);
            if (
                (($img->getImageColorspace() != IMagick::COLORSPACE_SRGB) || ($img->getImageDepth() > 8))
                &&
                $img->getImageColorspace() != IMagick::COLORSPACE_GRAY
            ) {
                $img->transformimagecolorspace(IMagick::COLORSPACE_RGB);
                $img->setImageDepth(8);
            }
            resize_img($width, $height, $img, $crop);
            //if ($file_type == "png") {
            //    $img->setImageFormat("png");
            //} else {
            $img->setImageFormat("jpg");
            $img->setImageCompression(Imagick::COMPRESSION_JPEG);
            $img->setImageCompressionQuality(50);
            $img->setInterlaceScheme(Imagick::INTERLACE_PLANE);
            //}

            $file_path_webp = pathinfo($file_path, PATHINFO_DIRNAME) . "/" . pathinfo($file_path, PATHINFO_FILENAME) . ".webp";
            $img2 = new Imagick($file_path);
            if (
                (($img2->getImageColorspace() != IMagick::COLORSPACE_SRGB) || ($img2->getImageDepth() > 8))
                &&
                $img2->getImageColorspace() != IMagick::COLORSPACE_GRAY
            ) {
                $img2->transformimagecolorspace(IMagick::COLORSPACE_RGB);
                $img2->setImageDepth(8);
            }
            resize_img($width, $height, $img2, $crop);
            $img2->setImageFormat("webp");
            $img2->setOption("webp:method", "6");
            $img2->setImageCompressionQuality(50);

            $img->stripImage();
            $img->writeImage($file_path);
            $img->clear();
            $img->destroy();

            $img2->stripImage();
            $img2->writeImage($file_path_webp);
            $img2->clear();
            $img2->destroy();
        }
    }

    /**
     * @param int $preferred_width
     * @param int $preferred_height
     * @param Imagick $img
     * @param bool $crop
     * @throws ImagickException
     */
    function resize_img(int $preferred_width, int $preferred_height, Imagick $img, bool $crop = true): void
    {
        $i_width = $img->getImageWidth();
        $i_height = $img->getImageHeight();

        if (($i_height < $preferred_height) && ($i_width < $preferred_width)) {
            $ratio_hg = $preferred_height / $i_height;
            $ratio_wd = $preferred_width / $i_width;
            $ratio = max($ratio_hg, $ratio_wd);
            $width = $preferred_width / $ratio;
            $height = $preferred_height / $ratio;
        } else {
            $width = $preferred_width;
            $height = $preferred_height;
        }

        if ($width <= $height) {
            $new_width = $width;
            $new_height = floor($i_height / ($i_width / $width));
        } else {
            $new_height = $height;
            $new_width = floor($i_width / ($i_height / $height));
        }

        $img->scaleImage($new_width, $new_height, $crop);
        $new_x = ($new_width - $width) / 2;
        $new_y = ($new_height - $height) / 2;
        //echo "need: {$preferred_width}x$preferred_height, need_edited: {$width}x$height, image: {$i_width}x$i_height, result: {$new_width}x$new_height, offset: {$new_x}x$new_y\n<br><br>\n";
        if ($crop) {
            $img->cropImage($width, $height, $new_x, $new_y);
        }
    }
