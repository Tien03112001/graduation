<?php

namespace App\Console\Commands;

use App\Common\Enum\StatusEnum;
use App\Common\WhereClause;
use App\Models\FProduct;
use App\Models\GProduct;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTag;
use App\Repository\FProductRepositoryInterface;
use App\Repository\GProductRepositoryInterface;
use App\Repository\PageRepositoryInterface;
use App\Repository\PostCategoryRepositoryInterface;
use App\Repository\PostRepositoryInterface;
use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;
use App\Repository\ProductTagRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class AutoGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:gen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinh file tu dong: site_map.xml, product catalog, product shopping';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start generate xml');
        $this->generateSiteMap();
        $this->generateFacebookCatalog();
        $this->generateGoogleShopping();
        $this->info('Completed generate xml');
        return 0;
    }

    private function generateSiteMap()
    {
        $urls = [];

        $repository = App::make(PageRepositoryInterface::class);
        if ($repository instanceof PageRepositoryInterface) {
            $pages = $repository->get([WhereClause::query('published', StatusEnum::ACTIVE)], 'id:asc');
            foreach ($pages as $page) {
                if ($page instanceof Page) {
                    if ($page->slug == 'home-page') {
                        array_push($urls, [
                            'loc' => config('app.url'),
                            'lastmod' => Carbon::parse($page->updated_at)->toDateString()
                        ]);
                    } else {
                        array_push($urls, [
                            'loc' => $page->full_path,
                            'lastmod' => Carbon::parse($page->updated_at)->toDateString()
                        ]);
                    }
                }
            }
        }

        $repository = App::make(PostCategoryRepositoryInterface::class);
        if ($repository instanceof PostCategoryRepositoryInterface) {
            $models = $repository->get([WhereClause::query('published', StatusEnum::ACTIVE)], 'order:asc,id:asc');
            foreach ($models as $model) {
                if ($model instanceof PostCategory) {
                    array_push($urls, [
                        'loc' => $model->full_path,
                        'lastmod' => Carbon::parse($model->updated_at)->toDateString()
                    ]);
                }
            }
        }


        $repository = App::make(PostRepositoryInterface::class);
        if ($repository instanceof PostRepositoryInterface) {
            $models = $repository->get([WhereClause::query('published', StatusEnum::ACTIVE)], 'order:asc,id:asc');
            foreach ($models as $model) {
                if ($model instanceof Post) {
                    array_push($urls, [
                        'loc' => $model->full_path,
                        'lastmod' => Carbon::parse($model->updated_at)->toDateString()
                    ]);
                    array_push($urls, [
                        'loc' => $model->amp_path,
                        'lastmod' => Carbon::parse($model->updated_at)->toDateString()
                    ]);
                }
            }
        }

        $repository = App::make(ProductCategoryRepositoryInterface::class);
        if ($repository instanceof ProductCategoryRepositoryInterface) {
            $models = $repository->get([WhereClause::query('published', StatusEnum::ACTIVE)], 'order:asc,id:asc');
            foreach ($models as $model) {
                if ($model instanceof ProductCategory) {
                    array_push($urls, [
                        'loc' => $model->full_path,
                        'lastmod' => Carbon::parse($model->updated_at)->toDateString()
                    ]);
                }
            }
        }

        $repository = App::make(ProductTagRepositoryInterface::class);
        if ($repository instanceof ProductTagRepositoryInterface) {
            $models = $repository->get([], 'id:asc');
            foreach ($models as $model) {
                if ($model instanceof ProductTag) {
                    array_push($urls, [
                        'loc' => $model->full_path,
                        'lastmod' => Carbon::parse($model->updated_at)->toDateString()
                    ]);
                }
            }
        }

        $repository = App::make(ProductRepositoryInterface::class);
        if ($repository instanceof ProductRepositoryInterface) {
            $models = $repository->get([WhereClause::query('published', StatusEnum::ACTIVE)], 'order:asc,id:asc');
            foreach ($models as $model) {
                if ($model instanceof Product) {
                    array_push($urls, [
                        'loc' => $model->full_path,
                        'lastmod' => Carbon::parse($model->updated_at)->toDateString()
                    ]);
                }
            }
        }

        file_put_contents(public_path('xml/sitemap.xml'), view('templates.sitemap', compact('urls'))->render());
    }

    private function generateFacebookCatalog()
    {
        $rows = [];

        $repository = App::make(FProductRepositoryInterface::class);
        if ($repository instanceof FProductRepositoryInterface) {
            $models = $repository->get([WhereClause::query('enable', StatusEnum::ACTIVE)], 'id:asc');
            foreach ($models as $model) {
                if ($model instanceof FProduct) {
                    $product = $model->toArray();
                    unset($product['product_id']);
                    unset($product['enable']);
                    unset($product['fid']);
                    $product['id'] = $model->fid;
                    foreach ($product as $key => $value) {
                        if (empty($value)) {
                            unset($product[$key]);
                        }
                    }
                    array_push($rows, $product);
                }
            }
        }

        file_put_contents(public_path('xml/facebook_catalog.xml'), view('templates.facebook_catalog', compact('rows'))->render());
    }

    private function generateGoogleShopping()
    {
        $rows = [];

        $repository = App::make(GProductRepositoryInterface::class);
        if ($repository instanceof GProductRepositoryInterface) {
            $models = $repository->get([WhereClause::query('enable', StatusEnum::ACTIVE)], 'id:asc');
            foreach ($models as $model) {
                if ($model instanceof GProduct) {
                    $product = $model->toArray();
                    unset($product['product_id']);
                    unset($product['enable']);
                    unset($product['gid']);
                    $product['id'] = $model->gid;
                    foreach ($product as $key => $value) {
                        if (empty($value)) {
                            unset($product[$key]);
                        }
                    }
                    array_push($rows, $product);
                }
            }
        }

        file_put_contents(public_path('xml/google_shopping.xml'), view('templates.google_shopping', compact('rows'))->render());
    }
}
