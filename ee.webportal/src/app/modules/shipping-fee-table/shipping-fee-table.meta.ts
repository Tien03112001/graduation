import {ProvinceMeta} from '../province/province.meta';
import {DistrictMeta} from '../district/district.meta';
import {WardMeta} from '../ward/ward.meta';
import {PromotionMeta} from '../promotion/promotion.meta';


export class ShippingFeeTableMeta {
  id: number;
  province_id: number;
  province: ProvinceMeta;
  district_id: number;
  district: DistrictMeta;
  ward_id: number;
  ward: WardMeta;
  fee: number;
  promotions: PromotionMeta;
}
