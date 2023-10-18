import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {AbstractCRUDService, TitleService} from '../../core';
import { PaymentTypeMeta } from './payment-type.meta';
import {environment} from '../../../environments/environment';

@Injectable()
export class PaymentTypeService extends AbstractCRUDService<PaymentTypeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý kênh', '/payment_types');
    this.setApiUrl(environment.definition_api_url);
  }

}
