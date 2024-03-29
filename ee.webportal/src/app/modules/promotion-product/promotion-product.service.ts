import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {PromotionProductMeta} from './promotion-product.meta';
import {Observable} from 'rxjs/Observable';
import {DataResponse} from '../../core/common';
import {ProductMeta} from '../product/product.meta';
import {catchError, map} from 'rxjs/operators';

@Injectable()
export class PromotionProductService extends AbstractCRUDService<PromotionProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'chương trình khuyến mại', 'promotions');
  }
  assignProducts(id: number, ids: number): Observable<ProductMeta> {
    return this.toPipe(this.http.post<DataResponse<PromotionProductMeta>>(`${this.urlRestAPI}/${id}/attach_products?product_ids=${ids}`, {id: ids}));
  }
  detachProduct(promotion_id:number,body: any,) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/${promotion_id}/detach_products`, body));
  }
  css(object: PromotionProductMeta): Observable<PromotionProductMeta> {
    return this.toPipe(this.http.put<DataResponse<PromotionProductMeta>>(`${this.urlRestAPI}/${object['id']}/css`, {css: object['css']}));
  }

  script(object: PromotionProductMeta): Observable<PromotionProductMeta> {
    return this.toPipe(this.http.put<DataResponse<PromotionProductMeta>>(`${this.urlRestAPI}/${object['id']}/script`, {script: object['script']}));
  }
}
