import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {ToasterService} from 'angular2-toaster';
import {UserMeta} from './user.meta';
import {Observable} from 'rxjs/Observable';
import {AbstractCRUDService, DataResponse, TitleService} from '../../core';

@Injectable()
export class UserService extends AbstractCRUDService<UserMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Nhân viên', 'users');
  }

  editRoles(id: number, roles: number[]): Observable<DataResponse<UserMeta>> {
    return this.toPipe(this.http.post<DataResponse<UserMeta>>(`${this.urlRestAPI}/${id}/roles`, {roles: roles}));
  }

}
