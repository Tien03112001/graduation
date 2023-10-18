<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/05/2018
 * Time: 1:50 CH
 */

namespace App\Utils;


use App\Models\Widget;
use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HtmlUtil
{
    public static function getFirstImage(string $content)
    {
        $dom = new \DOMDocument();
        @$dom->loadHTMl(htmlspecialchars($content));
        $images = $dom->getElementsByTagName('images');
        if ($images->length > 0) {
            return $images->item(0)->getAttribute('src');
        }
        return null;
    }


    /**
     * @param string $html
     * @param array $createdImages
     * @return string
     */
    public static function minify(string $html, array &$createdImages)
    {
        $xml = '<?xml encoding="utf-8" ?>';
        $doc = new \DOMDocument();
        @$doc->loadHTML($xml . $html);

        $images = $doc->getElementsByTagName('img');
        for ($i = 0; $i < $images->length; $i++) {
            $image = $images->item($i);
            $src = $image->getAttribute('src');
            $image->removeAttribute('style');
            $image->removeAttribute('width');
            $image->removeAttribute('height');
            if (Str::startsWith($src, 'data:image/')) {
                try {
                    $alt = $image->getAttribute('alt');
                    if (empty($alt)) {
                        $filename = 'temp';
                    } else {
                        $filename = StringUtil::slugify($alt);
                    }
                    $fileSavedUrl = ImageUtil::save($src, $filename, 'articles');
                    $image->setAttribute('src', $fileSavedUrl);
                    array_push($createdImages, $fileSavedUrl);
                } catch (\Exception $e) {
                    Log::error($e);
                }
            }
        }

        preg_match("#<body>([\s\S]*)</body>#", trim($doc->saveHTML()), $matches);
        return empty($matches) ? '' : $matches[1];
    }

    public static function getSummaryContent($contentHtml)
    {
        $summary = strip_tags(html_entity_decode($contentHtml));
        return mb_substr($summary, 0, 200);
    }

    public static function extractShortText($str, $length = 200, $etc = '...')
    {
        if ($str == '' || !isset($str)) return NULL;
        $str = strip_tags($str);
        if (strlen($str) <= $length) return $str;
        else {
            if (substr($str, $length, 1) != ' ') {
                $str = mb_substr($str, 0, $length, mb_detect_encoding($str));
                if ($pad = strrpos($str, ' ')) {
                    return mb_substr($str, 0, $pad, mb_detect_encoding($str)) . $etc;
                } else return $str . $etc;
            } else {
                return mb_substr($str, 0, $length, mb_detect_encoding($str)) . $etc;
            }
        }
    }

    public static function price_format($price = null)
    {
        if (!is_null($price)) {
            return number_format($price, 0, ',', '.') . 'đ';
        }
        return null;
    }

    public static function widget_replace(string $content)
    {
        $xml = '<?xml encoding="utf-8" ?>';
        $doc = new DOMDocument();
        @$doc->loadHTML($xml . $content);
        $images = $doc->getElementsByTagName('div');
        $data = [];
        for ($i = 0; $i < $images->length; $i++) {
            $image = $images->item($i);
            if ($image->getAttribute('class') == 'widget') {
                $id = $image->getAttribute('id');
                $widget = Widget::query()->find($id)->html;
                $image->nodeValue = $widget;
            }
        }
        preg_match("#<body>([\s\S]*)</body>#", trim($doc->saveHTML()), $matches);
        return empty($matches) ? '' : $matches[1];
    }

    public static function formatted_content($content)
    {
        $html = $content;
        $xml = '<?xml encoding="utf-8" ?>';
        $doc = new \DOMDocument();
        @$doc->loadHTML($xml . $html);

        $images = $doc->getElementsByTagName('images');

        for ($i = 0; $i < $images->length; $i++) {
            $image = $images->item($i);
            $src = $image->getAttribute('src');
            $image->setAttribute('src', asset('default/500x500.jpg'));
            $image->setAttribute('width', '500');
            $image->setAttribute('height', '500');
            $image->setAttribute('data-src', $src);
            $image->setAttribute('class', 'lazy');
            $image->setAttribute('style', 'margin-left:auto;margin-right:auto;display:block;');
        }


        preg_match("#<body>([\s\S]*)</body>#", trim($doc->saveHTML()), $matches);

        return empty($matches) ? '' : $matches[1];
    }

    public static function extractContent($contentHtml)
    {
        if (empty($contentHtml)) {
            return null;
        }
        return strip_tags(html_entity_decode($contentHtml));
    }

}
