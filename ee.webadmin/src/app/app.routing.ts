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
    path: 'pages',
    loadChildren: 'app/modules/page/page.module#PageModule'
  },
  {
    path: 'settings',
    loadChildren: 'app/modules/setting/setting.module#SettingModule'
  },
  {
    path: 'widgets',
    loadChildren: 'app/modules/widget/widget.module#WidgetModule'
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
    path: 'dynamic-table-admin',
    loadChildren: 'app/modules/dynamic-table-admin/dynamic-table-admin.module#DynamicTableAdminModule'
  },
  {
    path: 'agents',
    loadChildren: 'app/modules/agent/agent.module#AgentModule'
  },
  {
    path: 'roles',
    loadChildren: 'app/modules/role/role.module#RoleModule'
  },
  {
    path: 'chanel',
    loadChildren: 'app/modules/chanel/chanel.module#ChanelModule'
  },
  {
    path: 'payment',
    loadChildren: 'app/modules/payment/payment.module#PaymentModule'
  },
  {
    path: 'status',
    loadChildren: 'app/modules/status/status.module#StatusModule'
  },
  {
    path: 'payment_methods',
    loadChildren: 'app/modules/payment-method/payment-method.module#PaymentMethodModule'
  },
  {
    path: 'shipping_units',
    loadChildren: 'app/modules/shipping_units/shipping_units.module#ShippingUnitsModule'
  },
  {
    path: 'shipping_status',
    loadChildren: 'app/modules/shipping-status/shipping-status.module#ShippingStatusModule'
  },
  {
    path: 'fanpages',
    loadChildren: 'app/modules/fanpage/fanpage.module#FanpageModule'
  },
];
