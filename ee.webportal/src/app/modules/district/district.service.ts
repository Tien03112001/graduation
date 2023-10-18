import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {DistrictMeta} from './district.meta';

@Injectable()
export class DistrictService extends AbstractCRUDService<DistrictMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Quận/Huyện', 'districts');
  }
}
