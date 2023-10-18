import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {TitleService} from '../../core/services';
import {ToasterService} from 'angular2-toaster';
import {ShippingUnitsMeta} from './shipping_units.meta';
import { Observable } from 'rxjs';
import {environment} from '../../../environments/environment';
import { DataResponse } from '../../core/common';
import {PaymentMethodMeta} from '../payment-method/payment-method.meta';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ShippingUnitsService extends AbstractCRUDService<ShippingUnitsMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'đối tác vận chuyển', 'shipping_units');
  }
  sync(id: number): Observable<ShippingUnitsMeta> {
    return this.http.post<DataResponse<ShippingUnitsMeta>>(`${this.urlRestAPI}/${id}/synchronized`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
  createUnitPartner(item: Object): Observable<ShippingUnitsMeta> {
    return this.http.post<DataResponse<ShippingUnitsMeta>>(`${this.urlRestAPI}/createUnitPartner`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
