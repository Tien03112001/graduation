import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, TitleService} from '../../core';
import { ShippingProvinceMeta } from './shipping-province.meta';

@Injectable()
export class ShippingProvinceService extends AbstractCRUDService<ShippingProvinceMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý kênh', 'shipping_provinces');
  }

}
