import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {Auth} from '.';
import {StorageUtil} from '../../core/utils';
import {environment} from '../../../environments/environment';

@Injectable()
export class AuthService extends AbstractCRUDService<Auth> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Xác thực tài khoản', 'login');
    this.setApiUrl(environment.passport_api_url);
  }

  logOut() {
    StorageUtil.clear();
    setTimeout(function () {
      window.location.href = `/login`;
    }, 500);
  }

  getProfile() {
    let user = StorageUtil.getUser();
    if (user.photoUrl == null || user.photoUrl.length === 0 || user.photoUrl == 'null') {
      user.photoUrl = '/assets/avatar5.png';
    }
    if (user.name == null || user.name.length === 0 || user.name == 'null') {
      user.name = 'Người dùng';
    }
    return user;
  }

}
