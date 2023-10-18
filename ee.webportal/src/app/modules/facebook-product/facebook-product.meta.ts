import { FacebookProductCategoryMeta } from "../facebook-product-category/facebook-product-category.meta";

export class FacebookProductMeta {
  id: number;
  product_id: string;
  enable:boolean;
  fid: string;
  title: string;
  description: string;
  availability: string;
  condition: string;
  price: number;
  link: string;
  image_link: string;
  brand: string;
  quantity_to_sell_on_facebook: number;
  fb_product_category: string;
  size: string;
  sale_price: string;
  sale_price_effective_date: string;
  item_group_id: string;
  status: string;
  additional_image_link: string; 
  color: string;
  gender: string;
  age_group: string;
  material: string;
  pattern: string;
  height: string;
  length: string;
  width: string;
  finish: string;
  volume: string;
  scent: string;
  decor_style: string;
  gemstone: string;
  ingredients: string;
  rich_text_description: string;
  custom_label_0: string;
  custom_label_1: string;
  custom_label_2: string;
  custom_label_3: string;
  custom_label_4: string;
  category: FacebookProductCategoryMeta;
}