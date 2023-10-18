import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ShippingStatusMeta} from './shipping-status.meta';
import { Observable } from 'rxjs';
import { DataResponse } from '../../core/common';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class ShippingStatusService extends AbstractCRUDService<ShippingStatusMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Trạng thái ship đơn ', 'shipping_status');
  }
}
