import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleOrderShippingMeta} from './sale-order-shipping.meta';
import {Observable} from 'rxjs/Observable';
import {catchError, map} from 'rxjs/operators';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';

@Injectable()
export class SaleOrderShippingService extends AbstractCRUDService<SaleOrderShippingMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Vận đơn', 'sale_order_shippings');
  }

  info(id: number): Observable<any> {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/${id}/info`)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
