import {Injectable} from '@angular/core';
import {HttpClient, HttpParams} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleOrderMeta} from './sale-order.meta';
import {catchError, map} from 'rxjs/operators';
import { AbstractCRUDService, DataResponse, TitleService } from '../../core';

@Injectable()
export class SaleOrderService extends AbstractCRUDService<SaleOrderMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Đơn hàng', 'sale_orders');
  }

  return(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/return`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  receiveRefund(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/receiveRefund`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  refund(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/refund`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  ship(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/ship`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  done(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/done`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  note(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/note`,item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  cancel(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/cancel`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  reUpdate(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/reUpdate`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  printShippingBill(ids: number[]) {
    return this.http.get<DataResponse<any>>(`${this.urlRestAPI}/printShippingBill`, {params: {order_ids: ids.join(',')}})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
