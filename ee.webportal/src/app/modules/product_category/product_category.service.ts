import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {Product_categoryMeta} from './product_category.meta';
import {DataResponse} from '../../core/common';
import {catchError, map} from 'rxjs/operators';
import {Observable} from 'rxjs';

@Injectable()
export class Product_categoryService extends AbstractCRUDService<Product_categoryMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Danh mục sản phẩm', 'product_categories');
  }

  changeOrder(item): any {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${item['id']}/change_order`, item));
  }

  up(id: number): Observable<Product_categoryMeta> {
    return this.http.post<DataResponse<Product_categoryMeta>>(`${this.urlRestAPI}/${id}/up`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

  down(id: number): Observable<Product_categoryMeta> {
    return this.http.post<DataResponse<Product_categoryMeta>>(`${this.urlRestAPI}/${id}/down`, {})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }
}
