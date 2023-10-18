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
    path: 'sale-orders',
    loadChildren: 'app/modules/sale-order/sale-order.module#SaleOrderModule'
  },
  {
    path: 'products',
    loadChildren: 'app/modules/product/product.module#ProductModule'
  },
  {
    path: 'orders',
    loadChildren: 'app/modules/order/order.module#OrderModule'
  },
  {
    path: 'conversation',
    loadChildren: 'app/modules/conversation/conversation.module#ConversationModule',
  },
];
