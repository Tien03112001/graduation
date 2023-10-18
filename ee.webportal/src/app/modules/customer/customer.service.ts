import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {CustomerMeta} from './customer.meta';

@Injectable()
export class CustomerService extends AbstractCRUDService<CustomerMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Khách hàng', 'customers');
  }

  checkExist(params: any) {
    let parameters: HttpParams = new HttpParams();
    Object.keys(params).forEach(key => {
      let value = params[key];
      parameters = parameters.set(key, value);
    });
    return this.http.get('customers_check', {params: parameters}).catch(err => this.handleErrorRequest(err));
  }

}
