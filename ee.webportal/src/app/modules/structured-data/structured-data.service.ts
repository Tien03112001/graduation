import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {StructuredDataMeta} from './structured-data.meta';

@Injectable()
export class StructuredDataService extends AbstractCRUDService<StructuredDataMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Structured Data', 'structured_datas');
  }

}
