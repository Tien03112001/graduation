export const APP_FEATURES = [
  {
    name: 'Bảng điều khiển',
    icon: 'fa fa-dashboard',
    link: '/dashboard'
  },
  {
    name: 'Nội dung',
    icon: 'fa fa-list',
    children:
      [
        {
          name: 'Quản lý bài viết',
          icon: 'fa fa-wpforms',
          children: [
            {
              name: 'Danh mục',
              icon: 'fa fa-bars',
              link: '/post-categories'
            },
            {
              name: 'Bài viết',
              icon: 'fa fa-wpforms',
              link: '/posts'
            },
            {
              name: 'Tag',
              icon: 'fa fa-tag',
              link: '/post-tags'
            }
          ],
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
              name: 'Dữ liệu công ty',
              icon: 'fa fa-map-pin',
              link: '/dynamic-tables'
            }
          ]
        },
        {
          name: 'Tin tuyển dụng',
          icon: 'fa fa-suitcase',
          link: '/job-postings'
        }
      ]
  },


  {
    name: 'Giao diện',
    icon: 'fa fa-file-text-o',
    children: [
      {
        name: 'Quản lý form',
        icon: 'fa fa-user',
        children: [
          {
            name: 'Form',
            icon: 'fa fa-user',
            link: '/forms'
          },
          {
            name: 'Quản lý danh sách form',
            icon: 'fa fa-user',
            link: '/form-data',
          }
        ]
      },
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
        name: 'Quản lý Banner',
        icon: 'fa fa-picture-o',
        link: '/banners'
      },
      {
        name: 'Album ảnh',
        icon: 'fa fa-file-image-o',
        link: '/galleries'
      },
      {
        name: 'Upload ảnh',
        icon: 'fa fa-picture-o',
        link: '/images'
      },
      {
        name: 'Quản lý Menu',
        icon: 'fa fa-bars',
        link: '/menus'
      },

    ]
  },
  {
    name: 'Thương mại điện tử',
    icon: 'fa fa-bar-chart',
    children: [
      {
        name: 'Quản lý sản phẩm',
        icon: 'fa fa-wpforms',
        children: [
          {
            name: 'Danh mục',
            icon: 'fa fa-bars',
            link: '/product-categories'
          },
          {
            name: 'Sản phẩm',
            icon: 'fa fa-wpforms',
            link: '/products'
          },
          {
            name: 'Tag',
            icon: 'fa fa-tag',
            link: '/product-tags'
          }
        ],
      },
      {
        name: 'Mã giảm giá',
        icon: 'fa fa-gift',
        link: '/vouchers'
      },
      {
        name: 'Chương trình khuyến mãi',
        icon: 'fa fa-bullhorn',
        link: '/promotions'
      },
      {
        name: 'Phí vận chuyển',
        icon: 'fa fa-motorcycle',
        link: '/shipping_fee_tables'
      },
    ]
  },
  {
    name: 'Cấu hình',
    icon: 'fa fa-cogs',
    children: [
      {
        name: 'Cấu hình chung',
        icon: 'fa fa-cogs',
        link: '/settings',
      },
      {
        name: 'Cache',
        icon: 'fa fa-cogs',
        link: '/cache',
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
    name: 'SEO',
    icon: 'fa fa-google-plus-official',
    children: [
      {
        name: 'Bài viết',
        icon: 'fa fa-wpforms',
        link: '/seo-meta/post',
      },
      {
        name: 'Danh mục bài viết',
        icon: 'fa fa-bars',
        link: '/seo-meta/post-categories'
      },
      {
        name: 'Tag bài viết',
        icon: 'fa fa-tag',
        link: '/seo-meta/post-tag'
      },
      {
        name: 'Sản phẩm',
        icon: 'fa fa-product-hunt',
        link: '/seo-meta/product',
      },
      {
        name: 'Danh mục sản phẩm',
        icon: 'fa fa-address-card-o',
        link: '/seo-meta/product-categories',
      },
      {
        name: 'Tag sản phẩm',
        icon: 'fa fa-tags',
        link: '/seo-meta/product-tag',
      },
      {
        name: 'Page',
        icon: 'fa fa-file-text-o',
        link: '/seo-meta/pages',
      },
      {
        name: 'Facebook Catalog',
        icon: 'fa fa-facebook-official',
        children: [
          {
            name: 'Danh mục',
            icon: 'fa fa-list',
            link: '/facebook_product_categories'
          },
          {
            name: 'Sản phẩm',
            icon: 'fa fa-question-circle',
            link: '/facebook_products',
          }
        ]
      },
      {
        name: 'Structured data type',
        icon: 'fa fa-database',
        link: '/structured_data_type'
      }
    ]
  },
  {
    name: 'Hỗ trợ',
    icon: 'fa fa-question-circle',
    children:
      [
        {
          name: 'Ticket hỗ trợ',
          icon: 'fa fa-question-circle',
          link: '/supports'
        },
        {
          name: 'Hướng dẫn sử dụng',
          icon: 'fa fa-share-alt',
          link: '/manual-documents'
        },
        {
          name: 'Câu hỏi thường gặp',
          icon: 'fa fa-question-circle',
          link: '/freq-questions'
        },
      ]
  },



];
