import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {StructuredDataTypeMeta} from './structured-data-type.meta';

@Injectable()
export class StructuredDataTypeService extends AbstractCRUDService<StructuredDataTypeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Structured data types', 'structured_data_types');
  }

}
