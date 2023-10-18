import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {OrderMeta} from './order.meta';
import {catchError, map} from 'rxjs/operators';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';

@Injectable()
export class OrderService extends AbstractCRUDService<OrderMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'đơn hàng trên web', 'orders');
  }

  verify(item: OrderMeta) {
    const formData = new FormData();
    Object.keys(item).forEach(key => formData.append(key, item[key]));
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item['id']}/verify`, formData, {headers: new HttpHeaders({uploadFile: 'true'})})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  confirm(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/confirm`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  cancel(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/cancel`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  complain(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/complain`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  swap(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/swap`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  refund(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/refund`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  refundAmount(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/refundAmount`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  complete(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/complete`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  createSwappingOrder(item: OrderMeta, data: any) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/createSwappingOrder`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  recreate(item: OrderMeta) {
    return this.http.post<DataResponse<OrderMeta>>(`${this.urlRestAPI}/${item.id}/recreate`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
