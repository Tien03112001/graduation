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
    path: 'products',
    loadChildren: 'app/modules/product/product.module#ProductModule'
  }
];
