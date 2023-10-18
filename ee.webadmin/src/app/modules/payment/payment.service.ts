import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {TitleService} from '../../core/services';
import {ToasterService} from 'angular2-toaster';
import {PaymentMeta} from './payment.meta';
import {environment} from '../../../environments/environment';
import { DataResponse } from '../../core/common';

@Injectable()
export class PaymentService extends AbstractCRUDService<PaymentMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'payment', 'payment_types');
  }
}
