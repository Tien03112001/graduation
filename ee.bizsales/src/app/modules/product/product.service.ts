import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {ProductMeta} from './product.meta';
import {AbstractCRUDService, TitleService} from '../../core';

@Injectable()
export class ProductService extends AbstractCRUDService<ProductMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý sản phẩm', 'products');
  }

}
