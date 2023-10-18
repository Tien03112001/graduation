import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {FacebookProductCategoryMeta} from './facebook-product-category.meta';
import { Observable } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { AbstractCRUDService } from '../../core/crud';
import { TitleService } from '../../core/services';
import { DataResponse } from '../../core/common';

@Injectable()
export class FacebookProductCategoryService extends AbstractCRUDService<FacebookProductCategoryMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'danh mục sản phẩm', 'facebook_product_categories');
  }

  truncate(item :any): Observable<FacebookProductCategoryMeta> {
    return this.http.post<DataResponse<FacebookProductCategoryMeta>>(`${this.urlRestAPI}/truncate`, {item})
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
