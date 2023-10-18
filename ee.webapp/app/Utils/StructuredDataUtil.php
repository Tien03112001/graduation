<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 9/28/2017
 * Time: 3:55 PM
 */

namespace App\Utils;


use App\Models\AppSetting;
use App\Models\FrequentlyAskedQuestion;
use App\Models\Post;
use App\Models\Product;
use App\Models\StructureDataType;
use Illuminate\Support\Facades\Cache;

class StructuredDataUtil
{
    public static function generate(string $type, $data, $page_url)
    {
        $settings_name = AppSetting::where('name', 'name')->first()->value;
        $structured_data_type = StructureDataType::where('name', $type)->first();
        if (!$structured_data_type) {
            return '';
        }
        $structure_data = json_decode($structured_data_type->data);
        $post = $data;
        if (count($structure_data) > 0) {
            $code = "<script type=\"application/ld+json\">" . "{";
            $temp = null;
            foreach ($structure_data as $dat) {
                if ($dat->type == "text") {
                    $code .= PHP_EOL . "\"" . $dat->name . "\":" . " \"" . ($dat->value ?? $dat->default) . "\",";
                } elseif ($dat->type == 'array') {
                    $code .= PHP_EOL . "\"" . $dat->name . "\":" . "[" . ($dat->value) . "],";
                } elseif ($dat->type == 'code') {
                    eval($dat->default);
                    $code .= PHP_EOL . "\"" . $dat->name . "\":" . $temp . ",";
                } elseif ($dat->type == "object") {
                    $code .= PHP_EOL . "\"" . $dat->name . "\": {";
                    foreach ($dat->child as $da) {
                        if ($da->type == "text") {
                            $code .= PHP_EOL . "\"" . $da->name . "\":" . " \"" . ($da->value ?? $da->default) . "\",";
                        } elseif ($da->type == 'array') {
                            $code .= PHP_EOL . "\"" . $dat->name . "\":" . ($dat->value) . ",";
                        } elseif ($da->type == 'code') {
                            eval($da->default);
                            $code .= PHP_EOL . "\"" . $da->name . "\":" . $temp . ",";
                        } elseif ($da->type == "object") {
                            $code .= PHP_EOL . "\"" . $da->name . "\": {";
                            foreach ($da->child as $d) {
                                if ($d->type == 'array') {
                                    $code .= PHP_EOL . "\"" . $d->name . "\":" . "[" . ($d->value) . "],";
                                } elseif ($d->type == 'code') {
                                    eval($d->default);
                                    $code .= PHP_EOL . "\"" . $d->name . "\":" . $temp . ",";
                                } else $code .= PHP_EOL . "\"" . $d->name . "\":" . " \"" . ($d->value ?? $d->default) . "\",";
                            }
                            $code = substr($code, 0, -1) . PHP_EOL . "},";
                        }
                    }
                    $code = substr($code, 0, -1) . "},";
                }
            }
            $code = substr($code, 0, -1) . "}" . PHP_EOL . "</script>";
        } else {
            $code = null;
        }
        return $code;
    }

