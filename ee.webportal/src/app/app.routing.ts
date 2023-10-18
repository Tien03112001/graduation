export const MODULES_ROUTING = [
  {
    path: '',
    redirectTo: 'dashboard',
    pathMatch: 'full'
  },
  {
    path: 'dashboard',
    loadChildren: 'app/modules/dashboard/dashboard.module#DashboardModule'
  },
  {
    path: 'post-categories',
    loadChildren: 'app/modules/post-category/post-category.module#PostCategoryModule'
  },
  {
    path: 'posts',
    loadChildren: 'app/modules/post/post.module#PostModule'
  },
  {
    path: 'pages',
    loadChildren: 'app/modules/page/page.module#PageModule'
  },
  {
    path: 'settings',
    loadChildren: 'app/modules/setting/setting.module#SettingModule'
  },
  {
    path: 'embed-codes',
    loadChildren: 'app/modules/embed-code/embed-code.module#EmbedCodeModule'
  },
  {
    path: 'post-tags',
    loadChildren: 'app/modules/post-tag/post-tag.module#PostTagModule'
  },
  {
    path: 'widgets',
    loadChildren: 'app/modules/widget/widget.module#WidgetModule'
  },
  {
    path: 'banners',
    loadChildren: 'app/modules/banner-group/banner-group.module#BannerGroupModule'
  },
  {
    path: 'cache',
    loadChildren: 'app/modules/cache/cache.module#CacheModule'
  },
  {
    path: 'menus',
    loadChildren: 'app/modules/menu-position/menu-position.module#MenuPositionModule'
  },
  {
    path: 'galleries',
    loadChildren: 'app/modules/gallery/gallery.module#GalleryModule'
  },
  {
    path: 'images',
    loadChildren: 'app/modules/photo/photo.module#PhotoModule'
  },
  {
    path: 'users',
    loadChildren: 'app/modules/user/user.module#UserModule'
  },
  {
    path: 'profile',
    loadChildren: 'app/modules/profile/profile.module#ProfileModule'
  },
  {
    path: 'languages',
    loadChildren: 'app/modules/language/language.module#LanguageModule'
  },
  {
    path: 'attributes',
    loadChildren: 'app/modules/attribute/attribute.module#AttributeModule'
  },
  {
    path: 'dynamic-tables',
    loadChildren: 'app/modules/dynamic-table/dynamic-table.module#DynamicTableModule'
  },
  {
    path: 'forms',
    loadChildren: 'app/modules/form/form.module#FormModule'
  },
  {
    path: 'form-data',
    loadChildren: 'app/modules/form-data/form-data.module#FormDataModule'
  },
  {
    path: 'companies',
    loadChildren: 'app/modules/company/company.module#CompanyModule'
  },
  {
    path: 'product-categories',
    loadChildren: 'app/modules/product_category/product_category.module#Product_categoryModule'
  },
  {
    path: 'products',
    loadChildren: 'app/modules/product/product.module#ProductModule'
  },
  {
    path: 'product-tags',
    loadChildren: 'app/modules/product-tag/product-tag.module#ProductTagModule'
  },
  {
    path: 'job-postings',
    loadChildren: 'app/modules/job-posting/job-posting.module#JobPostingModule'
  },
  {
    path: 'freq-questions',
    loadChildren: 'app/modules/freq-question/freq-question.module#FreqQuestionModule'
  },

  {
    path: 'supports',
    loadChildren: 'app/modules/support-ticket/support-ticket.module#SupportTicketModule'
  },
  {
    path: 'seo-meta',
    loadChildren: 'app/modules/seo-meta/seo-meta.module#SeoMetaModule'
  },
  {
    path: 'manual-documents',
    loadChildren: 'app/modules/manual-document/manual-document.module#ManualDocumentModule'
  },
  {
    path: 'vouchers',
    loadChildren: 'app/modules/voucher/voucher.module#VoucherModule'
  },
  {
    path: 'promotions',
    loadChildren: 'app/modules/promotion/promotion.module#PromotionModule'
  },
  {
    path: 'provinces',
    loadChildren: 'app/modules/province/province.module#ProvinceModule'
  },
  {
    path: 'districts',
    loadChildren: 'app/modules/district/district.module#DistrictModule'
  },
  {
    path: 'wards',
    loadChildren: 'app/modules/ward/ward.module#WardModule'
  },
  {
    path: 'shipping_fee_tables',
    loadChildren: 'app/modules/shipping-fee-table/shipping-fee-table.module#ShippingFeeTableModule'
  },
  {
    path: 'structured_data_type',
    loadChildren: 'app/modules/structured-data-type/structured-data-type.module#StructuredDataTypeModule'
  },
  {
    path: 'facebook_products',
    loadChildren: 'app/modules/facebook-product/facebook-product.module#FacebookProductModule'
  },
  {
    path: 'facebook_product_categories',
    loadChildren: 'app/modules/facebook-product-category/facebook-product-category.module#FacebookProductCategoryModule'
  },
];
