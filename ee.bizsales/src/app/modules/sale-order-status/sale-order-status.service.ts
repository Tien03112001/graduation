import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, TitleService} from '../../core';
import { SaleOrderStatus } from './sale-order-status.meta';
import { environment } from '../../../environments/environment';

@Injectable()
export class SaleOrderStatusService extends AbstractCRUDService<SaleOrderStatus> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý kênh', 'sale_order_statuses');
    this.setApiUrl(environment.definition_api_url);
  }

}
