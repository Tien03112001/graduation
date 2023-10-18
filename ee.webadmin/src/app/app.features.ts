export const APP_FEATURES = [
  {
    name: 'Bảng điều khiển',
    icon: 'fa fa-dashboard',
    link: '/dashboard'
  },
  {
    name: 'Thông tin công ty',
    icon: 'fa fa-building-o',
    children: [
      {
        name: 'Thông tin công ty',
        icon: 'fa fa-building',
        link: '/companies'
      },
      {
        name: 'Bảng công ty',
        icon: 'fa fa-table',
        link: '/dynamic-table-admin'
      },
      {
        name: 'Dữ liệu công ty',
        icon: 'fa fa-map-pin',
        link: '/dynamic-tables'
      }
    ]
  },
  {
    name: 'Quản lý giao vận',
    icon: 'fa fa-truck',
    children: [
      {
        name: 'Quản lý kênh order',
        icon: 'fa fa-sitemap',
        link: '/chanel'
      },
      {
        name: 'Hình thức thanh toán',
        icon: 'fa fa-money',
        link: '/payment'
      },
      {
        name: 'Trạng thái đơn hàng',
        icon: 'fa fa-info-circle',
        link: '/status'
      },
      {
        name: 'Đối tác vận chuyển',
        icon: 'fa fa-handshake-o',
        link: '/shipping_units'
      },
      {
        name: 'Trạng thái ship hàng',
        icon: 'fa fa-paper-plane',
        link: '/shipping_status'
      },
      {
        name: 'Phương thức thanh toán',
        icon: 'fa fa-usd',
        link: '/payment_methods'
      },
    ]
  },
  {
    name: 'Giao diện',
    icon: 'fa fa-file-text-o',
    children: [
      {
        name: 'Page',
        icon: 'fa fa-file-text-o',
        link: '/pages',
      },
      {
        name: 'Widget',
        icon: 'fa fa-square',
        link: '/widgets'
      },
      {
        name: 'Quản lý Menu',
        icon: 'fa fa-bars',
        link: '/menus'
      },
    ]
  },
  {
    name: 'Quản lý dữ liệu form',
    icon: 'fa fa-user',
    children: [
      {
        name: 'Form',
        icon: 'fa fa-user',
        link: '/forms'
      },
      {
        name: 'Quản lý dữ liệu form',
        icon: 'fa fa-user',
        link: '/form-data',
      }
    ]
  },

  {
    name: 'Cài đặt',
    icon: 'fa fa-cogs',
    children: [
      {
        name: 'Cấu hình chung',
        icon: 'fa fa-cogs',
        link: '/settings',
      },
      {
        name: 'Cấu hình ngôn ngữ',
        icon: 'fa fa-language',
        link: '/languages'
      },
      {
        name: 'Mã nhúng',
        icon: 'fa fa-code',
        link: '/embed-codes'
      }
    ]
  },


  {
    name: 'Quản trị hệ thống',
    icon: 'fa fa-users',
    children: [
      {
        name: 'Quản lý người dùng',
        icon: 'fa fa-user',
        link: '/users'
      },
      {
        name: 'Quản lý tư vấn viên',
        icon: 'fa fa-user',
        link: '/agents'
      },
      {
        name: 'Quản lý role',
        icon: 'fa fa-users',
        link: '/roles'
      },
      {
        name: 'Quản lý fanpage',
        icon: 'fa fa-dashboard',
        link: '/fanpages'
      }
    ]
  },
];
