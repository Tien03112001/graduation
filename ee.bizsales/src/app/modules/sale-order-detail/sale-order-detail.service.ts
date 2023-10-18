import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleOrderDetailMeta} from './sale-order-detail.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class SaleOrderDetailService extends AbstractCRUDService<SaleOrderDetailMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Chi tiết đơn hàng', 'sale_order_details');
  }

}
