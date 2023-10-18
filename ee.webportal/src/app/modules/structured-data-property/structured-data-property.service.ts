import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {StructuredDataPropertyMeta} from './structured-data-property.meta';

@Injectable()
export class StructuredDataPropertyService extends AbstractCRUDService<StructuredDataPropertyMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Structured data properties', 'structured_data_properties');
  }

}
