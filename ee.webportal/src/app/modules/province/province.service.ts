import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {ProvinceMeta} from './province.meta';

@Injectable()
export class ProvinceService extends AbstractCRUDService<ProvinceMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Tỉnh/Thành phố', 'provinces');
  }

}
