import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleOrderHistoryMeta} from './sale-order-history.meta';
import { AbstractCRUDService, TitleService } from '../../core';

@Injectable()
export class SaleOrderHistoryService extends AbstractCRUDService<SaleOrderHistoryMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Lịch sử đơn hàng', 'sale_order_histories');
  }

}
