<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31/05/2018
 * Time: 1:50 CH
 */

namespace App\Utils;


use App\Models\Article;
use App\Utils\Caches\AppSettingUtil;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class HtmlUtil
{

    /**
     * @param Article $article
     * @return string
     */
    public static function formatted_content(Article $article)
    {
        $html = $article->content;
        $xml = '<?xml encoding="utf-8" ?>';
        $doc = new \DOMDocument();
        @$doc->loadHTML($xml . $html);

        //lazy image
        $images = $doc->getElementsByTagName('img');
        for ($i = 0; $i < $images->length; $i++) {
            $image = $images->item($i);
            $src = $image->getAttribute('src');
            $image->setAttribute('src', CdnUtil::getInstance()->getCompressedImageURL($src));
            $image->setAttribute('data-src', $src);
            $image->setAttribute('class', 'lazy');
            $image->removeAttribute('width');
            $image->removeAttribute('height');
            $image->removeAttribute('hspace');
            $image->removeAttribute('vspace');
            $image->setAttribute('style', 'margin-left:auto;margin-right:auto;display:block;');
        }
        //add table of contents

        preg_match("#<body>([\s\S]*)</body>#", trim($doc->saveHTML()), $matches);

        return empty($matches) ? '' : $matches[1];
    }

    /**
     * Xử lý trả về HTML
     * @param View $view
     * @return null|string|string[]
     */
    public static function optimize(View $view)
    {
        $appSettings = AppSettingUtil::getInstance()->getCachedData([]);
        $doc = new \DOMDocument();
        @$doc->loadHTML($view->render());

        //lazy images
        $images = $doc->getElementsByTagName('img');
        for ($i = 0; $i < $images->length; $i++) {
            $image = $images->item($i);
            $classes = $image->getAttribute('class');
            $isLazy = Str::contains($classes, 'lazy');
            if (!$isLazy) {
                $src = $image->getAttribute('src');
                $image->setAttribute('src', CdnUtil::getInstance()->getCompressedImageURL($src));
                $image->setAttribute('data-src', $src);
                $image->setAttribute('class', $classes . ' lazy');
            }
        }

        //insert GTM code
        if (isset($appSettings['GTM'])) {
            $gtmId = $appSettings['GTM'];
            $gtmHeader = self::createElementFromHTML($doc, "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','$gtmId');</script>");
            self::addToHead($doc, $gtmHeader);
            $gtmBody = self::createElementFromHTML($doc, "<noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=$gtmId\" height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>");
            self::addToBody($doc, $gtmBody);
        }

        return self::sanitize_output($doc->saveHTML());
    }

    private static function createElementFromHTML(\DOMDocument $doc, string $str)
    {
        $d = new \DOMDocument();
        $d->loadHTML($str);
        return $doc->importNode($d->documentElement, true);
    }

    private static function addToBody(\DOMDocument $doc, \DOMNode $node)
    {
        $element = $doc->getElementsByTagName('body');
        $element->item(0)->appendChild($node);
    }

    private static function addToHead(\DOMDocument $doc, \DOMNode $node)
    {
        $element = $doc->getElementsByTagName('head');
        $element->item(0)->appendChild($node);
    }

    /**
     * Chuyển đổi sang AMP
     * @param Article $article
     * @return null|string|string[]
     */
    public static function convertAMP(Article $article)
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($article->content);
        //replace images
        $images = $doc->getElementsByTagName('img');
        while ($images->length > 0) {
            $image = $images->item(0);
            $node = $doc->createElement("amp-img");
            $width = $image->getAttribute('width');
            $height = $image->getAttribute('height');
            $node->setAttribute('src', $image->getAttribute('src'));
            $node->setAttribute('width', empty($width) ? '900' : $width);
            $node->setAttribute('height', empty($height) ? '600' : $height);
            $node->setAttribute('layout', 'responsive');
            $node->setAttribute('alt', $image->getAttribute('alt') ?? '');
            $node->setAttribute('noloading', '');
            $image->parentNode->replaceChild($node, $image);
        }

        //replace attribute span
        $spans = $doc->getElementsByTagName('span');
        $i = 0;
        while ($i < $spans->length) {
            $span = $spans->item($i);
            $attributes = $span->attributes;
            while ($attributes->length) {
                $span->removeAttribute($attributes->item(0)->name);
            }
            $i++;
        }

        preg_match("#<body>([\s\S]*)</body>#", trim($doc->saveHTML()), $matches);
        $content = empty($matches) ? '' : $matches[1];
        $content = html_entity_decode($content);
        return self::sanitize_output($content);
    }

    private static function sanitize_output($buffer)
    {
        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );
        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );
        return preg_replace($search, $replace, $buffer);
    }

    /**
     * Lấy content từ html
     * @param $contentHtml
     * @return null|string
     */
    public static function extractContent($contentHtml)
    {
        if (empty($contentHtml)) {
            return null;
        }
        return strip_tags(html_entity_decode($contentHtml));
    }

    /**
     * Lấy Short Text của nội dung HTML
     * @param $str
     * @param int $length
     * @param string $etc
     * @return null|string
     */
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

}
