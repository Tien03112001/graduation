import {ShippingDistrictMeta} from '../shipping-district/shipping-district.meta';

export class ShippingWardMeta {
  id: number;
  district_id: number;
  name: string;
  code: string;
  district: ShippingDistrictMeta;
}
