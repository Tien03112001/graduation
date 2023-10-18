import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {RelatedProductMeta} from './related-product.meta';

@Injectable()
export class RelatedProductService extends AbstractCRUDService<RelatedProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Related Products', 'related_products');
  }

}
