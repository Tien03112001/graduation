import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {AttributeMeta} from './attribute.meta';

@Injectable()
export class AttributeService extends AbstractCRUDService<AttributeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thuộc tính sản phẩm', 'attributes');
  }
}
