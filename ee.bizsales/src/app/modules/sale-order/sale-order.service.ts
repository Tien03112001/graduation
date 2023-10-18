import {Injectable} from '@angular/core';
import {HttpClient, HttpHeaders} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {SaleOrderMeta} from './sale-order.meta';
import {catchError, map} from 'rxjs/operators';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';
import { Observable } from 'rxjs';

@Injectable()
export class SaleOrderService extends AbstractCRUDService<SaleOrderMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'đơn hàng', 'sale_orders');
  }

  verifyWithImage(item: SaleOrderMeta): Observable<any> {
    const formData = new FormData();
    Object.keys(item).forEach(key => formData.append(key, item[key]));
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item['id']}/verify`, formData, {headers: new HttpHeaders({uploadFile: 'true'})})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  draftverify(item: SaleOrderMeta): Observable<any> {
    const formData = new FormData();
    Object.keys(item).forEach(key => formData.append(key, item[key]));
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item['id']}/draftverify`, formData, {headers: new HttpHeaders({uploadFile: 'true'})})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  confirm(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/confirm`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  cancel(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/cancel`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  complain(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/complain`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  swap(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/swap`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  refund(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/refund`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  refundAmount(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/refundAmount`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  complete(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/complete`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  createSwappingOrder(item: SaleOrderMeta, data: any) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/createSwappingOrder`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  recreate(item: SaleOrderMeta) {
    return this.http.post<DataResponse<SaleOrderMeta>>(`${this.urlRestAPI}/${item.id}/recreate`, item)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
