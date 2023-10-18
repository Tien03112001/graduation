import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {Auth} from '.';
import {StorageUtil} from '../../core/utils';
import {Router} from '@angular/router';

@Injectable()
export class AuthService extends AbstractCRUDService<Auth> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thông tin đăng nhập', 'login');
  }

  getProfile() {
    let user = StorageUtil.getUser();
    if (user.photoUrl == undefined
      || user.photoUrl.length === 0
      || user.photoUrl == 'null'
      || user.photoUrl == 'undefined') {
      user.photoUrl = '/assets/avatar5.png';
    }
    if (user.name == undefined
      || user.name.length === 0
      || user.name == 'null'
      || user.name == 'undefined') {
      user.name = 'Người dùng';
    }
    return user;
  }
}
