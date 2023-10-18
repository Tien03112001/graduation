import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService, TitleService} from '../../core';
import {ToasterService} from 'angular2-toaster';
import {CustomerContactMeta} from './customer-contact.meta';
import {environment} from '../../../environments/environment';

@Injectable()
export class CustomerContactService extends AbstractCRUDService<CustomerContactMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Tài khoản', 'customer-contacts');
    this.setApiUrl(environment.api_url);
  }

}
