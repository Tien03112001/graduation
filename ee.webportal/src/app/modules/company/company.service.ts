import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {CompanyMeta} from './company.meta';

@Injectable()
export class CompanyService extends AbstractCRUDService<CompanyMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thông tin công ty', 'companies');
  }
}