    public static function generatePost(Post $posts)
    {
        $code = array();
        $code['@context'] = 'http://schema.org/';
        $code['@type'] = 'NewsArticle';
        $code['mainEntityOfPage'] = array(
            '@type' => 'WebPage',
            'id' => $posts->full_path,
            'headline' => $posts->name,
            'image' => [$posts->image_full_path],
            'datePublished' => $posts->created_at,
            'dateModified' => $posts->updated_at,
            'author' => array(
                '@type' => 'Person',
                'name' => 'Admin'
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => json_decode(Cache::get('app_settings'), true)['name'] ?? 'Hàng hiệu giá tốt'
            ),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => json_decode(Cache::get('logo'), true)['name'] ?? '/images/logo.png'
            ),
            'description' => $posts->summary ?? HtmlUtil::getSummaryContent($posts->content)
        );
        $code = json_encode($code);
        $code = '<script type="application/ld+json">' . $code . '</script>';
        return $code;
    }

    public static function generateProducts(Product $product)
    {
        $code = array();
        $code['@context'] = 'http://schema.org/';
        $code['@type'] = 'Product';
        $code['name'] = $product['name'];
        $code['sku'] = $product['code'] ? $product['code'] : $product['id'];
        $code['mpn'] = $product['id'];
        $code['description'] = strip_tags($product['summary']) ?? '';
        $code['brand'] = 'Hạt điều nhà Lê';
        $code['offers'] = array(
            '@type' => 'Offer',
            'url' => $product['full_path'],
            'priceCurrency' => 'VND',
            'price' => $product['price'],
            'itemCondition' => 'https://schema.org/UsedCondition',
            'availability' => 'https://schema.org/InStock',
            'priceValidUntil' => date("Y-m-d", strtotime('+1 year')),
            'seller' => array(
                '@type' => 'Organization',
                'name' => json_decode(Cache::get('app_settings'), true)['name'] ?? 'Hàng hiệu giá tốt'
            )
        );
        if (count($product->comments)) {
            $comments = $product->comments;
            $code['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => '4.85',
                'reviewCount' => count($comments)
            );
            $code['review'] = array();
            foreach ($comments as $c) {
                $code['review'][] = array(
                    '@type' => 'Review',
                    'author' => $c['cust_name'],
                    'datePublished' => date('Y-m-d', $c['date_add']),
                    'description' => $c['content'],
                    'reviewRating' => array(
                        "@type" => "Rating",
                        "bestRating" => "5",
                        "ratingValue" => $c['rate'] ? $c['rate'] : 4.85,
                        "worstRating" => "1"
                    )
                );
            }
        }
        $code['image'] = [];
        if ($product['image']) array_push($code['image'], $product['image_full_path']);
        if ($product['image2']) array_push($code['image'], $product['image2_full_path']);
        $code = json_encode($code);

        $codes = '<script type="application/ld+json">' . $code . '</script>';
        return $codes;
    }

    public static function generateCompany(Post $posts)
    {
        $code = '<script type="application/ld+json">' . '{
"@context": "https://schema.org",
"@type": "NewsArticle",
"mainEntityOfPage": {
"@type": "WebPage",
"@id":"' . $posts->full_path . '"},
"headline":"' . $posts->name . '",
"image":[';
        if (isset($posts->image)) {
            $code .= '"' . $posts->image . '"';
        } else {
            $code .= '"' . '/images/logo.png' . '"';
        }
        $code .= '],
"datePublished":"' . $posts->created_at . '",
"dateModified":"' . $posts->created_at . '",
"author": {
"@type": "Person",
"name":"Quản trị viên"},
"publisher": {
"@type": "Organization",
"name":"' . json_decode(Cache::get('app_settings'), true)['name'] . '",
"logo": {
"@type": "ImageObject",
"url":"/images/logo.png"
}},
"description": "' . $posts->summary . '"}' . '</script>';
        return $code;
    }

    public static function generateCategoryProduct($products)
    {
        $code = '<script type="application/ld+json">';
        $codes = array();
        $codes['@context'] = 'http://schema.org/';
        $codes['@type'] = 'ItemList';
        $codes['itemListElement'] = [];
        foreach ($products as $index => $p) {
            $codes['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => array(
                    '@type' => 'Product',
                    'url' => config('app.url'),
                    'name' => $p->name,
                    'image' => [$p->image_full_path],
                    'description' => $p->summary ?? HtmlUtil::getSummaryContent($p->content),
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $p->price,
                        'url' => $p->full_path,
                        'priceCurrency' => json_decode(Cache::get('app_settings'), true)['priceCurrency'] ?? 'VNĐ'
                    ]
                )
            ];
        }
        $code .= json_encode($codes) . '</script>';
        return $code;
    }

    public static function generateFAQ(FrequentlyAskedQuestion $faqs)
    {
        $code = '<script type="application/ld+json">';
        $codes = array();
        $codes['@context'] = 'http://schema.org/';
        $codes['@type'] = 'FAQPage';
        $codes['mainEntity'] = array();
        foreach ($faqs as $f) {
            $codes['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $f->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $f->answer
                ]
            ];
        }
        $code .= json_encode($codes) . '</script>';
        return $code;
    }
}
