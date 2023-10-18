import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService, TitleService} from '../../../core';
import {ToasterService} from 'angular2-toaster';
import {UserMeta} from './user.meta';
import {environment} from '../../../../environments/environment';

@Injectable()
export class UserService extends AbstractCRUDService<UserMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Tài khoản', 'users');
    this.setApiUrl(environment.api_url);
  }

}
