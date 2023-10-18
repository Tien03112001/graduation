export const MODULES_ROUTING = [
  {
    path: '',
    redirectTo: 'dashboard',
    pathMatch: 'full'
  },
  {
    path: 'dashboard',
    loadChildren: 'app/modules/dashboard/dashboard.module#DashboardModule',
  },
  {
    path: 'conversation',
    loadChildren: 'app/modules/conversation/conversation.module#ConversationModule',
  },
];
