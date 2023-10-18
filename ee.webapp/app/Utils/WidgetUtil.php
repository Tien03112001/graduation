<?php
/**
 * Created by PhpStorm.
 * User: BaoHoang
 * Date: 5/26/2022
 * Time: 12:15
 */

namespace App\Utils;


use App\Models\DynamicTableRow;
use App\Models\Post;
use App\Models\Widget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class WidgetUtil
{

    public static function render(Widget $widget)
    {
        $path = Config::get('view.paths.0');
        $viewFilename = $widget->name . '-' . $widget->id . "-" . hash('sha1', $widget->updated_at);
        $fullFilename = $path . "/cache/" . $viewFilename . ".blade.php";
        if (!file_exists($fullFilename)) {
            file_put_contents($fullFilename, html_entity_decode($widget->html));
        }
        $params = self::getViewData($widget->html);
        return view('cache.' . $viewFilename, $params)->render();
    }

    private static function getViewData($html)
    {
        $params = [];
        $xml = '<?xml encoding="utf-8" ?>';
        $doc = new \DOMDocument();
        @$doc->loadHTML($xml . $html);

        $divs = $doc->getElementsByTagName('dynamic');

        for ($i = 0; $i < $divs->length; $i++) {
            $div = $divs->item($i);
            $tableName = $div->getAttribute('name');
            $limit = $div->getAttribute('limit');
            $search = $div->getAttribute('search');
            $orderBy = $div->getAttribute('orderBy');

            $query = (new DynamicTableRow())
                ->whereHas('table', function (Builder $q) use ($tableName) {
                    $q->where('name', $tableName);
                })
                ->when(isset($search), function (Builder $q) use ($search) {
                    return $q->whereHas('cells', function (Builder $q) use ($search) {
                        $q->where('cell_value', 'like', '%' . $search . '%');
                    });
                })
                ->limit($limit)
                ->with(['cells.column']);
            if (!empty($orderBy)) {
                $query = $query->orderBy(...preg_split('/\:/', $orderBy, 1));
            }
            $data = $query->get();

            $rows = [];
            foreach ($data as $r) {
                $row = new \stdClass();
                foreach ($r->cells as $cell) {
                    $row->{$cell->column->name} = $cell->cell_value;
                }
                array_push($rows, $row);
            }


            $params[preg_replace('/\W+/', '_', Str::lower($tableName))] = $rows;
        }

        $divs = $doc->getElementsByTagName('post');
        for ($i = 0; $i < $divs->length; $i++) {
            $div = $divs->item($i);
            $name = $div->getAttribute('name') ?? '';
            $limit = $div->getAttribute('limit');
            $search = $div->getAttribute('search');
            $categoryName = $div->getAttribute('categoryName');
            $orderBy = $div->getAttribute('orderBy');

            $query = (new Post())
                ->when(!empty($search), function (Builder $q) use ($search) {
                    return $q->where('name', 'like', '%' . $search . '%');
                })
                ->when(!empty($categoryName), function (Builder $q) use ($categoryName) {
                    return $q->whereHas('category', function ($q1) use ($categoryName) {
                        $q1->whereName($categoryName);
                    });
                })
                ->where('published', 1)
                ->limit($limit)
                ->with(['category', 'meta', 'article', 'tags', 'gallery'])
                ->withCount('comments');

            if (!empty($orderBy)) {
                $query = $query->orderBy(...preg_split('/\:/', $orderBy, 1));
            }
            $params[$name . '_posts'] = $query->get();
        }

        return $params;
    }
}