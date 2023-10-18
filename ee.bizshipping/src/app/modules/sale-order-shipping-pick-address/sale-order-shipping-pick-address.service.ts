import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleOrderShippingPickAddressMeta} from './sale-order-shipping-pick-address.meta';
import { AbstractCRUDService, TitleService } from '../../core';

@Injectable()
export class SaleOrderShippingPickAddressService extends AbstractCRUDService<SaleOrderShippingPickAddressMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Địa điểm giao hàng', 'sale_order_shipping_pick_addresses');
  }

}
