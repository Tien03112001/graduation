import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService, TitleService} from '../../core';
import {ToasterService} from 'angular2-toaster';
import {OrderMeta} from './order.meta';
import {environment} from '../../../environments/environment';

@Injectable()
export class OrderService extends AbstractCRUDService<OrderMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'đơn hàng', 'orders');
    this.setApiUrl(environment.api_url);
  }

}
