import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {AttributeTypeMeta} from './attribute-type.meta';

@Injectable()
export class AttributeTypeService extends AbstractCRUDService<AttributeTypeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thuộc tính thông dụng', 'attribute_types');
  }

}
