import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {ShippingDistrictMeta} from './shipping-district.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class ShippingDistrictService extends AbstractCRUDService<ShippingDistrictMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quận/Huyện', 'shipping_districts');
  }

}
