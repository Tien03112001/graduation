import {ShippingProvinceMeta} from '../shipping-province/shipping-province.meta';
import {ShippingWardMeta} from '../shipping-ward/shipping-ward.meta';

export class ShippingDistrictMeta {
  id: number;
  province_id: number;
  province: ShippingProvinceMeta;
  name: string;
  code: string;
  wards: ShippingWardMeta[];
}
