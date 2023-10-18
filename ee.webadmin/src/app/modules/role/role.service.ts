import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {TitleService} from '../../core/services';
import {ToasterService} from 'angular2-toaster';
import {RoleMeta} from './role.meta';
import {environment} from '../../../environments/environment';
import { DataResponse } from '../../core/common';

@Injectable()
export class RoleService extends AbstractCRUDService<RoleMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'role', 'roles');
  }

  attachRole(body: any) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/roles/attach`, body));
  }

  detachRole(body: any) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/roles/detach`, body));
  }

}
