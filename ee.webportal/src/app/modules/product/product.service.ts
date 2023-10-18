import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProductMeta} from './product.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class ProductService extends AbstractCRUDService<ProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'sản phẩm', 'products');
  }

  assignTags(id: number, ids: number[]): Observable<ProductMeta> {
    return this.toPipe(this.http.post<DataResponse<ProductMeta>>(`${this.urlRestAPI}/${id}/sync_tags?tag_ids=${ids}`, {ids: ids.join()}));
  }
  loadAvailableProducts(id: number, param: object ): Observable<any> {
    return this.http.post<DataResponse<ProductMeta[]>>(`${this.urlRestAPI}/${id}/loadAvailableProducts`, {param : param })
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  getMeta(id: number): Observable<ProductMeta> {
    return this.toPipe(this.http.get<DataResponse<ProductMeta>>(`${this.urlRestAPI}/${id}/meta`));
  }

  delete(item: any) {
    return this.toPipe(this.http.put<DataResponse<any>>(`delete_product`, {item: item}));
  }

  changeOrder(item) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${item['id']}/change_order`, item));
  }

  up(id: number): Observable<ProductMeta> {
    return this.http.post<DataResponse<ProductMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<ProductMeta> {
    return this.http.post<DataResponse<ProductMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
