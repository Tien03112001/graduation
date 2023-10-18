import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ShippingStoreMeta} from './shipping-store.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {PostMeta} from '../post/post.meta';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ShippingStoreService extends AbstractCRUDService<ShippingStoreMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Bài viết', 'shipping-stores');
  }

  assignTags(id: number, ids: number[]): Observable<ShippingStoreMeta> {
    return this.toPipe(this.http.post<DataResponse<ShippingStoreMeta>>(`${this.urlRestAPI}/${id}/sync_tags?tag_ids=${ids}`, {ids: ids.join()}));
  }

  getMeta(id: number): Observable<ShippingStoreMeta> {
    return this.toPipe(this.http.get<DataResponse<ShippingStoreMeta>>(`${this.urlRestAPI}/${id}/meta`));
  }

  delete(item: any) {
    return this.toPipe(this.http.put<DataResponse<any>>(`delete_shipping-store`, {item: item}));
  }

  changeOrder(item) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${item['id']}/change_order`, item));
  }

  up(id: number): Observable<ShippingStoreMeta> {
    return this.http.post<DataResponse<ShippingStoreMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<ShippingStoreMeta> {
    return this.http.post<DataResponse<ShippingStoreMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
