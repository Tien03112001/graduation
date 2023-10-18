import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {TitleService} from '../../core/services';
import {ToasterService} from 'angular2-toaster';
import {PaymentmtMeta} from './paymentmt.meta';
import {environment} from '../../../environments/environment';
import { DataResponse } from '../../core/common';

@Injectable()
export class PaymentmtService extends AbstractCRUDService<PaymentmtMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'paymentmt', 'payment_methods');
  }
}
