import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ShippingFeeTableMeta} from './shipping-fee-table.meta';

@Injectable()
export class ShippingFeeTableService extends AbstractCRUDService<ShippingFeeTableMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Phí ship', 'shipping_fee_tables');
  }
}
