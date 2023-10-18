import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {FormAttributeMeta} from './form-attribute.meta';

@Injectable()
export class FormAttributeService extends AbstractCRUDService<FormAttributeMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Form', 'form_attributes');
  }


}
