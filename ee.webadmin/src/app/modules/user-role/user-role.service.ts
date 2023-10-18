import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ToasterService } from 'angular2-toaster';
import { UserRoleMeta } from './user-role.meta';
import { AbstractCRUDService } from '../../core/crud';
import { TitleService } from '../../core/services';
import { DataResponse } from '../../core/common';

@Injectable()
export class UserRoleService extends AbstractCRUDService<UserRoleMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'quản lý role', 'user_roles');
  }

  attachUserRole(body: any) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/attach`, body));
  }

  detachUserRole(body: any) {
    return this.toPipe(this.http.post<DataResponse<any>>(`${this.urlRestAPI}/detach`, body));
  }

}
