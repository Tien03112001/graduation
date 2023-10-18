import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, TitleService} from '../../core';
import {ShippingFeeMeta} from './shipping-fee.meta';

@Injectable()
export class ShippingFeeService extends AbstractCRUDService<ShippingFeeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'phí ship', 'shipping_fees');
  }

}
