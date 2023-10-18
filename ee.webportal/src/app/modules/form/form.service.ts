import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {FormMeta} from './form.meta';

@Injectable()
export class FormService extends AbstractCRUDService<FormMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Email liên hệ', 'forms');
  }


}
