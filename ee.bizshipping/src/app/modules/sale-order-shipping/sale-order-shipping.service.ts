import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleOrderShippingMeta} from './sale-order-shipping.meta';
import {catchError, map} from 'rxjs/operators';
import {Observable} from 'rxjs/Observable';
import { AbstractCRUDService, DataResponse, TitleService } from '../../core';

@Injectable()
export class SaleOrderShippingService extends AbstractCRUDService<SaleOrderShippingMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Vận đơn', 'sale_order_shippings');
  }

  printBill(id: any) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/printBill`, {params: {}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  recreate(id: number): Observable<SaleOrderShippingMeta> {
    return this.http.post<DataResponse<SaleOrderShippingMeta>>(`${this.urlRestAPI}/${id}/recreate`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }



}
