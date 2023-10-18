import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {ShippingWardMeta} from './shipping-ward.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class ShippingWardService extends AbstractCRUDService<ShippingWardMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Xã/Phường', 'shipping_wards');
  }

}
