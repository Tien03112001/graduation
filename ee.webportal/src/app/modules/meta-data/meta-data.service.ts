import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {MetaDataMeta} from './meta-data.meta';

@Injectable()
export class MetaDataService extends AbstractCRUDService<MetaDataMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'SEO Meta', 'meta_data');
  }
}
