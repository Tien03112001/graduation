import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import 'rxjs/add/operator/map';
import {Observable} from 'rxjs';
import {UserMeta} from '../user/user.meta';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {DataResponse} from '../../core/common/data-response.metadata';

@Injectable()
export class ProfileService extends AbstractCRUDService<UserMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Thông tin cá nhân', 'profile');
  }


  get(): Observable<UserMeta> {
    return this.toPipe(this.http.get<DataResponse<UserMeta>>('profile'));
  }

  update(user: any): Observable<any> {
    return this.toPipe(this.http.post<DataResponse<any>>('profile', user));
  }

}
