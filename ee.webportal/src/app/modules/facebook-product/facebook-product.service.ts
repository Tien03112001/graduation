import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {FacebookProductMeta} from './facebook-product.meta';
import {AbstractCRUDService} from '../../core/crud';
import { TitleService } from '../../core/services';
import { Observable } from 'rxjs';
import { DataResponse } from '../../core/common';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class FacebookProductService extends AbstractCRUDService<FacebookProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'sản phẩm', 'facebook_products');
  }

  createXML(): Observable<FacebookProductMeta> {
    return this.http.get<DataResponse<FacebookProductMeta>>(`${this.urlRestAPI}/xml`)
      .pipe(catchError(this.handleErrorRequest.bind(this)), map(res => res['data']));
  }

}
