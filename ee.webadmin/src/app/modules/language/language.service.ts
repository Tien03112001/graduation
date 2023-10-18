import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {LanguageMeta} from './language.meta';

@Injectable()
export class LanguageService extends AbstractCRUDService<LanguageMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Ngôn ngữ', 'languages');
  }

}
