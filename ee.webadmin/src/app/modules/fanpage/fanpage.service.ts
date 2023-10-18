import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {TitleService} from '../../core/services';
import {ToasterService} from 'angular2-toaster';
import {FanpageMeta} from './fanpage.meta';
import { environment } from '../../../environments/environment';

@Injectable()
export class FanpageService extends AbstractCRUDService<FanpageMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Fanpage', 'facebook_fanpages');
    this.setApiUrl(environment.chat_api_url);
  }

}
