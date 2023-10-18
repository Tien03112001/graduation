import {ShippingDistrictMeta} from '../shipping-district/shipping-district.meta';

export class ShippingProvinceMeta {
  id: number;
  name: string;
  code: string;
  districts: ShippingDistrictMeta[];
}
