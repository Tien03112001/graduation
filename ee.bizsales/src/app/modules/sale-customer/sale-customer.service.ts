import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleCustomerMeta} from './sale-customer.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class SaleCustomerService extends AbstractCRUDService<SaleCustomerMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Khách hàng', 'sale_customers');
  }

}
