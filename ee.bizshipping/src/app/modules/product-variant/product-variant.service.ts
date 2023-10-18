import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {ProductVariantMeta} from './product-variant.meta';
import { AbstractCRUDService, TitleService } from '../../core';

@Injectable()
export class ProductVariantService extends AbstractCRUDService<ProductVariantMeta> {
  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quản lý sản phẩm', 'product_variants');
  }

}
